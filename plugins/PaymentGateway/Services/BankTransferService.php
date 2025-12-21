<?php

namespace Plugins\PaymentGateway\Services;

use Plugins\PaymentGateway\Models\PaymentGateway;
use Illuminate\Support\Facades\Log;

/**
 * Bank Transfer Service
 * 
 * Generates bank transfer instructions with IBAN/SWIFT validation
 */
class BankTransferService
{
    protected PaymentGateway $gateway;
    protected array $bankDetails;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->bankDetails = $gateway->credentials ?? [];
    }

    /**
     * Generate bank transfer instructions
     * 
     * @param array $orderData Order details
     * @return array Transfer instructions
     */
    public function generateInstructions(array $orderData): array
    {
        $amount = $orderData['amount'] ?? 0;
        $currency = $orderData['currency'] ?? 'RON';
        $orderId = $orderData['order_id'] ?? uniqid('order_');

        // Calculate fees
        $fees = $this->gateway->calculateFee($amount);
        $totalAmount = $fees['total_with_fees'];

        // Validate bank details
        if (!$this->validateBankDetails()) {
            return [
                'success' => false,
                'error' => 'Bank details not properly configured'
            ];
        }

        $iban = $this->bankDetails['iban'] ?? '';
        $swift = $this->bankDetails['swift_bic'] ?? '';
        $bankName = $this->bankDetails['bank_name'] ?? '';
        $accountHolder = $this->bankDetails['account_holder'] ?? '';
        $bankAddress = $this->bankDetails['bank_address'] ?? '';

        $instructions = [
            'success' => true,
            'payment_method' => 'bank_transfer',
            'order_id' => $orderId,
            'amount' => $totalAmount,
            'currency' => $currency,
            'bank_details' => [
                'bank_name' => $bankName,
                'account_holder' => $accountHolder,
                'iban' => $this->formatIBAN($iban),
                'swift_bic' => $swift,
                'bank_address' => $bankAddress,
            ],
            'payment_reference' => 'Order #' . $orderId,
            'instructions' => $this->getTransferInstructions($orderId, $totalAmount, $currency),
            'qr_code_data' => $this->generateQRCodeData($iban, $accountHolder, $totalAmount, $currency, $orderId),
        ];

        Log::info('Bank transfer instructions generated', [
            'order_id' => $orderId,
            'amount' => $totalAmount
        ]);

        return $instructions;
    }

    /**
     * Validate bank details configuration
     * 
     * @return bool Whether bank details are valid
     */
    protected function validateBankDetails(): bool
    {
        $iban = $this->bankDetails['iban'] ?? '';
        $swift = $this->bankDetails['swift_bic'] ?? '';

        if (empty($iban) || empty($swift)) {
            return false;
        }

        if (!$this->validateIBAN($iban)) {
            Log::error('Invalid IBAN format: ' . $iban);
            return false;
        }

        if (!$this->validateSWIFT($swift)) {
            Log::error('Invalid SWIFT/BIC format: ' . $swift);
            return false;
        }

        return true;
    }

    /**
     * Validate IBAN using modulo 97 check
     * 
     * @param string $iban IBAN to validate
     * @return bool Whether IBAN is valid
     */
    public function validateIBAN(string $iban): bool
    {
        // Remove spaces and convert to uppercase
        $iban = strtoupper(str_replace(' ', '', $iban));

        // Check length (15-34 characters)
        if (strlen($iban) < 15 || strlen($iban) > 34) {
            return false;
        }

        // Check format (2 letters + 2 digits + alphanumeric)
        if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]+$/', $iban)) {
            return false;
        }

        // Move first 4 characters to end
        $rearranged = substr($iban, 4) . substr($iban, 0, 4);

        // Replace letters with numbers (A=10, B=11, ..., Z=35)
        $numeric = '';
        for ($i = 0; $i < strlen($rearranged); $i++) {
            $char = $rearranged[$i];
            if (ctype_alpha($char)) {
                $numeric .= (ord($char) - 55);
            } else {
                $numeric .= $char;
            }
        }

        // Calculate modulo 97
        return bcmod($numeric, '97') === '1';
    }

    /**
     * Validate SWIFT/BIC code
     * 
     * @param string $swift SWIFT/BIC code
     * @return bool Whether SWIFT is valid
     */
    public function validateSWIFT(string $swift): bool
    {
        // Remove spaces
        $swift = str_replace(' ', '', $swift);

        // SWIFT format: 8 or 11 characters
        // AAAA BB CC [DDD]
        // AAAA = Bank code (4 letters)
        // BB = Country code (2 letters)
        // CC = Location code (2 alphanumeric)
        // DDD = Branch code (3 alphanumeric, optional)
        
        if (!preg_match('/^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$/', strtoupper($swift))) {
            return false;
        }

        return true;
    }

    /**
     * Format IBAN with spaces every 4 characters
     * 
     * @param string $iban IBAN to format
     * @return string Formatted IBAN
     */
    public function formatIBAN(string $iban): string
    {
        $iban = strtoupper(str_replace(' ', '', $iban));
        return trim(chunk_split($iban, 4, ' '));
    }

    /**
     * Get transfer instructions text
     * 
     * @param string $orderId Order ID
     * @param float $amount Amount
     * @param string $currency Currency
     * @return array Instructions array
     */
    protected function getTransferInstructions(string $orderId, float $amount, string $currency): array
    {
        return [
            'ro' => [
                'Efectuați transferul bancar folosind datele de mai sus.',
                'Este important să includeți referința comenzii în descrierea plății.',
                'Comanda dvs. va fi procesată după confirmarea plății.',
                'Timpul de procesare: 1-3 zile lucrătoare.',
            ],
            'en' => [
                'Make the bank transfer using the details above.',
                'It is important to include the order reference in the payment description.',
                'Your order will be processed after payment confirmation.',
                'Processing time: 1-3 business days.',
            ]
        ];
    }

    /**
     * Generate QR code data for payment
     * 
     * Uses EPC QR Code format (European Payments Council)
     * 
     * @param string $iban IBAN
     * @param string $beneficiary Beneficiary name
     * @param float $amount Amount
     * @param string $currency Currency
     * @param string $reference Payment reference
     * @return string QR code data string
     */
    public function generateQRCodeData(string $iban, string $beneficiary, float $amount, string $currency, string $reference): string
    {
        // EPC QR Code format
        // https://www.europeanpaymentscouncil.eu/document-library/guidance-documents/quick-response-code-guidelines-enable-data-capture-initiation
        
        $data = [
            'BCD',                                          // Service Tag
            '002',                                          // Version
            '1',                                            // Character Set (1 = UTF-8)
            'SCT',                                          // Identification (SEPA Credit Transfer)
            $this->bankDetails['swift_bic'] ?? '',         // BIC
            $beneficiary,                                   // Beneficiary Name
            str_replace(' ', '', $iban),                    // Beneficiary Account (IBAN)
            $currency . number_format($amount, 2, '.', ''), // Amount
            '',                                             // Purpose (empty)
            $reference,                                     // Structured Reference
            'Order payment',                                // Unstructured Remittance
            '',                                             // Beneficiary to Originator Information
        ];

        return implode("\n", $data);
    }
}
