<?php

namespace Plugins\PaymentGateway\Services;

use Plugins\PaymentGateway\Models\PaymentGateway;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Netopia (MobilPay) Payment Gateway Service
 * 
 * Based on official documentation:
 * - https://netopia-payments.com/en/documentatie/
 * 
 * Uses RSA encryption with public/private key pairs
 */
class NetopiaService
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
            ? 'http://sandboxsecure.mobilpay.ro'  // Sandbox
            : 'https://secure.mobilpay.ro';        // Production
    }

    /**
     * Create payment for Netopia
     * 
     * @param array $orderData Order details
     * @return array Payment response with form data
     */
    public function createPayment(array $orderData): array
    {
        $signature = $this->credentials['signature'] ?? '';
        $publicKeyPath = $this->credentials['public_key_path'] ?? '';

        if (empty($signature) || empty($publicKeyPath)) {
            throw new Exception('Netopia credentials not configured');
        }

        if (!file_exists($publicKeyPath)) {
            throw new Exception('Netopia public key file not found: ' . $publicKeyPath);
        }

        try {
            $amount = $orderData['amount'] ?? 0;
            $currency = $orderData['currency'] ?? 'RON';
            $orderId = $orderData['order_id'] ?? uniqid('order_');
            $description = $orderData['description'] ?? 'Plata comanda';

            // Calculate fees
            $fees = $this->gateway->calculateFee($amount);
            $totalAmount = $fees['total_with_fees'];

            // Build XML request (Netopia format)
            $xmlData = $this->buildXML([
                'order_id' => $orderId,
                'amount' => $totalAmount,
                'currency' => $currency,
                'description' => $description,
                'customer_email' => $orderData['customer_email'] ?? '',
                'customer_phone' => $orderData['customer_phone'] ?? '',
                'customer_name' => $orderData['customer_name'] ?? '',
                'return_url' => $this->gateway->callback_url ?? url('/payment/netopia/return'),
                'confirm_url' => $this->gateway->webhook_url ?? url('/webhooks/netopia'),
            ]);

            // Encrypt XML with public key
            $encryptedData = $this->encryptData($xmlData, $publicKeyPath);
            $envelopeData = base64_encode($encryptedData['envelope']);
            $envKeyData = base64_encode($encryptedData['env_key']);

            // Payment form URL
            $paymentUrl = $this->apiUrl . '/pay';

            Log::info('Netopia payment created', [
                'order_id' => $orderId,
                'amount' => $totalAmount
            ]);

            return [
                'success' => true,
                'payment_url' => $paymentUrl,
                'order_id' => $orderId,
                'amount' => $totalAmount,
                'form_data' => [
                    'env_key' => $envKeyData,
                    'data' => $envelopeData,
                    'signature' => $signature,
                ],
                'requires_post' => true // This gateway requires POST form submission
            ];

        } catch (Exception $e) {
            Log::error('Netopia payment creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Build XML data for Netopia
     * 
     * @param array $data Payment data
     * @return string XML string
     */
    protected function buildXML(array $data): string
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $xml .= '<order type="card" id="' . htmlspecialchars($data['order_id']) . '" timestamp="' . date('YmdHis') . '">' . "\n";
        $xml .= '  <signature>' . $this->credentials['signature'] . '</signature>' . "\n";
        $xml .= '  <invoice currency="' . htmlspecialchars($data['currency']) . '" amount="' . number_format($data['amount'], 2, '.', '') . '">' . "\n";
        $xml .= '    <details>' . htmlspecialchars($data['description']) . '</details>' . "\n";
        $xml .= '    <contact_info>' . "\n";
        
        if (!empty($data['customer_email'])) {
            $xml .= '      <email>' . htmlspecialchars($data['customer_email']) . '</email>' . "\n";
        }
        if (!empty($data['customer_phone'])) {
            $xml .= '      <mobile_phone>' . htmlspecialchars($data['customer_phone']) . '</mobile_phone>' . "\n";
        }
        if (!empty($data['customer_name'])) {
            $names = explode(' ', $data['customer_name'], 2);
            $xml .= '      <first_name>' . htmlspecialchars($names[0]) . '</first_name>' . "\n";
            if (isset($names[1])) {
                $xml .= '      <last_name>' . htmlspecialchars($names[1]) . '</last_name>' . "\n";
            }
        }
        
        $xml .= '    </contact_info>' . "\n";
        $xml .= '  </invoice>' . "\n";
        $xml .= '  <url>' . "\n";
        $xml .= '    <return>' . htmlspecialchars($data['return_url']) . '</return>' . "\n";
        $xml .= '    <confirm>' . htmlspecialchars($data['confirm_url']) . '</confirm>' . "\n";
        $xml .= '  </url>' . "\n";
        $xml .= '</order>';

        return $xml;
    }

    /**
     * Encrypt data with RSA public key
     * 
     * @param string $data Data to encrypt
     * @param string $publicKeyPath Path to public key file
     * @return array Encrypted envelope and key
     */
    protected function encryptData(string $data, string $publicKeyPath): array
    {
        // Load public key
        $publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));
        
        if ($publicKey === false) {
            throw new Exception('Failed to load Netopia public key');
        }

        // Generate random encryption key
        $encryptionKey = openssl_random_pseudo_bytes(32);

        // Encrypt data with AES
        $iv = openssl_random_pseudo_bytes(16);
        $encryptedData = openssl_encrypt($data, 'AES-256-CBC', $encryptionKey, OPENSSL_RAW_DATA, $iv);
        $envelope = $iv . $encryptedData;

        // Encrypt the encryption key with RSA public key
        $encryptedKey = '';
        openssl_public_encrypt($encryptionKey, $encryptedKey, $publicKey);

        openssl_free_key($publicKey);

        return [
            'envelope' => $envelope,
            'env_key' => $encryptedKey
        ];
    }

    /**
     * Decrypt callback data
     * 
     * @param string $envelope Encrypted envelope
     * @param string $envKey Encrypted key
     * @return string Decrypted XML data
     */
    public function decryptCallback(string $envelope, string $envKey): string
    {
        $privateKeyPath = $this->credentials['private_key_path'] ?? '';
        $privateKeyPassword = $this->credentials['private_key_password'] ?? '';

        if (empty($privateKeyPath) || !file_exists($privateKeyPath)) {
            throw new Exception('Netopia private key not configured or not found');
        }

        // Load private key
        $privateKeyContent = file_get_contents($privateKeyPath);
        $privateKey = openssl_pkey_get_private($privateKeyContent, $privateKeyPassword);

        if ($privateKey === false) {
            throw new Exception('Failed to load Netopia private key');
        }

        // Decrypt encryption key with RSA
        $decryptedKey = '';
        openssl_private_decrypt($envKey, $decryptedKey, $privateKey);

        // Decrypt data with AES
        $iv = substr($envelope, 0, 16);
        $encryptedData = substr($envelope, 16);
        $decryptedData = openssl_decrypt($encryptedData, 'AES-256-CBC', $decryptedKey, OPENSSL_RAW_DATA, $iv);

        openssl_free_key($privateKey);

        return $decryptedData;
    }

    /**
     * Process IPN (Instant Payment Notification)
     * 
     * @param string $envelope Encrypted envelope from POST
     * @param string $envKey Encrypted key from POST
     * @return array Processing result
     */
    public function processIPN(string $envelope, string $envKey): array
    {
        try {
            // Decrypt callback data
            $xmlData = $this->decryptCallback(base64_decode($envelope), base64_decode($envKey));

            // Parse XML
            $xml = simplexml_load_string($xmlData);
            
            if ($xml === false) {
                throw new Exception('Invalid XML data in callback');
            }

            $orderId = (string)$xml['id'];
            $action = (string)$xml->mobilpay->action;
            $errorCode = (string)($xml->mobilpay->error['code'] ?? '0');
            $errorMessage = (string)($xml->mobilpay->error ?? '');
            $amount = (float)($xml->invoice['amount'] ?? 0);
            $currency = (string)($xml->invoice['currency'] ?? 'RON');

            Log::info('Netopia IPN received', [
                'order_id' => $orderId,
                'action' => $action,
                'error_code' => $errorCode
            ]);

            // Determine status
            $status = 'pending';
            if ($action === 'confirmed' && $errorCode === '0') {
                $status = 'completed';
            } elseif ($action === 'confirmed_pending') {
                $status = 'pending';
            } elseif ($action === 'paid_pending') {
                $status = 'pending';
            } elseif ($action === 'paid') {
                $status = 'completed';
            } elseif ($action === 'canceled') {
                $status = 'canceled';
            } elseif ($action === 'credit') {
                $status = 'refunded';
            }

            return [
                'success' => true,
                'order_id' => $orderId,
                'action' => $action,
                'status' => $status,
                'amount' => $amount,
                'currency' => $currency,
                'error_code' => $errorCode,
                'error_message' => $errorMessage
            ];

        } catch (Exception $e) {
            Log::error('Netopia IPN processing error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
