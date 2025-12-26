<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Xendit\Xendit;

class XenditPaymentController extends Controller
{
    public function planPayWithXendit(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $xendit_api = $payment_setting['xendit_api_key'];
        
        $currency = isset($payment_setting['CURRENCY']) ? $payment_setting['CURRENCY'] : 'USD';

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = Auth::user();
        if ($plan) {
            $get_amount = $plan->price;

            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $get_amount = $plan->price - $discount_value;
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = Auth::user()->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $orderID;
                    $userCoupon->save();
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if($get_amount <= 0)
            {
                $user->plan = $plan->id;
                $user->save();

                $assignPlan = $user->assignPlan($plan->id);
                $orderID = time();

                if($request->has('coupon') && $request->coupon != ''){

                    $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    $discount_value         = ($plan->price / 100) * $coupons->discount;
                    $discounted_price = $plan->price - $discount_value;

                    if(!empty($coupons))
                    {
                        $userCoupon         = new UserCoupon();
                        $userCoupon->user   = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();

                        $usedCoupun = $coupons->used_coupon();
                        
                    }
                }

                if($assignPlan['is_success'] == true && !empty($plan))
                {
                    PlanOrder::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $plan->price,
                            'price_currency' => $get_amount == null ? 0 : $get_amount,
                            'txn_id' => '',
                            'payment_type' => 'Xendit',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $user->id,
                        ]
                    );
                    return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                }
            }

            Xendit::setApiKey($xendit_api);

            $external_id = $orderID;
            $params = [
                'external_id' => $external_id,
                'amount' => $get_amount,
                'description' => 'Xendit Payment',
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => $user->name,
                    'email' => $user->email,
                ],
                'success_redirect_url' => route('plan.get.xendit.status'),
                'failure_redirect_url' => route('plan.get.xendit.status'),
                'currency' => $currency,
            ];

            $invoice = \Xendit\Invoice::create($params);

            if ($invoice['status'] == 'PENDING') {
                return redirect($invoice['invoice_url']);
            } else {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Plan not found.'));
        }
    }

    public function planGetXenditStatus(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $xendit_api = $payment_setting['xendit_api_key'];
        Xendit::setApiKey($xendit_api);

        if ($request->has('external_id')) {
            $invoice = \Xendit\Invoice::retrieve($request->external_id);
            
            if ($invoice['status'] == 'PAID') {
                $user = Auth::user();
                $plan = Plan::where('price', $invoice['amount'])->first();
                
                if ($plan) {
                    $user->plan = $plan->id;
                    $user->save();
                    $assignPlan = $user->assignPlan($plan->id);
                    
                    if ($assignPlan['is_success'] == true && !empty($plan)) {
                        PlanOrder::create(
                            [
                                'order_id' => $invoice['external_id'],
                                'name' => null,
                                'email' => null,
                                'card_number' => null,
                                'card_exp_month' => null,
                                'card_exp_year' => null,
                                'plan_name' => $plan->name,
                                'plan_id' => $plan->id,
                                'price' => $invoice['amount'],
                                'price_currency' => $invoice['currency'],
                                'txn_id' => $invoice['id'],
                                'payment_type' => 'Xendit',
                                'payment_status' => 'succeeded',
                                'receipt' => null,
                                'user_id' => $user->id,
                            ]
                        );
                        return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                    }
                }
            } else {
                return redirect()->back()->with('error', __('Payment failed.'));
            }
        }
        
        return redirect()->back()->with('error', __('Payment failed.'));
    }
}
