# Payment Gateway Plugin - Complete Setup Guide

This guide covers all 5 payment gateway integrations with production-ready code based on official documentation.

## Table of Contents
- [Overview](#overview)
- [Stripe Setup](#stripe-setup)
- [PayPal Setup](#paypal-setup)
- [EuPlatesc Setup](#euplatesc-setup)
- [Netopia Setup](#netopia-setup)
- [Bank Transfer Setup](#bank-transfer-setup)
- [Testing](#testing)
- [Webhooks](#webhooks)

---

## Overview

All services are production-ready and follow official documentation:
- **Stripe**: API v2024-12-18.acacia with Payment Links, Checkout, Payment Intents
- **PayPal**: REST API v2 with Orders API and OAuth 2.0
- **EuPlatesc**: HMAC-MD5 signature authentication
- **Netopia**: RSA encryption with public/private keys
- **Bank Transfer**: IBAN/SWIFT validation with EPC QR codes

---

## Stripe Setup

### Official Documentation
- https://stripe.com/docs/api
- https://stripe.com/docs/payments/checkout
- https://stripe.com/docs/payments/payment-links

### Configuration in Admin Panel

Navigate to **Admin → Gateway-uri de plată → Create/Edit Stripe**

#### Test Mode Credentials
```json
{
  "test_secret_key": "sk_test_...",
  "test_publishable_key": "pk_test_...",
  "webhook_secret": "whsec_..."
}
```

#### Live Mode Credentials
```json
{
  "live_secret_key": "sk_live_...",
  "live_publishable_key": "pk_live_...",
  "webhook_secret": "whsec_..."
}
```

### Features
- ✅ Quick Payment Links (one-time payments)
- ✅ Checkout Sessions (product purchases with line items)
- ✅ Payment Intents (custom flows)
- ✅ Webhook signature verification
- ✅ Test/Live mode separation

### Usage Example
```php
use Plugins\PaymentGateway\Models\PaymentGateway;
use Plugins\PaymentGateway\Services\StripeService;

$gateway = PaymentGateway::where('provider', 'stripe')->active()->first();
$service = new StripeService($gateway);

// Create quick payment link
$paymentUrl = $service->createQuickPaymentLink(
    amount: 100.00,
    description: 'Test Payment',
    metadata: ['order_id' => '12345']
);

// Create checkout session
$result = $service->createCheckoutSession([
    'items' => [
        [
            'name' => 'Product Name',
            'price' => 50.00,
            'quantity' => 2
        ]
    ],
    'customer_email' => 'customer@example.com',
    'order_id' => '12345'
]);
```

### Webhook Setup
1. Go to Stripe Dashboard → Developers → Webhooks
2. Add endpoint: `https://carphatian.ro/webhooks/stripe`
3. Select events: `checkout.session.completed`, `payment_intent.succeeded`, `payment_intent.payment_failed`
4. Copy webhook secret to admin panel

---

## PayPal Setup

### Official Documentation
- https://developer.paypal.com/api/rest/
- https://developer.paypal.com/docs/api/orders/v2/
- https://developer.paypal.com/docs/api/webhooks/

### Configuration in Admin Panel

Navigate to **Admin → Gateway-uri de plată → Create/Edit PayPal**

#### Sandbox Credentials
```json
{
  "sandbox_client_id": "AZa...",
  "sandbox_secret": "EL_...",
  "webhook_id": "7RN..."
}
```

#### Live Credentials
```json
{
  "live_client_id": "AZa...",
  "live_secret": "EL_...",
  "webhook_id": "7RN..."
}
```

### Features
- ✅ OAuth 2.0 authentication
- ✅ Orders API v2 with line items
- ✅ Automatic payment capture
- ✅ Webhook signature verification
- ✅ Refund support
- ✅ Sandbox/Live mode separation

### Usage Example
```php
use Plugins\PaymentGateway\Models\PaymentGateway;
use Plugins\PaymentGateway\Services\PayPalService;

$gateway = PaymentGateway::where('provider', 'paypal')->active()->first();
$service = new PayPalService($gateway);

// Create order
$result = $service->createOrder([
    'items' => [
        [
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 50.00,
            'quantity' => 2,
            'sku' => 'PROD-001'
        ]
    ],
    'customer_email' => 'customer@example.com',
    'order_id' => '12345',
    'shipping' => [
        'name' => 'John Doe',
        'address_line_1' => '123 Main St',
        'city' => 'Bucharest',
        'postal_code' => '010101',
        'country_code' => 'RO'
    ]
]);

// Redirect to: $result['approval_url']

// After customer approves, capture payment
$capture = $service->captureOrder($result['order_id']);
```

### Webhook Setup
1. Go to PayPal Developer Dashboard → Webhooks
2. Add webhook: `https://carphatian.ro/webhooks/paypal`
3. Select events: `CHECKOUT.ORDER.APPROVED`, `PAYMENT.CAPTURE.COMPLETED`, `PAYMENT.CAPTURE.DENIED`
4. Copy webhook ID to admin panel

---

## EuPlatesc Setup

### Official Documentation
- https://euplatesc.ro/documentatie-integrare

### Configuration in Admin Panel

Navigate to **Admin → Gateway-uri de plată → Create/Edit EuPlatesc**

#### Credentials
```json
{
  "merchant_id": "XXXXX",
  "secret_key": "YYYYYYYYYYYYYYY"
}
```

### Features
- ✅ HMAC-MD5 signature generation
- ✅ Instant Payment Notification (IPN)
- ✅ Signature verification
- ✅ Test/Live mode support

### Usage Example
```php
use Plugins\PaymentGateway\Models\PaymentGateway;
use Plugins\PaymentGateway\Services\EuPlatescService;

$gateway = PaymentGateway::where('provider', 'euplatesc')->active()->first();
$service = new EuPlatescService($gateway);

// Create payment
$result = $service->createPayment([
    'amount' => 100.00,
    'currency' => 'RON',
    'order_id' => '12345',
    'description' => 'Comanda #12345',
    'customer_email' => 'customer@example.com',
    'customer_phone' => '0723456789',
    'customer_name' => 'Ion Popescu'
]);

// Redirect to: $result['payment_url']
```

### IPN Handler
```php
// In your webhook controller
$data = $request->all();
$signature = $data['fp_hash'] ?? '';

$result = $service->processIPN($data);

if ($result['success'] && $result['status'] === 'approved') {
    // Payment successful
    // Update order status
}

// Always respond with 200 OK
return response('OK', 200);
```

---

## Netopia Setup

### Official Documentation
- https://netopia-payments.com/en/documentatie/

### Configuration in Admin Panel

Navigate to **Admin → Gateway-uri de plată → Create/Edit Netopia**

#### Credentials
```json
{
  "signature": "XXXX-XXXX-XXXX-XXXX-XXXX",
  "public_key_path": "/var/www/carphatian.ro/html/storage/keys/netopia_public.cer",
  "private_key_path": "/var/www/carphatian.ro/html/storage/keys/netopia_private.key",
  "private_key_password": "your_password"
}
```

### Key Setup
1. Download keys from Netopia admin panel
2. Upload to `/var/www/carphatian.ro/html/storage/keys/`
3. Set permissions: `chmod 600 netopia_private.key`
4. Update paths in admin panel

### Features
- ✅ RSA encryption (public key)
- ✅ RSA decryption (private key)
- ✅ XML request/response format
- ✅ Instant Payment Notification (IPN)
- ✅ Sandbox/Live mode support

### Usage Example
```php
use Plugins\PaymentGateway\Models\PaymentGateway;
use Plugins\PaymentGateway\Services\NetopiaService;

$gateway = PaymentGateway::where('provider', 'netopia')->active()->first();
$service = new NetopiaService($gateway);

// Create payment
$result = $service->createPayment([
    'amount' => 100.00,
    'currency' => 'RON',
    'order_id' => '12345',
    'description' => 'Comanda #12345',
    'customer_email' => 'customer@example.com',
    'customer_phone' => '0723456789',
    'customer_name' => 'Ion Popescu'
]);

// This gateway requires POST form submission
// Display form with hidden fields
```

### POST Form Example
```html
<form action="{{ $result['payment_url'] }}" method="POST">
    <input type="hidden" name="env_key" value="{{ $result['form_data']['env_key'] }}">
    <input type="hidden" name="data" value="{{ $result['form_data']['data'] }}">
    <button type="submit">Plătește</button>
</form>
```

### IPN Handler
```php
// In your webhook controller
$envelope = $request->input('env_key');
$data = $request->input('data');

$result = $service->processIPN($data, $envelope);

if ($result['success'] && $result['status'] === 'completed') {
    // Payment successful
    // Update order status
}

// Respond with mobilpay tag
echo '<?xml version="1.0" encoding="utf-8"?><crc>OK</crc>';
```

---

## Bank Transfer Setup

### Configuration in Admin Panel

Navigate to **Admin → Gateway-uri de plată → Create/Edit Transfer Bancar**

#### Bank Details
```json
{
  "bank_name": "Banca Transilvania",
  "account_holder": "SC Company SRL",
  "iban": "RO49AAAA1B31007593840000",
  "swift_bic": "BTRLRO22",
  "bank_address": "Str. Exemplu Nr. 1, București"
}
```

### Features
- ✅ IBAN validation (modulo 97)
- ✅ SWIFT/BIC validation
- ✅ EPC QR code generation
- ✅ Multi-language instructions
- ✅ Formatted IBAN display

### Usage Example
```php
use Plugins\PaymentGateway\Models\PaymentGateway;
use Plugins\PaymentGateway\Services\BankTransferService;

$gateway = PaymentGateway::where('provider', 'bank_transfer')->active()->first();
$service = new BankTransferService($gateway);

// Generate instructions
$result = $service->generateInstructions([
    'amount' => 100.00,
    'currency' => 'RON',
    'order_id' => '12345'
]);

// Display to customer
echo "IBAN: " . $result['bank_details']['iban'];
echo "SWIFT: " . $result['bank_details']['swift_bic'];
echo "Amount: " . $result['amount'] . " " . $result['currency'];
echo "Reference: " . $result['payment_reference'];

// Generate QR code using $result['qr_code_data']
```

---

## Testing

### Test Mode Configuration

All gateways support test mode. Enable it in the admin panel:

1. Edit gateway
2. Enable **Test Mode**
3. Use test credentials

### Stripe Test Cards
- Success: `4242 4242 4242 4242`
- Decline: `4000 0000 0000 0002`
- Auth required: `4000 0027 6000 3184`

### PayPal Sandbox
- Use sandbox.paypal.com to create test accounts
- Personal account for buyer
- Business account for seller

### EuPlatesc Test
- Use test merchant credentials
- Test mode uses same URL with different credentials

### Netopia Sandbox
- URL: `http://sandboxsecure.mobilpay.ro`
- Use sandbox signature and test keys

---

## Webhooks

### Setup Webhook Routes

Add to `routes/web.php`:

```php
use App\Http\Controllers\WebhookController;

Route::post('/webhooks/stripe', [WebhookController::class, 'stripe']);
Route::post('/webhooks/paypal', [WebhookController::class, 'paypal']);
Route::post('/webhooks/euplatesc', [WebhookController::class, 'euplatesc']);
Route::post('/webhooks/netopia', [WebhookController::class, 'netopia']);
```

### Create Webhook Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Plugins\PaymentGateway\Models\PaymentGateway;
use Plugins\PaymentGateway\Services\{
    StripeService,
    PayPalService,
    EuPlatescService,
    NetopiaService
};

class WebhookController extends Controller
{
    public function stripe(Request $request)
    {
        $gateway = PaymentGateway::where('provider', 'stripe')->active()->first();
        $service = new StripeService($gateway);

        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$service->verifyWebhookSignature($payload, $signature)) {
            return response('Invalid signature', 403);
        }

        $result = $service->processWebhook($payload, $signature);

        // Update order based on $result
        
        return response('OK', 200);
    }

    public function paypal(Request $request)
    {
        $gateway = PaymentGateway::where('provider', 'paypal')->active()->first();
        $service = new PayPalService($gateway);

        $headers = $request->headers->all();
        $body = $request->getContent();
        $webhookId = $gateway->credentials['webhook_id'] ?? '';

        if (!$service->verifyWebhookSignature($webhookId, $headers, $body)) {
            return response('Invalid signature', 403);
        }

        $result = $service->processWebhook($request->all());

        // Update order based on $result
        
        return response('OK', 200);
    }

    public function euplatesc(Request $request)
    {
        $gateway = PaymentGateway::where('provider', 'euplatesc')->active()->first();
        $service = new EuPlatescService($gateway);

        $result = $service->processIPN($request->all());

        // Update order based on $result
        
        return response('OK', 200);
    }

    public function netopia(Request $request)
    {
        $gateway = PaymentGateway::where('provider', 'netopia')->active()->first();
        $service = new NetopiaService($gateway);

        $envelope = $request->input('data');
        $envKey = $request->input('env_key');

        $result = $service->processIPN($envelope, $envKey);

        // Update order based on $result
        
        return response('<?xml version="1.0" encoding="utf-8"?><crc>OK</crc>', 200)
            ->header('Content-Type', 'text/xml');
    }
}
```

---

## Security Best Practices

### 1. Credentials Storage
- Never commit credentials to git
- Use `.env` file for sensitive data
- Rotate keys regularly

### 2. Webhook Verification
- Always verify signatures
- Use constant-time comparison (`hash_equals`)
- Log all webhook events

### 3. HTTPS Required
- All webhooks must use HTTPS
- Use valid SSL certificates
- Test webhook URLs before going live

### 4. Error Handling
- Log all payment errors
- Monitor failed payments
- Set up alerts for critical failures

---

## Troubleshooting

### Stripe Issues
- **"Invalid API key"**: Check test/live mode matches API key
- **"No such payment_intent"**: Payment Intent expired or invalid
- **Webhook failing**: Verify endpoint URL and signature

### PayPal Issues
- **Authentication failed**: Check client ID and secret
- **Order not found**: Order may have expired (3 hours)
- **Sandbox issues**: Use sandbox credentials with sandbox URL

### EuPlatesc Issues
- **Invalid signature**: Check secret key and signature generation
- **Payment rejected**: Verify merchant ID is active

### Netopia Issues
- **Encryption failed**: Check public key file exists and is readable
- **Decryption failed**: Verify private key password
- **XML parse error**: Check XML format matches Netopia spec

### Bank Transfer
- **Invalid IBAN**: Use modulo 97 validation
- **Invalid SWIFT**: Check format (8 or 11 characters)

---

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode in `.env`: `APP_DEBUG=true`
3. Test in sandbox/test mode first
4. Review official documentation for each gateway

---

## Changelog

### v1.0.0 (2025-12-20)
- ✅ Initial release
- ✅ Stripe integration (API v2024-12-18.acacia)
- ✅ PayPal integration (REST API v2)
- ✅ EuPlatesc integration (HMAC-MD5)
- ✅ Netopia integration (RSA encryption)
- ✅ Bank Transfer with IBAN/SWIFT validation
- ✅ Test/Live mode separation
- ✅ Webhook handlers
- ✅ Production-ready code
