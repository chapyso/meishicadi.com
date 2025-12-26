@extends('layouts.admin')
@section('page-title')
    {{__('Plan Requests')}}
@endsection
@section('title')
    {{__('Plan Requests')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Plan Requests')}}</li>
@endsection
@section('content')
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="ti ti-refresh text-primary" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h4 class="mb-0">{{ __('Plan Renewal Requests') }}</h4>
                    <p class="text-muted mb-0">{{ __('Manage expired plan renewal requests from users') }}</p>
                </div>
            </div>
        </div>
        
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Requested Plan') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Request Date') }}</th>
                            <th>{{ __('Notes') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($plan_requests->count() > 0)
                            @foreach($plan_requests as $prequest)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-user"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $prequest->user->name }}</div>
                                            <small class="text-muted">{{ $prequest->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $prequest->plan->name }}</div>
                                    <small class="text-muted">
                                        {{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $prequest->plan->price }} / {{ ucfirst($prequest->plan->duration) }}
                                    </small>
                                </td>
                                <td>
                                    @if($prequest->status == 'pending')
                                        <span class="badge bg-warning">{{ __('Pending') }}</span>
                                    @elseif($prequest->status == 'approved')
                                        <span class="badge bg-success">{{ __('Approved') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ \App\Models\Utility::getDateFormated($prequest->request_date ?? $prequest->created_at, true) }}</div>
                                    <small class="text-muted">{{ $prequest->request_date ? $prequest->request_date->diffForHumans() : $prequest->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    @if($prequest->notes)
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#notesModal{{ $prequest->id }}">
                                            <i class="ti ti-message-circle me-1"></i>{{ __('View Notes') }}
                                        </button>
                                    @else
                                        <span class="text-muted">{{ __('No notes') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($prequest->status == 'pending')
                                        <div class="d-flex gap-2">
                                            <a href="{{route('response.request',[$prequest->id,1])}}" 
                                               data-bs-placement="top" 
                                               data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('Approve')}}" 
                                               class="btn btn-success btn-sm">
                                                <i class="ti ti-check"></i>
                                            </a>
                                            <a href="{{route('response.request',[$prequest->id,0])}}" 
                                               data-bs-placement="top" 
                                               data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('Reject')}}" 
                                               class="btn btn-danger btn-sm">
                                                <i class="ti ti-x"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted">{{ __('Processed') }}</span>
                                    @endif
                                </td>
                            </tr>
                            
                            <!-- Notes Modal -->
                            @if($prequest->notes)
                            <div class="modal fade" id="notesModal{{ $prequest->id }}" tabindex="-1" aria-labelledby="notesModalLabel{{ $prequest->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="notesModalLabel{{ $prequest->id }}">{{ __('Additional Notes & Feature Requests') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <h6 class="alert-heading mb-2">
                                                    <i class="ti ti-lightbulb me-2"></i>
                                                    {{ __('User Requested Features:') }}
                                                </h6>
                                                <p class="mb-0">{{ $prequest->notes }}</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <div class="text-center p-4">
                                        <div class="theme-avtar bg-secondary mx-auto mb-3">
                                            <i class="ti ti-inbox"></i>
                                        </div>
                                        <h6 class="text-muted">{{ __('No Plan Requests Found') }}</h6>
                                        <p class="text-muted mb-0">{{ __('When users submit expired plan renewal requests, they will appear here.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .theme-avtar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .theme-avtar i {
        font-size: 1rem;
        color: white;
    }
    
    .badge {
        font-size: 0.75rem;
    }
</style>
@endsection


