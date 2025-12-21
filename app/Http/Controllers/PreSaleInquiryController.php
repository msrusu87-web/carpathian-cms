<?php

namespace App\Http\Controllers;

use App\Models\PreSaleInquiry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PreSaleInquiryController extends Controller
{
    public function showForm($productId)
    {
        $product = Product::findOrFail($productId);
        return view('shop.pre-sale-inquiry', compact('product'));
    }

    public function submit(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:10|max:2000',
            'create_account' => 'nullable|boolean',
            'password' => 'nullable|required_if:create_account,1|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        
        // Create account if requested and not authenticated
        if (!$user && $request->create_account) {
            try {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                ]);
                
                // Auto-assign Customer role
                if (!$user->hasRole('Customer')) {
                    $user->assignRole('Customer');
                }
                
                auth()->login($user);
                Log::info('New user created via pre-sale inquiry', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                return back()->with('error', 'Email already exists. Please login or use a different email.');
            }
        }

        // Create inquiry
        $inquiry = PreSaleInquiry::create([
            'user_id' => $user ? $user->id : null,
            'product_id' => $product->id,
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'] ?? null,
            'inquiry_message' => $validated['message'],
            'status' => 'pending',
        ]);

        // Send notification email to admin
        try {
            $adminEmail = config('mail.from.address');
            Mail::raw(
                "New Pre-Sale Inquiry\n\n" .
                "Product: {$product->name}\n" .
                "Customer: {$validated['name']}\n" .
                "Email: {$validated['email']}\n" .
                "Phone: " . ($validated['phone'] ?? 'Not provided') . "\n\n" .
                "Message:\n{$validated['message']}\n\n" .
                "View in admin panel: " . url('/admin/pre-sale-inquiries'),
                function ($message) use ($adminEmail, $inquiry) {
                    $message->to($adminEmail)
                            ->subject("New Pre-Sale Inquiry #$inquiry->id");
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to send pre-sale inquiry notification', [
                'inquiry_id' => $inquiry->id,
                'error' => $e->getMessage()
            ]);
        }

        // Send confirmation to customer
        try {
            Mail::raw(
                "Thank you for your inquiry!\n\n" .
                "We have received your question about: {$product->name}\n\n" .
                "Our team will review your inquiry and respond within 24-48 hours.\n\n" .
                "Your inquiry ID: #{$inquiry->id}\n\n" .
                "Best regards,\n" .
                config('app.name'),
                function ($message) use ($validated) {
                    $message->to($validated['email'])
                            ->subject('Pre-Sale Inquiry Received');
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to send inquiry confirmation to customer', [
                'inquiry_id' => $inquiry->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('pre-sale.thank-you', $inquiry->id)
                        ->with('success', 'Your inquiry has been submitted successfully!');
    }

    public function thankYou($inquiryId)
    {
        $inquiry = PreSaleInquiry::with('product')->findOrFail($inquiryId);
        return view('shop.pre-sale-thank-you', compact('inquiry'));
    }
}
