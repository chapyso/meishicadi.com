@extends('layouts.admin')

@section('page-title')
    {{ __('Order Requests') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Order Requests') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Request Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderRequests as $orderRequest)
                                    <tr>
                                        <td>{{ $orderRequest->user->name ?? 'N/A' }}</td>
                                        <td>{{ $orderRequest->plan->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($orderRequest->status == 0)
                                                <span class="badge bg-warning">{{ __('Pending') }}</span>
                                            @elseif($orderRequest->status == 1)
                                                <span class="badge bg-success">{{ __('Approved') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $orderRequest->request_date }}</td>
                                        <td class="Action">
                                            <div class="action-btn bg-info ms-2">
                                                <a href="{{ route('order_request.show', $orderRequest->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('View') }}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-success ms-2">
                                                <a href="{{ route('order_request.status', $orderRequest->id, 1) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Approve') }}">
                                                    <i class="ti ti-check text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="{{ route('order_request.status', $orderRequest->id, 2) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Reject') }}">
                                                    <i class="ti ti-x text-white"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
