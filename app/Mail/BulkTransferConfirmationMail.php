<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BulkTransferConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $senderName;
    public $senderEmail;
    public $recipientEmail;
    public $files;
    public $fileCount;
    public $totalSize;
    public $transferDate;
    public $expirationHours;
    public $expirationDate;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->senderName = $data['senderName'];
        $this->senderEmail = $data['senderEmail'];
        $this->recipientEmail = $data['recipientEmail'];
        $this->files = $data['files'];
        $this->fileCount = $data['fileCount'];
        $this->totalSize = $data['totalSize'];
        $this->transferDate = $data['transferDate'];
        $this->expirationHours = $data['expirationHours'];
        $this->expirationDate = $data['expirationDate'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'admin@meishicadi.com',
            subject: 'Transfer Confirmation - Meishicadi',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bulk-transfer.confirmation',
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
