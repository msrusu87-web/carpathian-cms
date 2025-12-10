<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function send(string $type, $user, string $subject, string $content): bool
    {
        try {
            if ($type === 'email') {
                Mail::raw($content, function ($message) use ($user, $subject) {
                    $message->to($user->email)->subject($subject);
                });
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
