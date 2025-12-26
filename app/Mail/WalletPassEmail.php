<?php

namespace App\Mail;

use App\Models\Business;
use App\Models\WalletPass;
use App\Services\GoogleWalletService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletPassEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $business;
    public $walletPass;
    public $walletType;
    public $downloadUrl;
    public $googleWalletService;

    /**
     * Create a new message instance.
     */
    public function __construct(Business $business, WalletPass $walletPass, string $walletType, string $downloadUrl, GoogleWalletService $googleWalletService = null)
    {
        $this->business = $business;
        $this->walletPass = $walletPass;
        $this->walletType = $walletType;
        $this->downloadUrl = $downloadUrl;
        $this->googleWalletService = $googleWalletService;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Your Meishicadi Wallet Pass is Ready!';
        
        return $this->subject($subject)
                    ->view('emails.wallet-pass')
                    ->with([
                        'business' => $this->business,
                        'walletPass' => $this->walletPass,
                        'walletType' => $this->walletType,
                        'downloadUrl' => $this->downloadUrl,
                        'googleWalletService' => $this->googleWalletService,
                    ]);
    }
} 