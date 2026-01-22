<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailSetting;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class EmailService
{
    public static function configure(): void
    {
        $settings = EmailSetting::getSettings();
        if (!$settings) {
            return;
        }

        Config::set('mail.default', $settings->mail_driver ?? 'smtp');
        Config::set('mail.mailers.smtp.host', $settings->mail_host);
        Config::set('mail.mailers.smtp.port', $settings->mail_port);
        Config::set('mail.mailers.smtp.username', $settings->mail_username);
        Config::set('mail.mailers.smtp.password', $settings->mail_password);
        Config::set('mail.mailers.smtp.encryption', $settings->mail_encryption);
        Config::set('mail.from.address', $settings->mail_from_address);
        Config::set('mail.from.name', $settings->mail_from_name);
    }

    public static function send(string $templateSlug, string $toEmail, array $data = [], ?string $toName = null): bool
    {
        $template = EmailTemplate::findBySlug($templateSlug);
        if (!$template) {
            EmailLog::log($toEmail, "Template not found: $templateSlug", 'failed', $templateSlug, 'Template not found');
            return false;
        }

        $rendered = $template->render($data);

        try {
            self::configure();
            
            Mail::send([], [], function ($message) use ($toEmail, $toName, $rendered) {
                $message->to($toEmail, $toName)
                    ->subject($rendered['subject'])
                    ->html($rendered['body']);
            });

            EmailLog::log($toEmail, $rendered['subject'], 'sent', $templateSlug);
            return true;
        } catch (\Exception $e) {
            EmailLog::log($toEmail, $rendered['subject'], 'failed', $templateSlug, $e->getMessage());
            return false;
        }
    }

    public static function sendRaw(string $toEmail, string $subject, string $body, ?string $toName = null): bool
    {
        try {
            self::configure();
            
            Mail::send([], [], function ($message) use ($toEmail, $toName, $subject, $body) {
                $message->to($toEmail, $toName)
                    ->subject($subject)
                    ->html($body);
            });

            EmailLog::log($toEmail, $subject, 'sent');
            return true;
        } catch (\Exception $e) {
            EmailLog::log($toEmail, $subject, 'failed', null, $e->getMessage());
            return false;
        }
    }

    public static function notifyAdmin(string $subject, string $body, string $notificationType = 'general'): bool
    {
        $settings = EmailSetting::getSettings();
        if (!$settings || !$settings->admin_notification_email) {
            return false;
        }

        if (!$settings->shouldNotify($notificationType)) {
            return false;
        }

        return self::sendRaw($settings->admin_notification_email, $subject, $body);
    }
}
