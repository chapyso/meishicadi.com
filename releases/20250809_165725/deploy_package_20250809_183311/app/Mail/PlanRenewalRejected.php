<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Plan;

class PlanRenewalRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Plan $plan, $settings)
    {
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
                    ->markdown('email.plan_renewal_rejected')
                    ->subject('Plan Renewal Request Update - ' . $this->plan->name)
                    ->with([
                        'user' => $this->user,
                        'plan' => $this->plan,
                        'mail_header' => $this->settings['company_name'],
                    ]);
    }
} 