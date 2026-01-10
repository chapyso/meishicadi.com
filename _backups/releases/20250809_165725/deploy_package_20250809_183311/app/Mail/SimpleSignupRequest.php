<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleSignupRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $phone;
    public $company;
    public $cards_required;
    public $industry;
    public $message;
    public $submitted_at;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->company = $data['company'] ?? '';
        $this->cards_required = $data['cards_required'] ?? '';
        $this->industry = $data['industry'] ?? '';
        $this->message = $data['message'] ?? '';
        $this->submitted_at = $data['submitted_at'] ?? '';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from('no-reply@meishicadi.com', 'MeishiCadi Signup')
                    ->subject('New Digital Business Card Signup Request')
                    ->view('emails.signup-request-simple');
    }
}
