<?php

namespace App\Mail;

use App\Models\MinatInvestor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMinatInvestorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $minatInvestor;

    /**
     * Create a new message instance.
     */
    public function __construct(MinatInvestor $minatInvestor)
    {
        $this->minatInvestor = $minatInvestor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Minat Investasi Baru: ' . $this->minatInvestor->nama,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_minat_investor',
            with: [
                'minatInvestor' => $this->minatInvestor,
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
