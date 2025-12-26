<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Plan;
use Illuminate\Http\Request;

class CheckPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(\Auth::check())
        {
            $user = \Auth::user();

            // Check plan when owner login
            $user_plan = Plan::find($user->plan);
            if(!empty($user_plan) && $user_plan->duration !== 'Lifetime'){
                if($user->type == 'company' && (empty($user->plan_expire_date) || $user->plan_expire_date < date('Y-m-d')))
                {
                    $error = $user->is_trial_done ? __('Your Plan is expired.') : ($user->plan_expire_date < date('Y-m-d') ? __('Please upgrade your plan') : '');
                    if($request->ajax()){
                        return response()->json(['flag'=>'0','msg'=>$error]);
                    }
                    return redirect()->route('plans.index')->with('error', $error);
                }
            }

            // Check premium features access
            $this->checkPremiumFeatures($request, $user, $user_plan);
        }

        return $next($request);
    }

    /**
     * Check if user has access to premium features based on their plan
     */
    private function checkPremiumFeatures(Request $request, $user, $plan)
    {
        if (!$plan) {
            return;
        }

        // Define premium feature routes and their corresponding plan settings
        $premiumFeatures = [
            'business.domain-setting' => 'enable_custdomain',
            'wallet.export' => 'enable_wallet_export',
            'business.qr-code' => 'enable_qr_code',
            'business.chatgpt' => 'enable_chatgpt',
            'generate_ai_business' => 'enable_chatgpt',
            'generate_ai' => 'enable_chatgpt',
            'generate_ai_testimonial' => 'enable_chatgpt',
            'business.pwa' => 'pwa_business',
            'business.branding' => 'enable_branding',
        ];

        $currentRoute = $request->route()->getName();
        
        foreach ($premiumFeatures as $route => $planSetting) {
            if ($currentRoute === $route && $plan->$planSetting !== 'on') {
                if ($request->ajax()) {
                    return response()->json([
                        'flag' => '0',
                        'msg' => __('This feature is not available in your current plan. Please upgrade to access premium features.')
                    ]);
                }
                
                return redirect()->back()->with('error', __('This feature is not available in your current plan. Please upgrade to access premium features.'));
            }
        }

        // Check business limits
        if ($plan->business !== -1) {
            $businessCount = \App\Models\Business::where('created_by', $user->creatorId())->count();
            if ($businessCount >= $plan->business) {
                if ($request->route()->getName() === 'business.create') {
                    if ($request->ajax()) {
                        return response()->json([
                            'flag' => '0',
                            'msg' => __('You have reached the maximum number of businesses allowed in your plan. Please upgrade to create more businesses.')
                        ]);
                    }
                    
                    return redirect()->back()->with('error', __('You have reached the maximum number of businesses allowed in your plan. Please upgrade to create more businesses.'));
                }
            }
        }

        // Check user limits
        if ($plan->max_users !== -1) {
            $userCount = \App\Models\User::where('created_by', $user->creatorId())->count();
            if ($userCount >= $plan->max_users) {
                if ($request->route()->getName() === 'users.create') {
                    if ($request->ajax()) {
                        return response()->json([
                            'flag' => '0',
                            'msg' => __('You have reached the maximum number of users allowed in your plan. Please upgrade to add more users.')
                        ]);
                    }
                    
                    return redirect()->back()->with('error', __('You have reached the maximum number of users allowed in your plan. Please upgrade to add more users.'));
                }
            }
        }
    }
}
