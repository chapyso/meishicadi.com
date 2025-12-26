<?php

namespace App\Http\Controllers;

use App\Models\ReferralSetting;
use App\Models\ReferralTransaction;
use App\Models\TransactionOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class ReferralProgramController extends Controller
{
    public function index()
    {
        if (Auth::user()->type == 'company') {
            $user = Auth::user();
            $referralSetting = ReferralSetting::first();
            $transactionDetail = ReferralTransaction::where('referral_code', $user->referral_code)->get();
            $transactionsOrder = TransactionOrder::where('request_user_id', $user->id)->get();
            $paidAmount = $transactionsOrder->where('status', 2)->sum('request_amount');
            $paymentRequest = TransactionOrder::where('status', 1)->where('request_user_id', $user->id)->first();
            return view('referral.company_index', compact('referralSetting', 'transactionDetail', 'paidAmount', 'transactionsOrder', 'paymentRequest'));

        } else {
            $referralSetting = ReferralSetting::first();
            $transactionDetail = ReferralTransaction::get();
            $payRequests = TransactionOrder::where('status', 1)->get();
            return view('referral.index', compact('referralSetting', 'transactionDetail', 'payRequests'));
        }

    }

    public function store(Request $request)
    {
        if ($request->is_comission_setting == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'commission' => 'required',
                    'threshold_amount' => 'required',
                    'guideline' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
        }
        if ($request->is_comission_setting == 'on') {
            $is_referral_enable = 1;
        } else {
            $is_referral_enable = 0;
        }
        $referralSetting = ReferralSetting::first();
        if ($referralSetting) {
            $referralSetting->commision = $request->commission;
            $referralSetting->threshold_amount = $request->threshold_amount;
            $referralSetting->guidelines = $request->guideline;
            $referralSetting->is_enable = $is_referral_enable;
            $referralSetting->created_by = \Auth::user()->creatorId();
            $referralSetting->save();
        } else {
            $referralSetting = new ReferralSetting();
            $referralSetting->commision = $request->commission;
            $referralSetting->threshold_amount = $request->threshold_amount;
            $referralSetting->guidelines = $request->guideline;
            $referralSetting->is_enable = $is_referral_enable;
            $referralSetting->created_by = \Auth::user()->creatorId();
            $referralSetting->save();
        }

        return redirect()->back()->with('success', 'Referral setting successfully updated.');

    }

    public function referralRequestAmountSent($paidAmount)
    {
        $user = Auth::user();
        return view('referral.request_amount', compact('user', 'paidAmount'));
    }
    public function requestAmountStore(Request $request, $id)
    {
        $order = new TransactionOrder();
        $order->request_amount = $request->request_amount;
        $order->request_user_id = Auth::user()->id;
        $order->status = 1;
        $order->date = date('Y-m-d');
        $order->save();

        return redirect()->route('referral.index')->with('success', __('Request Send Successfully.'));
    }
    public function referralRequestAmountCancel(Request $request, $id)
    {
        $transaction = TransactionOrder::where('request_user_id', $id)->orderBy('id', 'desc')->first();
        // $transaction->status = 0;
        // $transaction->request_user_id = Auth::user()->id;
        $transaction->delete();

        return redirect()->route('referral.index')->with('success', __('Request Cancel Successfully.'));
    }
    public function requestAmountStatus($id, $response)
    {
        $transaction = TransactionOrder::where('request_user_id', $id)->orderBy('id', 'desc')->first();
        $transaction->status = $response;
        $transaction->save();

        return redirect()->route('referral.index')->with('success', __('Request Status Updated Successfully.'));
    }
}
