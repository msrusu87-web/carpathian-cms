<?php

namespace Plugins\PaymentGateway\Services;

use Plugins\PaymentGateway\Models\PaymentGateway;
use Exception;
use Stripe\Stripe as StripeClient;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\PaymentLink;

/**
 * Stripe Payment Gateway Service
 * Official Documentation: https://stripe.com/docs/api
 * Based on: WooCommerce Stripe Plugin, Stripe PHP Library
 */
class StripeService
{
    protected PaymentGateway $gateway;
    protected array $credentials;
    protected bool $testMode;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->credentials = $gateway->credentials ?? [];
        $this->testMode = $gateway->test_mode;

        // Initialize Stripe with API key
        $apiKey = $this->testMode 
            ? ($this->credentials['test_secret_key'] ?? $this->credentials['secret_key'] ?? '')
            : ($this->credentials['live_secret_key'] ?? $this->credentials['secret_key'] ?? '');

        if (empty($apiKey)) {
            throw new Exception('Stripe API key not configured');
        }

        StripeClient::setApiKey($apiKey);
        StripeClient::setApiVersion('2024-12-18.acacia'); // Latest API version
    }

    /**
     * Create Quick Payment Link
     * Docs: https://stripe.com/docs/payment-links
     */
    public function createQuickPaymentLink(float $amount, string $description, array $metadata = []): string
    {
        try {
            // Create one-time product
            $product = \Stripe\Product::create([
                'name' => $description,
                'type' => 'service',
            ]);

            // Create price
            $price = \Stripe\Price::create([
                'product' => $product->id,
                'unit_amount' => round($amount * 100), // Convert to cents
                'currency' => strtolower($this->credentials['currency'] ?? 'ron'),
            ]);

            // Create payment link
            $paymentLink = PaymentLink::create([
                'line_items' => [
                    [
                        'price' => $price->id,
                        'quantity' => 1,
                    ],
                ],
                'metadata' => $metadata,
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => [
                        'url' => $this->gateway->callback_url ?? url('/payment/success'),
                    ],
                ],
            ]);

            return $paymentLink->url;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe Quick Payment Link Error: ' . $e->getMessage());
            throw new Exception('Failed to create payment link: ' . $e->getMessage());
        }
    }

    /**
     * Create Checkout Session for Product Purchase
     * Docs: https://stripe.com/docs/payments/checkout
     */
    public function createCheckoutSession(array $orderData): array
    {
        try {
            $lineItems = [];

            // Build line items
            if (!empty($orderData['items'])) {
                foreach ($orderData['items'] as $item) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => strtolower($orderData['currency'] ?? 'ron'),
                            'product_data' => [
                                'name' => $item['name'] ?? 'Product',
                                'description' => $item['description'] ?? '',
                                'images' => $item['images'] ?? [],
                            ],
                            'unit_amount' => round(($item['price'] ?? 0) * 100),
                        ],
                        'quantity' => $item['quantity'] ?? 1,
                    ];
                }
            } else {
                // Single item
                $lineItems[] = [
                    'price_data' => [
                        'currency' => strtolower($orderData['currency'] ?? 'ron'),
                        'product_data' => [
                            'name' => $orderData['description'] ?? 'Payment',
                        ],
                        'unit_amount' => round(($orderData['amount'] ?? 0) * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            $sessionData = [
                'payment_method_types' => $this->credentials['payment_methods'] ?? ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $this->gateway->callback_url ?? url('/payment/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/payment/cancel'),
                'client_reference_id' => $orderData['order_id'] ?? null,
                'metadata' => $orderData['metadata'] ?? [],
            ];

            // Add customer email if provided
            if (!empty($orderData['customer_email'])) {
                $sessionData['customer_email'] = $orderData['customer_email'];
            }

            // Add shipping if provided
            if (!empty($orderData['shipping'])) {
                $sessionData['shipping_address_collection'] = [
                    'allowed_countries' => ['RO', 'US', 'GB', 'DE', 'FR', 'IT', 'ES'],
                ];
            }

            $session = Session::create($sessionData);

            return [
                'success' => true,
                'session_id' => $session->id,
                'payment_url' => $session->url,
                'public_key' => $this->testMode 
                    ? ($this->credentials['test_publishable_key'] ?? $this->credentials['publishable_key'] ?? '')
                    : ($this->credentials['live_publishable_key'] ?? $this->credentials['publishable_key'] ?? ''),
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe Checkout Session Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create Payment Intent (for custom checkout flows)
     * Docs: https://stripe.com/docs/payments/payment-intents
     */
    public function createPaymentIntent(float $amount, array $options = []): array
    {
        try {
            $intentData = [
                'amount' => round($amount * 100),
                'currency' => strtolower($options['currency'] ?? 'ron'),
                'payment_method_types' => $this->credentials['payment_methods'] ?? ['card'],
                'metadata' => $options['metadata'] ?? [],
            ];

            if (!empty($options['customer_email'])) {
                $intentData['receipt_email'] = $options['customer_email'];
            }

            if (!empty($options['description'])) {
                $intentData['description'] = $options['description'];
            }

            $intent = PaymentIntent::create($intentData);

            return [
                'success' => true,
                'client_secret' => $intent->client_secret,
                'payment_intent_id' => $intent->id,
                'public_key' => $this->testMode 
                    ? ($this->credentials['test_publishable_key'] ?? $this->credentials['publishable_key'] ?? '')
                    : ($this->credentials['live_publishable_key'] ?? $this->credentials['publishable_key'] ?? ''),
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe Payment Intent Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify Webhook Signature
     * Docs: https://stripe.com/docs/webhooks/signatures
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $webhookSecret = $this->testMode 
            ? ($this->credentials['test_webhook_secret'] ?? $this->credentials['webhook_secret'] ?? '')
            : ($this->credentials['live_webhook_secret'] ?? $this->credentials['webhook_secret'] ?? '');

        if (empty($webhookSecret)) {
            throw new Exception('Webhook secret not configured');
        }

        try {
            \Stripe\Webhook::constructEvent($payload, $signature, $webhookSecret);
            return true;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            \Log::error('Stripe Webhook Signature Verification Failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process Webhook Event
     */
    public function processWebhook(string $payload, string $signature): array
    {
        try {
            $webhookSecret = $this->testMode 
                ? ($this->credentials['test_webhook_secret'] ?? $this->credentials['webhook_secret'] ?? '')
                : ($this->credentials['live_webhook_secret'] ?? $this->credentials['webhook_secret'] ?? '');

            $event = \Stripe\Webhook::constructEvent($payload, $signature, $webhookSecret);

            return [
                'success' => true,
                'event_type' => $event->type,
                'event_id' => $event->id,
                'data' => $event->data->object,
            ];
        } catch (\Exception $e) {
            \Log::error('Stripe Webhook Processing Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve Session Details
     */
    public function retrieveSession(string $sessionId): array
    {
        try {
            $session = Session::retrieve($sessionId);

            return [
                'success' => true,
                'session_id' => $session->id,
                'payment_status' => $session->payment_status,
                'amount_total' => $session->amount_total / 100,
                'currency' => strtoupper($session->currency),
                'customer_email' => $session->customer_email,
                'metadata' => $session->metadata->toArray(),
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
