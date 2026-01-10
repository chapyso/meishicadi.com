<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\plan_request;
use App\Models\User;

class ExpiredPlanRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $planRequest;
    public $user;
    public $plan;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(plan_request $planRequest, User $user, $plan, $settings)
    {
        $this->planRequest = $planRequest;
        $this->user = $user;
        $this->plan = $plan;
        $this->settings = $settings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = !empty($this->settings['company_name']) ? $this->settings['company_name'] : config('app.name');
        
        return $this->from($this->settings['mail_from_address'], $from)
                    ->markdown('email.expired_plan_request_notification')
                    ->subject('New Expired Plan Renewal Request - ' . $this->user->name)
                    ->with([
                        'planRequest' => $this->planRequest,
                        'user' => $this->user,
                        'plan' => $this->plan,
                        'mail_header' => $this->settings['company_name'],
                    ]);
    }
} 