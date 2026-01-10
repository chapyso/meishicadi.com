<?php

namespace App\Http\Controllers;

use App\Models\plan_request;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->type == 'super admin')
        {
            try {
                $plan_requests = plan_request::with(['user', 'plan'])->get();
                return view('plan_request.index', compact('plan_requests'));
            } catch (\Exception $e) {
                \Log::error('Error loading plan requests: ' . $e->getMessage());
                return redirect()->back()->with('error', __('Error loading plan requests. Please try again.'));
            }
        }
        else
        {
            return redirect()->back()->with('error',__('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\plan_request  $plan_request
     * @return \Illuminate\Http\Response
     */
    public function show(plan_request $plan_request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\plan_request  $plan_request
     * @return \Illuminate\Http\Response
     */
    public function edit(plan_request $plan_request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\plan_request  $plan_request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, plan_request $plan_request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\plan_request  $plan_request
     * @return \Illuminate\Http\Response
     */
    public function destroy(plan_request $plan_request)
    {
        //
    }

    public function requestView($plan_id)
    {
        if(Auth::user()->type != 'super admin')
        {
            $planID = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);
            $plan   = Plan::find($planID);
               
            if(!empty($plan))
            {
                return view('plan_request.show', compact('plan'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function userRequest($plan_id)
    {
       
        $objUser =Auth::user() ;
       
        if(Auth::user()->type == 'company')
        {
            $planID = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);

            if(!empty($planID))
            {
                plan_request::create([
                                         'user_id' => $objUser->id,
                                         'plan_id' => $planID,

                                     ]);

                // Update User Table
                //$objUser->update(['requested_plan' => $planID]);
                $objUser['requested_plan'] = $planID;
                $objUser->update();


                return redirect()->back()->with('success', __('Request Send Successfully.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('You already send request to another plan.'));
        }
    }

    public function acceptRequest($id, $response)
    {
        if(Auth::user()->type == 'super admin')
        {
            $plan_request = plan_request::find($id);
            if(!empty($plan_request))
            {
                $user = User::find($plan_request->user_id);
                $plan = Plan::find($plan_request->plan_id);
                
                // Check if user and plan exist
                if(!$user || !$plan) {
                    return redirect()->back()->with('error', __('Invalid plan request: User or Plan not found.'));
                }

                if($response == 1)
                {
                    // Update plan request status
                    $plan_request->status = 'approved';
                    $plan_request->save();

                    $user->requested_plan = $plan_request->plan_id;
                    $user->plan           = $plan_request->plan_id;
                    $user->save();

                    $assignPlan = $user->assignPlan($plan_request->plan_id, $plan_request->duration);

                    if($assignPlan['is_success'] == true && !empty($plan))
                    {
                        // Cancel existing subscription if any
                        if(!empty($user->payment_subscription_id) && $user->payment_subscription_id != '')
                        {
                            try
                            {
                                $user->cancel_subscription($user->id);
                            }
                            catch(\Exception $exception)
                            {
                                \Log::debug($exception->getMessage());
                            }
                        }

                        // Create order record
                        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                        PlanOrder::create([
                            'order_id' => $orderID,
                            'name' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $plan->price,
                            'price_currency' => !empty(env('CURRENCY_CODE')) ? env('CURRENCY_CODE') : 'usd',
                            'txn_id' => '',
                            'payment_type' => __('Expired Plan Renewal'),
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $user->id,
                        ]);

                        // Send email notification to user
                        try {
                            $settings = Utility::settings();
                            \Mail::to($user->email)->send(new \App\Mail\PlanRenewalApproved($user, $plan, $settings));
                        } catch (\Exception $e) {
                            \Log::error('Failed to send plan renewal approval email: ' . $e->getMessage());
                        }

                        return redirect()->back()->with('success', __('Plan renewal request approved successfully. User has been notified via email.'));
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Plan failed to upgrade.'));
                    }
                }
                else
                {
                    // Update plan request status
                    $plan_request->status = 'rejected';
                    $plan_request->save();

                    $user['requested_plan'] = '0';
                    $user->update();

                    // Send email notification to user
                    try {
                        $settings = Utility::settings();
                        \Mail::to($user->email)->send(new \App\Mail\PlanRenewalRejected($user, $plan, $settings));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send plan renewal rejection email: ' . $e->getMessage());
                    }

                    return redirect()->back()->with('success', __('Plan renewal request rejected. User has been notified via email.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function cancelRequest($id)
    {

        $user = User::find($id);
        $user['requested_plan'] = '0';
        $user->update();
        plan_request::where('user_id', $id)->delete();

        return redirect()->back()->with('success', __('Request Canceled Successfully.'));
    }

    /**
     * Clean up orphaned plan requests (requests with non-existent users or plans)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cleanupOrphanedRequests()
    {
        if(Auth::user()->type == 'super admin')
        {
            // Find plan requests where user doesn't exist
            $orphanedByUser = plan_request::whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                      ->from('users')
                      ->whereRaw('users.id = plan_requests.user_id');
            });

            // Find plan requests where plan doesn't exist
            $orphanedByPlan = plan_request::whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                      ->from('plans')
                      ->whereRaw('plans.id = plan_requests.plan_id');
            });

            // Get the count before deletion
            $orphanedCount = $orphanedByUser->count() + $orphanedByPlan->count();

            // Delete orphaned requests
            $orphanedByUser->delete();
            $orphanedByPlan->delete();

            return redirect()->back()->with('success', __('Cleaned up ' . $orphanedCount . ' orphaned plan requests.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
