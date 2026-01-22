<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MarketingCarphatian extends Mailable
{
    use Queueable, SerializesModels;

    public string $contactName;
    public string $companyName;
    public string $unsubscribeUrl;
    public string $emailSubject;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $contactName = '',
        string $companyName = '',
        string $unsubscribeUrl = '#',
        string $emailSubject = 'Soluții Web Profesionale pentru Afacerea Ta 🚀'
    ) {
        $this->contactName = $contactName;
        $this->companyName = $companyName;
        $this->unsubscribeUrl = $unsubscribeUrl;
        $this->emailSubject = $emailSubject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.marketing.template-carphatian',
            with: [
                'contact_name' => $this->contactName,
                'company_name' => $this->companyName,
                'unsubscribe_url' => $this->unsubscribeUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
