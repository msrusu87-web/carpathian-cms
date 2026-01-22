<?php

namespace Plugins\PaymentGateway\Services;

use Plugins\PaymentGateway\Models\PaymentGateway;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * EuPlatesc Payment Gateway Service
 * 
 * Based on official documentation:
 * - https://euplatesc.ro/documentatie-integrare
 * 
 * HMAC-MD5 signature authentication
 */
class EuPlatescService
{
    protected PaymentGateway $gateway;
    protected array $credentials;
    protected bool $testMode;
    protected string $apiUrl;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->credentials = $gateway->credentials ?? [];
        $this->testMode = $gateway->test_mode;
        
        // Set API endpoint based on test/live mode
        $this->apiUrl = $this->testMode
            ? 'https://secure.euplatesc.ro/tdsprocess/tranzactd.php'  // Test
            : 'https://secure.euplatesc.ro/tdsprocess/tranzactd.php'; // Live (same URL)
    }

    /**
     * Create payment for EuPlatesc
     * 
     * @param array $orderData Order details
     * @return array Payment response with redirect URL
     */
    public function createPayment(array $orderData): array
    {
        $merchantId = $this->credentials['merchant_id'] ?? '';
        $secretKey = $this->credentials['secret_key'] ?? '';

        if (empty($merchantId) || empty($secretKey)) {
            throw new Exception('EuPlatesc credentials not configured');
        }

        try {
            $amount = $orderData['amount'] ?? 0;
            $currency = $orderData['currency'] ?? 'RON';
            $orderId = $orderData['order_id'] ?? uniqid('order_');
            $description = $orderData['description'] ?? 'Plata comanda';

            // Calculate fees
            $fees = $this->gateway->calculateFee($amount);
            $totalAmount = $fees['total_with_fees'];

            // EuPlatesc required parameters
            $params = [
                'amount' => number_format($totalAmount, 2, '.', ''),
                'curr' => $currency,
                'invoice_id' => $orderId,
                'order_desc' => $description,
                'merch_id' => $merchantId,
                'timestamp' => gmdate('YmdHis'),
                'nonce' => md5(microtime() . mt_rand()),
            ];

            // Add customer info if available
            if (!empty($orderData['customer_email'])) {
                $params['email'] = $orderData['customer_email'];
            }
            if (!empty($orderData['customer_phone'])) {
                $params['phone'] = $orderData['customer_phone'];
            }
            if (!empty($orderData['customer_name'])) {
                $params['fname'] = $orderData['customer_name'];
            }

            // Add callback URLs
            $params['back_ref'] = $this->gateway->callback_url ?? url('/payment/euplatesc/return');

            // Generate HMAC signature
            $params['fp_hash'] = $this->generateSignature($params, $secretKey);

            // Build payment URL with parameters
            $paymentUrl = $this->apiUrl . '?' . http_build_query($params);

            Log::info('EuPlatesc payment created', [
                'order_id' => $orderId,
                'amount' => $totalAmount
            ]);

            return [
                'success' => true,
                'payment_url' => $paymentUrl,
                'order_id' => $orderId,
                'amount' => $totalAmount,
                'params' => $params
            ];

        } catch (Exception $e) {
            Log::error('EuPlatesc payment creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate HMAC-MD5 signature
     * 
     * @param array $params Payment parameters
     * @param string $secretKey Merchant secret key
     * @return string HMAC signature
     */
    protected function generateSignature(array $params, string $secretKey): string
    {
        // Build string to sign (specific order required by EuPlatesc)
        $signatureString = 
            strlen($params['amount']) . $params['amount'] .
            strlen($params['curr']) . $params['curr'] .
            strlen($params['invoice_id']) . $params['invoice_id'] .
            strlen($params['order_desc']) . $params['order_desc'] .
            strlen($params['merch_id']) . $params['merch_id'] .
            strlen($params['timestamp']) . $params['timestamp'] .
            strlen($params['nonce']) . $params['nonce'];

        // Generate HMAC-MD5
        return strtoupper(hash_hmac('md5', $signatureString, $secretKey));
    }

    /**
     * Verify callback signature
     * 
     * @param array $data Callback data from EuPlatesc
     * @param string $receivedSignature Signature from callback
     * @return bool Whether signature is valid
     */
    public function verifyCallback(array $data, string $receivedSignature): bool
    {
        $secretKey = $this->credentials['secret_key'] ?? '';

        if (empty($secretKey)) {
            Log::error('EuPlatesc: Cannot verify callback - secret key not configured');
            return false;
        }

        try {
            $expectedSignature = $this->generateCallbackSignature($data, $secretKey);
            return hash_equals(strtoupper($expectedSignature), strtoupper($receivedSignature));
        } catch (Exception $e) {
            Log::error('EuPlatesc callback verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate callback signature for verification
     * 
     * @param array $data Callback data
     * @param string $secretKey Merchant secret key
     * @return string Expected signature
     */
    protected function generateCallbackSignature(array $data, string $secretKey): string
    {
        $signatureString = 
            strlen($data['amount']) . $data['amount'] .
            strlen($data['curr']) . $data['curr'] .
            strlen($data['invoice_id']) . $data['invoice_id'] .
            strlen($data['ep_id']) . $data['ep_id'] .
            strlen($data['merch_id']) . $data['merch_id'] .
            strlen($data['action']) . $data['action'] .
            strlen($data['message']) . $data['message'] .
            strlen($data['approval']) . $data['approval'] .
            strlen($data['timestamp']) . $data['timestamp'] .
            strlen($data['nonce']) . $data['nonce'];

        return strtoupper(hash_hmac('md5', $signatureString, $secretKey));
    }

    /**
     * Process IPN (Instant Payment Notification)
     * 
     * @param array $data IPN data from EuPlatesc
     * @return array Processing result
     */
    public function processIPN(array $data): array
    {
        try {
            $signature = $data['fp_hash'] ?? '';
            
            if (!$this->verifyCallback($data, $signature)) {
                Log::warning('EuPlatesc IPN: Invalid signature');
                return [
                    'success' => false,
                    'error' => 'Invalid signature'
                ];
            }

            $action = $data['action'] ?? '';
            $orderId = $data['invoice_id'] ?? '';
            $amount = $data['amount'] ?? 0;
            $transactionId = $data['ep_id'] ?? '';

            Log::info('EuPlatesc IPN received', [
                'order_id' => $orderId,
                'action' => $action,
                'transaction_id' => $transactionId
            ]);

            return [
                'success' => true,
                'action' => $action,
                'order_id' => $orderId,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'status' => $action === '0' ? 'approved' : 'declined',
                'message' => $data['message'] ?? ''
            ];

        } catch (Exception $e) {
            Log::error('EuPlatesc IPN processing error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
