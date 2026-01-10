<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignupRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $signupData;

    /**
     * Create a new message instance.
     */
    public function __construct($signupData)
    {
        $this->signupData = $signupData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from('no-reply@meishicadi.com', 'MeishiCadi Signup')
                    ->subject('New Digital Business Card Signup Request')
                    ->view('emails.signup-request-simple')
                    ->with($this->signupData);
    }
}
