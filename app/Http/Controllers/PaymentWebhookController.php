<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Plugins\PaymentGateway\Models\PaymentGateway;
use Plugins\PaymentGateway\Services\{
    StripeService,
    PayPalService,
    EuPlatescService,
    NetopiaService
};

/**
 * Payment Gateway Webhook Controller
 * 
 * Handles webhook callbacks from payment providers
 */
class PaymentWebhookController extends Controller
{
    /**
     * Handle Stripe webhook
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function stripe(Request $request)
    {
        try {
            $gateway = PaymentGateway::where('provider', 'stripe')
                ->active()
                ->first();

            if (!$gateway) {
                Log::error('Stripe webhook: Gateway not found or inactive');
                return response('Gateway not configured', 503);
            }

            $service = new StripeService($gateway);

            // Get raw payload and signature
            $payload = $request->getContent();
            $signature = $request->header('Stripe-Signature');

            // Verify signature
            if (!$service->verifyWebhookSignature($payload, $signature)) {
                Log::warning('Stripe webhook: Invalid signature');
                return response('Invalid signature', 403);
            }

            // Process webhook
            $result = $service->processWebhook($payload, $signature);

            if (!$result['success']) {
                Log::error('Stripe webhook processing failed', $result);
                return response('Processing failed', 500);
            }

            // Handle different event types
            switch ($result['event_type']) {
                case 'checkout.session.completed':
                    $this->handleStripeCheckoutCompleted($result['session']);
                    break;
                
                case 'payment_intent.succeeded':
                    $this->handleStripePaymentSucceeded($result['payment_intent']);
                    break;
                
                case 'payment_intent.payment_failed':
                    $this->handleStripePaymentFailed($result['payment_intent']);
                    break;
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Stripe webhook exception: ' . $e->getMessage());
            return response('Server error', 500);
        }
    }

    /**
     * Handle PayPal webhook
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function paypal(Request $request)
    {
        try {
            $gateway = PaymentGateway::where('provider', 'paypal')
                ->active()
                ->first();

            if (!$gateway) {
                Log::error('PayPal webhook: Gateway not found or inactive');
                return response('Gateway not configured', 503);
            }

            $service = new PayPalService($gateway);

            // Get webhook ID from credentials
            $webhookId = $gateway->credentials['webhook_id'] ?? '';
            
            if (empty($webhookId)) {
                Log::error('PayPal webhook: Webhook ID not configured');
                return response('Webhook not configured', 503);
            }

            // Verify signature
            $headers = $request->headers->all();
            $body = $request->getContent();

            if (!$service->verifyWebhookSignature($webhookId, $headers, $body)) {
                Log::warning('PayPal webhook: Invalid signature');
                return response('Invalid signature', 403);
            }

            // Process webhook
            $event = $request->all();
            $result = $service->processWebhook($event);

            if (!$result['success']) {
                Log::error('PayPal webhook processing failed', $result);
                return response('Processing failed', 500);
            }

            // Handle different event types
            switch ($result['action']) {
                case 'order_approved':
                    $this->handlePayPalOrderApproved($result);
                    break;
                
                case 'payment_completed':
                    $this->handlePayPalPaymentCompleted($result);
                    break;
                
                case 'payment_failed':
                    $this->handlePayPalPaymentFailed($result);
                    break;
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('PayPal webhook exception: ' . $e->getMessage());
            return response('Server error', 500);
        }
    }

    /**
     * Handle EuPlatesc IPN
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function euplatesc(Request $request)
    {
        try {
            $gateway = PaymentGateway::where('provider', 'euplatesc')
                ->active()
                ->first();

            if (!$gateway) {
                Log::error('EuPlatesc IPN: Gateway not found or inactive');
                return response('Gateway not configured', 503);
            }

            $service = new EuPlatescService($gateway);

            // Process IPN
            $result = $service->processIPN($request->all());

            if (!$result['success']) {
                Log::error('EuPlatesc IPN processing failed', $result);
                return response('Processing failed', 500);
            }

            // Handle payment status
            if ($result['status'] === 'approved') {
                $this->handleEuPlatescApproved($result);
            } else {
                $this->handleEuPlatescDeclined($result);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('EuPlatesc IPN exception: ' . $e->getMessage());
            return response('Server error', 500);
        }
    }

    /**
     * Handle Netopia IPN
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function netopia(Request $request)
    {
        try {
            $gateway = PaymentGateway::where('provider', 'netopia')
                ->active()
                ->first();

            if (!$gateway) {
                Log::error('Netopia IPN: Gateway not found or inactive');
                return response('<?xml version="1.0" encoding="utf-8"?><crc>ERROR - Gateway not configured</crc>', 503)
                    ->header('Content-Type', 'text/xml');
            }

            $service = new NetopiaService($gateway);

            // Get encrypted data
            $envelope = $request->input('data');
            $envKey = $request->input('env_key');

            // Process IPN
            $result = $service->processIPN($envelope, $envKey);

            if (!$result['success']) {
                Log::error('Netopia IPN processing failed', $result);
                return response('<?xml version="1.0" encoding="utf-8"?><crc>ERROR - Processing failed</crc>', 500)
                    ->header('Content-Type', 'text/xml');
            }

            // Handle payment status
            switch ($result['status']) {
                case 'completed':
                    $this->handleNetopiaCompleted($result);
                    break;
                
                case 'pending':
                    $this->handleNetopiaPending($result);
                    break;
                
                case 'canceled':
                    $this->handleNetopiaCanceled($result);
                    break;
                
                case 'refunded':
                    $this->handleNetopiaRefunded($result);
                    break;
            }

            // Netopia requires XML response
            return response('<?xml version="1.0" encoding="utf-8"?><crc>OK</crc>', 200)
                ->header('Content-Type', 'text/xml');

        } catch (\Exception $e) {
            Log::error('Netopia IPN exception: ' . $e->getMessage());
            return response('<?xml version="1.0" encoding="utf-8"?><crc>ERROR</crc>', 500)
                ->header('Content-Type', 'text/xml');
        }
    }

    /**
     * Handle Stripe checkout completed
     */
    protected function handleStripeCheckoutCompleted(array $session)
    {
        $orderId = $session['metadata']['order_id'] ?? null;
        
        if (!$orderId) {
            Log::warning('Stripe checkout completed: No order ID in metadata');
            return;
        }

        Log::info('Stripe payment completed', [
            'order_id' => $orderId,
            'session_id' => $session['id'],
            'amount' => $session['amount_total'] / 100,
        ]);

        // TODO: Update order status to paid
        // $order = Order::find($orderId);
        // $order->status = 'paid';
        // $order->transaction_id = $session['id'];
        // $order->save();
    }

