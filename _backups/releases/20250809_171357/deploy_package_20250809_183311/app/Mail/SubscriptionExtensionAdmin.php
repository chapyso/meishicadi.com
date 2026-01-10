<?php

namespace App\Mail;

use App\Models\Utility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionExtensionAdmin extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $admin;
    public $extensionPeriod;
    public $newExpiryDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $admin, $extensionPeriod, $newExpiryDate)
    {
        $this->user = $user;
        $this->admin = $admin;
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
        return $this->markdown('email.subscription_extension_admin')
                    ->subject('Subscription Extension - ' . env('APP_NAME'))
                    ->with([
                        'user' => $this->user,
                        'admin' => $this->admin,
                        'extensionPeriod' => $this->extensionPeriod,
                        'newExpiryDate' => $this->newExpiryDate
                    ]);
    }
} 