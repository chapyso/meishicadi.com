<?php

namespace App\Mail;

use App\Models\Business;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $business;
    public $activationUrl;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Business $business, string $activationUrl)
    {
        $this->business = $business;
        $this->activationUrl = $activationUrl;
        $this->user = $business->user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Activate Your Meishicadi Business Card - ' . $this->business->title;
        
        return $this->subject($subject)
                    ->view('emails.business-activation')
                    ->with([
                        'business' => $this->business,
                        'activationUrl' => $this->activationUrl,
                        'user' => $this->user,
                    ]);
    }
} 