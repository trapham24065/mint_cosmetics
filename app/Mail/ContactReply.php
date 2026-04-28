<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public string $replyMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Phản hồi từ ' . setting('site_name', 'Mint Cosmetics') . ' - Liên hệ #' . $this->contactMessage->id,
            replyTo: [setting('contact_email', 'noreply@example.com')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_reply',
            with: [
                'contactMessage' => $this->contactMessage,
                'replyMessage' => $this->replyMessage,
                'siteName' => setting('site_name', 'Mint Cosmetics'),
            ],
        );
    }
}
