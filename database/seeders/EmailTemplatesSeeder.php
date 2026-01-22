<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Email Verification',
                'slug' => 'email-verification',
                'subject' => 'Verify Your Email Address - {{app_name}}',
                'body_html' => $this->getVerificationTemplate(),
                'body_text' => 'Please verify your email by clicking: {{verification_url}}',
                'variables' => ['user_name', 'verification_url', 'app_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome',
                'subject' => 'Welcome to {{app_name}}!',
                'body_html' => $this->getWelcomeTemplate(),
                'body_text' => 'Welcome {{user_name}}! Thank you for registering.',
                'variables' => ['user_name', 'app_name', 'login_url'],
                'is_active' => true,
            ],
            [
                'name' => 'Password Reset',
                'slug' => 'password-reset',
                'subject' => 'Reset Your Password - {{app_name}}',
                'body_html' => $this->getPasswordResetTemplate(),
                'body_text' => 'Reset your password: {{reset_url}}',
                'variables' => ['user_name', 'reset_url', 'app_name', 'expire_minutes'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Confirmation',
                'slug' => 'order-confirmation',
                'subject' => 'Order Confirmed #{{order_number}} - {{app_name}}',
                'body_html' => $this->getOrderConfirmationTemplate(),
                'body_text' => 'Your order #{{order_number}} has been confirmed.',
                'variables' => ['user_name', 'order_number', 'order_total', 'order_items', 'app_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Shipped',
                'slug' => 'order-shipped',
                'subject' => 'Your Order Has Shipped #{{order_number}}',
                'body_html' => $this->getOrderShippedTemplate(),
                'body_text' => 'Your order #{{order_number}} has been shipped.',
                'variables' => ['user_name', 'order_number', 'tracking_number', 'carrier', 'app_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Contact Form Notification',
                'slug' => 'contact-form',
                'subject' => 'New Contact Message from {{sender_name}}',
                'body_html' => $this->getContactFormTemplate(),
                'body_text' => 'New message from {{sender_name}} ({{sender_email}}): {{message}}',
                'variables' => ['sender_name', 'sender_email', 'sender_phone', 'subject', 'message'],
                'is_active' => true,
            ],
            [
                'name' => 'Support Chat Notification',
                'slug' => 'support-chat',
                'subject' => 'New Support Chat from {{participant_name}}',
                'body_html' => $this->getSupportChatTemplate(),
                'body_text' => 'New support chat started by {{participant_name}}.',
                'variables' => ['participant_name', 'participant_email', 'chat_url', 'message'],
                'is_active' => true,
            ],
            [
                'name' => 'New Chat Message',
                'slug' => 'chat-message',
                'subject' => 'New Message in Support Chat',
                'body_html' => $this->getChatMessageTemplate(),
                'body_text' => 'You have a new message from {{sender_name}}.',
                'variables' => ['sender_name', 'message', 'chat_url'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }

    private function getBaseTemplate(string $content): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%); padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .btn { display: inline-block; background: #6b46c1; color: #fff !important; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .highlight { background: #f3e8ff; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{app_name}}</h1>
        </div>
        <div class="content">' . $content . '</div>
        <div class="footer">
            <p>&copy; ' . date('Y') . ' {{app_name}}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

    private function getVerificationTemplate(): string
    {
        return $this->getBaseTemplate('
            <h2>Hello {{user_name}},</h2>
            <p>Thank you for registering! Please verify your email address by clicking the button below:</p>
            <p style="text-align: center;">
                <a href="{{verification_url}}" class="btn">Verify Email Address</a>
            </p>
            <p>If you did not create an account, no further action is required.</p>
            <p>If you are having trouble clicking the button, copy and paste this URL into your browser:</p>
            <p style="word-break: break-all; color: #6b46c1;">{{verification_url}}</p>
        ');
    }

    private function getWelcomeTemplate(): string
    {
        return $this->getBaseTemplate('
            <h2>Welcome, {{user_name}}!</h2>
            <p>Thank you for joining {{app_name}}. We are excited to have you as part of our community.</p>
            <p>You can now:</p>
            <ul>
                <li>Browse our products and services</li>
                <li>Make purchases and track orders</li>
                <li>Get customer support</li>
            </ul>
            <p style="text-align: center;">
                <a href="{{login_url}}" class="btn">Go to Your Dashboard</a>
            </p>
        ');
    }

    private function getPasswordResetTemplate(): string
    {
        return $this->getBaseTemplate('
            <h2>Hello {{user_name}},</h2>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            <p style="text-align: center;">
                <a href="{{reset_url}}" class="btn">Reset Password</a>
            </p>
            <p>This password reset link will expire in {{expire_minutes}} minutes.</p>
            <p>If you did not request a password reset, no further action is required.</p>
        ');
    }

    private function getOrderConfirmationTemplate(): string
    {
        return $this->getBaseTemplate('
            <h2>Thank you for your order, {{user_name}}!</h2>
            <div class="highlight">
                <strong>Order Number:</strong> #{{order_number}}<br>
                <strong>Total:</strong> {{order_total}}
            </div>
            <h3>Order Details:</h3>
            {{order_items}}
            <p>We will notify you when your order ships.</p>
        ');
    }

    private function getOrderShippedTemplate(): string
    {
        return $this->getBaseTemplate('
            <h2>Your order is on its way!</h2>
            <p>Hello {{user_name}},</p>
            <p>Great news! Your order #{{order_number}} has been shipped.</p>
            <div class="highlight">
                <strong>Carrier:</strong> {{carrier}}<br>
                <strong>Tracking Number:</strong> {{tracking_number}}
            </div>
        ');
    }

    private function getContactFormTemplate(): string
    {
        return $this->getBaseTemplate('
            <h2>New Contact Form Message</h2>
            <div class="highlight">
                <strong>From:</strong> {{sender_name}}<br>
                <strong>Email:</strong> {{sender_email}}<br>
                <strong>Phone:</strong> {{sender_phone}}<br>
                <strong>Subject:</strong> {{subject}}
            </div>
            <h3>Message:</h3>
            <p>{{message}}</p>
        ');
    }

    private function getSupportChatTemplate(): string
    {
        return $this->getBaseTemplate('
            <h2>New Support Chat Started</h2>
            <div class="highlight">
                <strong>From:</strong> {{participant_name}}<br>
                <strong>Email:</strong> {{participant_email}}
            </div>
            <h3>Initial Message:</h3>
            <p>{{message}}</p>
            <p style="text-align: center;">
                <a href="{{chat_url}}" class="btn">View Chat</a>
            </p>
        ');
    }

    private function getChatMessageTemplate(): string
    {
        return $this->getBaseTemplate('
            <h2>New Support Message</h2>
            <div class="highlight">
                <strong>From:</strong> {{sender_name}}
            </div>
            <h3>Message:</h3>
            <p>{{message}}</p>
            <p style="text-align: center;">
                <a href="{{chat_url}}" class="btn">Reply to Message</a>
            </p>
        ');
    }
}
