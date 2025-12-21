<?php

namespace Plugins\PaymentGateway\Services;

use Plugins\PaymentGateway\Models\PaymentGateway;
use Exception;

class PaymentService
{
    protected PaymentGateway $gateway;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Create a quick payment link
     */
    public function createQuickPaymentLink(float $amount, string $description, array $metadata = []): ?string
    {
        if (!$this->gateway->supports_quick_links) {
            throw new Exception('This gateway does not support quick payment links');
        }

        switch ($this->gateway->provider) {
            case 'stripe':
                $service = new StripeService($this->gateway);
                return $service->createQuickPaymentLink($amount, $description, $metadata);
            case 'euplatesc':
                return $this->createEuPlatescPaymentLink($amount, $description, $metadata);
            default:
                throw new Exception('Provider not supported for quick links');
        }
    }

    /**
     * Process product checkout payment
     */
    public function processCheckoutPayment(array $orderData): array
    {
        if (!$this->gateway->supports_product_checkout) {
            throw new Exception('This gateway does not support product checkout');
        }

        switch ($this->gateway->provider) {
            case 'stripe':
                $service = new StripeService($this->gateway);
                return $service->createCheckoutSession($orderData);
            case 'paypal':
                $service = new PayPalService($this->gateway);
                return $service->createOrder($orderData);
            case 'euplatesc':
                $euplatescService = new EuPlatescService($this->gateway);
                return $euplatescService->createPayment($orderData);
            case 'netopia':
                $netopiaService = new NetopiaService($this->gateway);
                return $netopiaService->createPayment($orderData);
            case 'bank_transfer':
                $bankService = new BankTransferService($this->gateway);
                return $bankService->generateInstructions($orderData);
            default:
                throw new Exception('Provider not supported for checkout');
        }
    }

    /**
     * EuPlatesc: Create payment link for quick payments
     */
    protected function createEuPlatescPaymentLink(float $amount, string $description, array $metadata): string
    {
        $euplatescService = new EuPlatescService($this->gateway);
        $paymentData = [
            'amount' => $amount,
            'description' => $description,
            'order_id' => uniqid('quick_'),
            'metadata' => $metadata
        ];
        $result = $euplatescService->createPayment($paymentData);
        return $result['payment_url'] ?? '';
    }

    /**
     * Calculate fees for this gateway
     */
    public function calculateFees(float $amount): array
    {
        $fee = $this->gateway->calculateFee($amount);
        $total = $this->gateway->getTotalWithFees($amount);

        return [
            'amount' => $amount,
            'fee' => $fee,
            'total' => $total,
            'fee_percentage' => $this->gateway->fee_percentage,
            'fee_fixed' => $this->gateway->fee_fixed,
        ];
    }
}
