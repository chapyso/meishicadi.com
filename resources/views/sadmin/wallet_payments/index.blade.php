@extends('layouts.app')
@section('title')
    {{ __('messages.wallet_payments.title') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            
            <!-- Statistics Cards -->
            <div class="row mb-5">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Revenue</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            ${{ number_format($totalRevenue, 2) }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="fas fa-dollar-sign text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Payments</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $totalPayments }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                        <i class="fas fa-wallet text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">This Month</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            ${{ number_format($monthlyRevenue, 2) }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="fas fa-calendar text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Average Payment</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            ${{ $totalPayments > 0 ? number_format($totalRevenue / $totalPayments, 2) : '0.00' }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                        <i class="fas fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet Payments Table -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-wallet me-2"></i>Wallet Add-On Payments
                        </h3>
                        <div>
                            <a href="{{ route('wallet.payments.export') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($walletPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>
                                            @if($payment->user)
                                                <div class="d-flex align-items-center">
                                                    @if($payment->user->profile_image)
                                                        <img src="{{ $payment->user->profile_image }}" 
                                                             class="rounded-circle me-2" 
                                                             width="32" height="32" 
                                                             alt="Profile">
                                                    @else
                                                        <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                             style="width: 32px; height: 32px;">
                                                            <span class="text-white fw-bold">
                                                                {{ strtoupper(substr($payment->user->first_name ?? 'U', 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $payment->user->full_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $payment->user->email ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">User not found</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->user->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                ${{ number_format($payment->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst($payment->payment_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment->status == \App\Models\Transaction::SUCCESS)
                                                <span class="badge bg-success">Success</span>
                                            @else
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $payment->created_at->format('M d, Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('wallet.payments.show', $payment->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-wallet fa-2x mb-3"></i>
                                                <p>No wallet add-on payments found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($walletPayments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $walletPayments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load statistics
    $.get('{{ route("wallet.payments.statistics") }}')
        .done(function(data) {
            // Update statistics if needed
            console.log('Wallet payment statistics loaded');
        })
        .fail(function() {
            console.error('Failed to load wallet payment statistics');
        });
});
</script>
@endpush 