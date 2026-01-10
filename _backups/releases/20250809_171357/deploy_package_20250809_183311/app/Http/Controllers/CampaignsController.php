<?php

namespace App\Http\Controllers;

use App\Models\CostSetting;
use Illuminate\Http\Request;
use App\Models\Campaigns;
use App\Models\BusinessCategory;
use App\Models\Business;
use App\Models\Utility;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Http\RedirectResponse;
use App\Models\Coupon;
use App\Models\UserCoupon;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class CampaignsController extends Controller
{
    public function index(Request $request)
    {
        $campaignsData = Campaigns::with('categories')->with('users');

        if (!empty($request->category)) {
            $campaignsData->where('category', $request->cat_type);
        }
        if (!empty($request->business)) {
            $campaignsData->where('business', $request->business);
        }
      
        if (!empty($request->start_date)) {
            $campaignsData->where('start_date', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $campaignsData->where('end_date', '<=', $request->end_date);
        }
        if (Auth::user()->type == 'company') {
            $campaignsData = $campaignsData->where('created_by', Auth::user()->creatorId())->get();
        } else {
            $campaignsData = $campaignsData->get();
        }

        $currentDate = now();
        foreach ($campaignsData as $campaign) {
            if ($currentDate->greaterThan($campaign->end_date)) {
                $campaign->status = 2;
                $campaign->save();
            }
        }

        $businessList = Business::get()->pluck('title', 'id');
        $businessList->prepend('Select Business');
        $catList = BusinessCategory::get()->pluck('name', 'id');
        $catList->prepend('Select Category');
        $userList = User::get()->pluck('name', 'id');
        $userList->prepend('Select User');
        return view('campaigns.index', compact('campaignsData', 'businessList', 'catList', 'userList'));
    }

    public function create()
    {
        $category = BusinessCategory::get()->pluck('name', 'id');
        $category->prepend('Select Category');
        return view('campaigns.create', compact('category'));
    }

    public function businessData(Request $request)
    {
        $businesses = Business::where('created_by', \Auth::user()->creatorId())->where('business_category', $request->category_id)->where('status', 'active')->get()->pluck('title', 'id')->toArray();
        return response()->json($businesses);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category' => 'required',
                'business' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'total_cost' => 'required',
                'payment_method' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $total_days = $start_date->diffInDays($end_date);

        $campaign = new Campaigns();
        $campaign->name = $request->name;
        $campaign->user = $request->user;
        $campaign->category = $request->category;
        $campaign->business = $request->business;
        $campaign->total_days = $total_days;
        $campaign->total_cost = $request->total_cost;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->payment_method = $request->payment_method;
        $campaign->status = 0;
        $campaign->approval = 0;
        $campaign->created_by = \Auth::user()->creatorId();
        $campaign->save();

        return redirect()->route('campaigns.index')->with('success', __('Campaign successfully created.'));
    }

    public function edit($id)
    {
        $campaign = Campaigns::find($id);
        $category = BusinessCategory::get()->pluck('name', 'id');
        $category->prepend('Select Category');
        return view('campaigns.edit', compact('campaign', 'category'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category' => 'required',
                'business' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'total_cost' => 'required',
                'payment_method' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $total_days = $start_date->diffInDays($end_date);

        $campaign = Campaigns::find($id);
        $campaign->name = $request->name;
        $campaign->user = $request->user;
        $campaign->category = $request->category;
        $campaign->business = $request->business;
        $campaign->total_days = $total_days;
        $campaign->total_cost = $request->total_cost;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->payment_method = $request->payment_method;
        $campaign->save();

        return redirect()->route('campaigns.index')->with('success', __('Campaign successfully updated.'));
    }

    public function destroy($id)
    {
        $campaign = Campaigns::find($id);
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', __('Campaign successfully deleted.'));
    }

    public function viewCampaigns($id)
    {
        $campaign = Campaigns::with('categories')->with('users')->with('businesses')->find($id);
        return view('campaigns.view', compact('campaign'));
    }

    public function ChangeStatus($id, $response)
    {
        $campaign = Campaigns::find($id);
        $campaign->status = $response;
        $campaign->save();

        return redirect()->route('campaigns.index')->with('success', __('Campaign status successfully updated.'));
    }

    public function campaignsEnable(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'is_campaign_enable' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $post = $request->all();
        unset($post['_token']);

        $settings = Utility::settings();

        foreach ($post as $key => $data) {
            if (in_array($key, array_keys($settings))) {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $data,
                        $key,
                        \Auth::user()->creatorId(),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', __('Campaign setting successfully updated.'));
    }

    public function businessAnalytics(Request $request, $id)
    {
        $business = Business::find($id);
        $campaigns = Campaigns::where('business', $id)->get();
        return view('campaigns.analytics', compact('business', 'campaigns'));
    }

    public function campaignsSetup()
    {
        return view('campaigns.setup');
    }

    public function businessEnable(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'is_business_enable' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $post = $request->all();
        unset($post['_token']);

        $settings = Utility::settings();

        foreach ($post as $key => $data) {
            if (in_array($key, array_keys($settings))) {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $data,
                        $key,
                        \Auth::user()->creatorId(),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', __('Business setting successfully updated.'));
    }

    public function WholesaleCost(Request $request)
    {
        if ($request->ajax()) {
            $business_id = $request->business_id;
            $cost_setting = CostSetting::where('business_id', $business_id)->first();
            return response()->json($cost_setting);
        }

        $businesses = Business::where('created_by', \Auth::user()->creatorId())->get();
        return view('campaigns.wholesale_cost', compact('businesses'));
    }

    public function costData(Request $request)
    {
        $cost_setting = CostSetting::where('business_id', $request->business_id)->first();
        if ($cost_setting) {
            $cost_setting->cost_per_day = $request->cost_per_day;
            $cost_setting->save();
        } else {
            $cost_setting = new CostSetting();
            $cost_setting->business_id = $request->business_id;
            $cost_setting->cost_per_day = $request->cost_per_day;
            $cost_setting->created_by = \Auth::user()->creatorId();
            $cost_setting->save();
        }

        return redirect()->back()->with('success', __('Cost setting successfully updated.'));
    }

    public function paymentSuccess(Request $request)
    {
        $campaign_id = $request->campaign_id;
        $campaign = Campaigns::find($campaign_id);
        $campaign->status = 1;
        $campaign->save();

        return redirect()->route('campaigns.index')->with('success', __('Payment successful. Campaign activated.'));
    }
}
