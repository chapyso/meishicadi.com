<?php

namespace App\Http\Controllers;

use App\Models\OrderRequest;
use App\Models\Plan;
use Illuminate\Http\Request;
use Auth;

class OrderRequestController extends Controller
{
    public function index()
    {
        if (Auth::user()->type == 'company') {
            $orderRequests = OrderRequest::where('user_id', Auth::user()->id)->get();
        } else {
            $orderRequests = OrderRequest::all();
        }
        return view('order_request.index', compact('orderRequests'));
    }

    public function create()
    {
        $plans = Plan::all();
        return view('order_request.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'plan_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $orderRequest = new OrderRequest();
        $orderRequest->user_id = Auth::user()->id;
        $orderRequest->plan_id = $request->plan_id;
        $orderRequest->status = 0;
        $orderRequest->request_date = date('Y-m-d');
        $orderRequest->save();

        return redirect()->route('order_request.index')->with('success', __('Order Request successfully created.'));
    }

    public function show($id)
    {
        $orderRequest = OrderRequest::find($id);
        return view('order_request.show', compact('orderRequest'));
    }

    public function edit($id)
    {
        $orderRequest = OrderRequest::find($id);
        $plans = Plan::all();
        return view('order_request.edit', compact('orderRequest', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'plan_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $orderRequest = OrderRequest::find($id);
        $orderRequest->plan_id = $request->plan_id;
        $orderRequest->save();

        return redirect()->route('order_request.index')->with('success', __('Order Request successfully updated.'));
    }

    public function destroy($id)
    {
        $orderRequest = OrderRequest::find($id);
        $orderRequest->delete();

        return redirect()->route('order_request.index')->with('success', __('Order Request successfully deleted.'));
    }

    public function changeStatus($id, $status)
    {
        $orderRequest = OrderRequest::find($id);
        $orderRequest->status = $status;
        $orderRequest->save();

        return redirect()->route('order_request.index')->with('success', __('Order Request status successfully updated.'));
    }
}
