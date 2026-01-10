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

            // Skip plan check for super admin
            if($user->type == 'super admin') {
                return $next($request);
            }

            // Check plan for all other users
            $user_plan = Plan::find($user->plan);
            if(!empty($user_plan) && $user_plan->duration !== 'Lifetime'){
                // Check if plan is expired for any user type (not just company)
                if(empty($user->plan_expire_date) || $user->plan_expire_date < date('Y-m-d'))
                {
                    $error = $user->is_trial_done ? __('Your Plan is expired.') : ($user->plan_expire_date < date('Y-m-d') ? __('Please upgrade your plan') : '');
                    if($request->ajax()){
                        return response()->json(['flag'=>'0','msg'=>$error]);
                    }
                    return redirect()->route('plan.expired')->with('error', $error);
                }
            }
        }

        return $next($request);
    }
}
