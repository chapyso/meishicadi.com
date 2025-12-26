<?php

namespace App\Mail;

use App\Models\Utility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionExtensionUser extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $extensionPeriod;
    public $newExpiryDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $extensionPeriod, $newExpiryDate)
    {
        $this->user = $user;
        $this->extensionPeriod = $extensionPeriod;
        $this->newExpiryDate = $newExpiryDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.subscription_extension_user')
                    ->subject('Subscription Extended - ' . env('APP_NAME'))
                    ->with([
                        'user' => $this->user,
                        'extensionPeriod' => $this->extensionPeriod,
                        'newExpiryDate' => $this->newExpiryDate
                    ]);
    }
} 