    /**
     * Handle Stripe payment succeeded
     */
    protected function handleStripePaymentSucceeded(array $paymentIntent)
    {
        Log::info('Stripe payment intent succeeded', [
            'payment_intent_id' => $paymentIntent['id'],
            'amount' => $paymentIntent['amount'] / 100,
        ]);

        // TODO: Update order based on metadata
    }

    /**
     * Handle Stripe payment failed
     */
    protected function handleStripePaymentFailed(array $paymentIntent)
    {
        Log::warning('Stripe payment intent failed', [
            'payment_intent_id' => $paymentIntent['id'],
            'error' => $paymentIntent['last_payment_error']['message'] ?? 'Unknown error',
        ]);

        // TODO: Update order status to failed
    }

    /**
     * Handle PayPal order approved
     */
    protected function handlePayPalOrderApproved(array $result)
    {
        Log::info('PayPal order approved', [
            'order_id' => $result['order_id'],
        ]);

        // TODO: Capture payment or wait for completion event
    }

    /**
     * Handle PayPal payment completed
     */
    protected function handlePayPalPaymentCompleted(array $result)
    {
        Log::info('PayPal payment completed', [
            'order_id' => $result['order_id'],
            'amount' => $result['amount'],
        ]);

        // TODO: Update order status to paid
    }

    /**
     * Handle PayPal payment failed
     */
    protected function handlePayPalPaymentFailed(array $result)
    {
        Log::warning('PayPal payment failed', [
            'order_id' => $result['order_id'],
            'reason' => $result['reason'],
        ]);

        // TODO: Update order status to failed
    }

    /**
     * Handle EuPlatesc approved
     */
    protected function handleEuPlatescApproved(array $result)
    {
        Log::info('EuPlatesc payment approved', [
            'order_id' => $result['order_id'],
            'transaction_id' => $result['transaction_id'],
            'amount' => $result['amount'],
        ]);

        // TODO: Update order status to paid
    }

    /**
     * Handle EuPlatesc declined
     */
    protected function handleEuPlatescDeclined(array $result)
    {
        Log::warning('EuPlatesc payment declined', [
            'order_id' => $result['order_id'],
            'message' => $result['message'],
        ]);

        // TODO: Update order status to failed
    }

    /**
     * Handle Netopia completed
     */
    protected function handleNetopiaCompleted(array $result)
    {
        Log::info('Netopia payment completed', [
            'order_id' => $result['order_id'],
            'action' => $result['action'],
            'amount' => $result['amount'],
        ]);

        // TODO: Update order status to paid
    }

    /**
     * Handle Netopia pending
     */
    protected function handleNetopiaPending(array $result)
    {
        Log::info('Netopia payment pending', [
            'order_id' => $result['order_id'],
            'action' => $result['action'],
        ]);

        // TODO: Update order status to pending
    }

    /**
     * Handle Netopia canceled
     */
    protected function handleNetopiaCanceled(array $result)
    {
        Log::warning('Netopia payment canceled', [
            'order_id' => $result['order_id'],
        ]);

        // TODO: Update order status to canceled
    }

    /**
     * Handle Netopia refunded
     */
    protected function handleNetopiaRefunded(array $result)
    {
        Log::info('Netopia payment refunded', [
            'order_id' => $result['order_id'],
            'amount' => $result['amount'],
        ]);

        // TODO: Update order status to refunded
    }
}
