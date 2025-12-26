@extends('layouts.app')
@section('title')
    {{ __('messages.wallet_payments.details') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">
                                    <i class="fas fa-wallet me-2"></i>Wallet Payment Details
                                </h3>
                                <a href="{{ route('wallet.payments.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Payment Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Payment ID:</td>
                                            <td>{{ $payment->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Amount:</td>
                                            <td>
                                                <span class="fw-bold text-success fs-5">
                                                    ${{ number_format($payment->amount, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Currency:</td>
                                            <td>{{ $payment->currency ?? 'USD' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Payment Method:</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst($payment->payment_type) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                @if($payment->status == \App\Models\Transaction::SUCCESS)
                                                    <span class="badge bg-success">Success</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Transaction ID:</td>
                                            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date:</td>
                                            <td>{{ $payment->created_at->format('F d, Y \a\t H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">User Information</h5>
                                    @if($payment->user)
                                        <div class="d-flex align-items-center mb-3">
                                            @if($payment->user->profile_image)
                                                <img src="{{ $payment->user->profile_image }}" 
                                                     class="rounded-circle me-3" 
                                                     width="64" height="64" 
                                                     alt="Profile">
                                            @else
                                                <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 64px; height: 64px;">
                                                    <span class="text-white fw-bold fs-4">
                                                        {{ strtoupper(substr($payment->user->first_name ?? 'U', 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $payment->user->full_name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $payment->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                        
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold">User ID:</td>
                                                <td>{{ $payment->user->id }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Email:</td>
                                                <td>{{ $payment->user->email ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Contact:</td>
                                                <td>{{ $payment->user->contact ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Registered:</td>
                                                <td>{{ $payment->user->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        </table>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            User information not available
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($payment->meta)
                                <div class="mt-4">
                                    <h5 class="text-primary mb-3">Additional Information</h5>
                                    <div class="bg-light p-3 rounded">
                                        <pre class="mb-0">{{ json_encode($payment->meta, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="mailto:{{ $payment->user->email ?? '' }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-2"></i>Contact User
                                </a>
                                
                                @if($payment->user)
                                    <a href="{{ route('users.show', $payment->user->id) }}" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-user me-2"></i>View User Profile
                                    </a>
                                @endif
                                
                                <button class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>Print Details
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>Payment Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mb-3">
                                    <h4 class="text-success mb-1">${{ number_format($payment->amount, 2) }}</h4>
                                    <small class="text-muted">Payment Amount</small>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="mb-1">{{ ucfirst($payment->payment_type) }}</h6>
                                    <small class="text-muted">Payment Method</small>
                                </div>
                                
                                <div>
                                    <h6 class="mb-1">{{ $payment->created_at->diffForHumans() }}</h6>
                                    <small class="text-muted">Time Ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 