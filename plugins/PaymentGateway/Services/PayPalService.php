<?php

namespace Plugins\PaymentGateway\Services;

use Plugins\PaymentGateway\Models\PaymentGateway;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PayPal REST API v2 Service
 * 
 * Based on official documentation:
 * - https://developer.paypal.com/api/rest/
 * - https://developer.paypal.com/docs/api/orders/v2/
 * - https://developer.paypal.com/docs/api/webhooks/
 * 
 * Reference implementations:
 * - WooCommerce PayPal Commerce plugin
 * - Magento PayPal module
 */
class PayPalService
{
    protected PaymentGateway $gateway;
    protected array $credentials;
    protected bool $testMode;
    protected string $apiBaseUrl;
    protected ?string $accessToken = null;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->credentials = $gateway->credentials ?? [];
        $this->testMode = $gateway->test_mode;

        // Set API endpoint based on test/live mode
        $this->apiBaseUrl = $this->testMode 
            ? 'https://api-m.sandbox.paypal.com'  // Sandbox
            : 'https://api-m.paypal.com';         // Production
    }

    /**
     * Get OAuth 2.0 access token
     * 
     * @see https://developer.paypal.com/api/rest/authentication/
     */
    protected function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $clientId = $this->testMode 
            ? ($this->credentials['sandbox_client_id'] ?? '')
            : ($this->credentials['live_client_id'] ?? '');

        $secret = $this->testMode 
            ? ($this->credentials['sandbox_secret'] ?? '')
            : ($this->credentials['live_secret'] ?? '');

        if (empty($clientId) || empty($secret)) {
            throw new Exception('PayPal API credentials not configured');
        }

        try {
            $response = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post($this->apiBaseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$response->successful()) {
                throw new Exception('PayPal authentication failed: ' . $response->body());
            }

            $data = $response->json();
            $this->accessToken = $data['access_token'] ?? '';

            return $this->accessToken;
        } catch (Exception $e) {
            Log::error('PayPal OAuth error: ' . $e->getMessage());
            throw new Exception('Failed to authenticate with PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Create PayPal order for product checkout
     * 
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_create
     * 
     * @param array $orderData Order details with items, customer info
     * @return array Order response with id and approval URL
     */
    public function createOrder(array $orderData): array
    {
        try {
            $token = $this->getAccessToken();

            // Build line items
            $items = [];
            $totalAmount = 0;

            foreach ($orderData['items'] as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $totalAmount += $itemTotal;

                $items[] = [
                    'name' => $item['name'],
                    'description' => $item['description'] ?? '',
                    'sku' => $item['sku'] ?? '',
                    'unit_amount' => [
                        'currency_code' => 'RON',
                        'value' => number_format($item['price'], 2, '.', '')
                    ],
                    'quantity' => (string)$item['quantity'],
                    'category' => $item['category'] ?? 'PHYSICAL_GOODS'
                ];
            }

            // Calculate fees
            $fees = $this->gateway->calculateFee($totalAmount);
            $grandTotal = $fees['total_with_fees'];

            // Build order payload
            $payload = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $orderData['order_id'] ?? uniqid('order_'),
                        'description' => $orderData['description'] ?? 'Order from ' . config('app.name'),
                        'custom_id' => $orderData['custom_id'] ?? null,
                        'soft_descriptor' => substr(config('app.name'), 0, 22),
                        'amount' => [
                            'currency_code' => 'RON',
                            'value' => number_format($grandTotal, 2, '.', ''),
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => 'RON',
                                    'value' => number_format($totalAmount, 2, '.', '')
                                ],
                                'handling' => [
                                    'currency_code' => 'RON',
                                    'value' => number_format($fees['total_fees'], 2, '.', '')
                                ]
                            ]
                        ],
                        'items' => $items
                    ]
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'locale' => 'ro-RO',
                    'landing_page' => 'BILLING',
                    'shipping_preference' => $orderData['shipping_required'] ?? true ? 'GET_FROM_FILE' : 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => $this->gateway->callback_url ?? url('/payment/paypal/return'),
                    'cancel_url' => url('/payment/paypal/cancel')
                ]
            ];

            // Add customer info if available
            if (!empty($orderData['customer_email'])) {
                $payload['payer'] = [
                    'email_address' => $orderData['customer_email']
                ];
            }

            // Add shipping info if available
            if (!empty($orderData['shipping'])) {
                $payload['purchase_units'][0]['shipping'] = [
                    'name' => [
                        'full_name' => $orderData['shipping']['name'] ?? ''
                    ],
                    'address' => [
                        'address_line_1' => $orderData['shipping']['address_line_1'] ?? '',
                        'address_line_2' => $orderData['shipping']['address_line_2'] ?? '',
                        'admin_area_2' => $orderData['shipping']['city'] ?? '',
                        'admin_area_1' => $orderData['shipping']['state'] ?? '',
                        'postal_code' => $orderData['shipping']['postal_code'] ?? '',
                        'country_code' => $orderData['shipping']['country_code'] ?? 'RO'
                    ]
                ];
            }

            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => uniqid('req_'),
                ])
                ->post($this->apiBaseUrl . '/v2/checkout/orders', $payload);

            if (!$response->successful()) {
                Log::error('PayPal create order error: ' . $response->body());
                throw new Exception('PayPal order creation failed: ' . $response->body());
            }

            $data = $response->json();

            // Find approval URL
            $approvalUrl = '';
            foreach ($data['links'] ?? [] as $link) {
                if ($link['rel'] === 'approve') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }

            return [
                'success' => true,
                'order_id' => $data['id'],
                'status' => $data['status'],
                'approval_url' => $approvalUrl,
                'payment_url' => $approvalUrl, // Alias for compatibility
                'raw_response' => $data
            ];

        } catch (Exception $e) {
            Log::error('PayPal order creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Capture payment for approved order
     * 
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_capture
     * 
     * @param string $orderId PayPal order ID
     * @return array Capture response
     */
    public function captureOrder(string $orderId): array
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => uniqid('cap_'),
                ])
                ->post($this->apiBaseUrl . "/v2/checkout/orders/{$orderId}/capture");

            if (!$response->successful()) {
                Log::error('PayPal capture order error: ' . $response->body());
                throw new Exception('PayPal capture failed: ' . $response->body());
            }

            $data = $response->json();

            return [
                'success' => true,
                'order_id' => $data['id'],
                'status' => $data['status'],
                'payer' => $data['payer'] ?? [],
                'purchase_units' => $data['purchase_units'] ?? [],
                'raw_response' => $data
            ];

        } catch (Exception $e) {
            Log::error('PayPal capture error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get order details
     * 
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_get
     * 
     * @param string $orderId PayPal order ID
     * @return array Order details
     */
    public function getOrder(string $orderId): array
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->get($this->apiBaseUrl . "/v2/checkout/orders/{$orderId}");

            if (!$response->successful()) {
                throw new Exception('Failed to retrieve PayPal order: ' . $response->body());
            }

            return [
                'success' => true,
                'order' => $response->json()
            ];

        } catch (Exception $e) {
            Log::error('PayPal get order error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature
     * 
     * @see https://developer.paypal.com/api/rest/webhooks/#verify-signature
     * 
     * @param string $webhookId Webhook ID from PayPal dashboard
     * @param array $headers Request headers
     * @param string $body Raw request body
     * @return bool Whether signature is valid
     */
    public function verifyWebhookSignature(string $webhookId, array $headers, string $body): bool
    {
        try {
            $token = $this->getAccessToken();

            $payload = [
                'auth_algo' => $headers['paypal-auth-algo'] ?? $headers['PAYPAL-AUTH-ALGO'] ?? '',
                'cert_url' => $headers['paypal-cert-url'] ?? $headers['PAYPAL-CERT-URL'] ?? '',
                'transmission_id' => $headers['paypal-transmission-id'] ?? $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
                'transmission_sig' => $headers['paypal-transmission-sig'] ?? $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
                'transmission_time' => $headers['paypal-transmission-time'] ?? $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
                'webhook_id' => $webhookId,
                'webhook_event' => json_decode($body, true)
            ];

            $response = Http::withToken($token)
                ->post($this->apiBaseUrl . '/v1/notifications/verify-webhook-signature', $payload);

            if (!$response->successful()) {
                Log::warning('PayPal webhook verification failed: ' . $response->body());
                return false;
            }

            $data = $response->json();
            return ($data['verification_status'] ?? '') === 'SUCCESS';

        } catch (Exception $e) {
            Log::error('PayPal webhook verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process webhook event
     * 
     * @see https://developer.paypal.com/api/rest/webhooks/event-names/
     * 
     * @param array $event Webhook event data
     * @return array Processing result
     */
    public function processWebhook(array $event): array
    {
        try {
            $eventType = $event['event_type'] ?? '';
            $resource = $event['resource'] ?? [];

            Log::info('PayPal webhook received', [
                'event_type' => $eventType,
                'resource_id' => $resource['id'] ?? null
            ]);

            switch ($eventType) {
                case 'CHECKOUT.ORDER.APPROVED':
                    // Order approved by customer, ready to capture
                    return [
                        'success' => true,
                        'action' => 'order_approved',
                        'order_id' => $resource['id'] ?? null,
                        'status' => 'approved'
                    ];

                case 'CHECKOUT.ORDER.COMPLETED':
                case 'PAYMENT.CAPTURE.COMPLETED':
                    // Payment captured successfully
                    return [
                        'success' => true,
                        'action' => 'payment_completed',
                        'order_id' => $resource['id'] ?? null,
                        'status' => 'completed',
                        'amount' => $resource['amount'] ?? null
                    ];

                case 'PAYMENT.CAPTURE.DENIED':
                case 'PAYMENT.CAPTURE.DECLINED':
                    // Payment denied/declined
                    return [
                        'success' => true,
                        'action' => 'payment_failed',
                        'order_id' => $resource['id'] ?? null,
                        'status' => 'failed',
                        'reason' => $resource['status_details']['reason'] ?? 'unknown'
                    ];

                case 'PAYMENT.CAPTURE.REFUNDED':
                    // Refund processed
                    return [
                        'success' => true,
                        'action' => 'payment_refunded',
                        'order_id' => $resource['id'] ?? null,
                        'status' => 'refunded',
                        'amount' => $resource['amount'] ?? null
                    ];

                default:
                    Log::info('PayPal unhandled webhook event: ' . $eventType);
                    return [
                        'success' => true,
                        'action' => 'unhandled',
                        'event_type' => $eventType
                    ];
            }

        } catch (Exception $e) {
            Log::error('PayPal webhook processing error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create refund for captured payment
     * 
     * @see https://developer.paypal.com/docs/api/payments/v2/#captures_refund
     * 
     * @param string $captureId Capture ID to refund
     * @param float|null $amount Amount to refund (null for full refund)
     * @param string|null $note Refund note
     * @return array Refund response
     */
    public function refundCapture(string $captureId, ?float $amount = null, ?string $note = null): array
    {
        try {
            $token = $this->getAccessToken();

            $payload = [];

            if ($amount !== null) {
                $payload['amount'] = [
                    'currency_code' => 'RON',
                    'value' => number_format($amount, 2, '.', '')
                ];
            }

            if ($note !== null) {
                $payload['note_to_payer'] = $note;
            }

            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => uniqid('ref_'),
                ])
                ->post($this->apiBaseUrl . "/v2/payments/captures/{$captureId}/refund", $payload);

            if (!$response->successful()) {
                Log::error('PayPal refund error: ' . $response->body());
                throw new Exception('PayPal refund failed: ' . $response->body());
            }

            $data = $response->json();

            return [
                'success' => true,
                'refund_id' => $data['id'],
                'status' => $data['status'],
                'raw_response' => $data
            ];

        } catch (Exception $e) {
            Log::error('PayPal refund error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
