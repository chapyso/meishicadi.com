@extends('layouts.admin')
@section('page-title')
    {{ __('Referral Program') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Referral Program') }}</li>
@endsection
@section('title')
    {{ __('Referral Program') }}
@endsection
@php
    $admin_payment_setting = Utility::getAdminPaymentSetting();
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#guideline" data-tab="guideline"
                                class="list-group-item list-group-item-action active tab-link 
                     border-0">{{ __('GuideLine') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#referral_transaction" data-tab="referral_transaction"
                                class="list-group-item list-group-item-action border-0 tab-link ">{{ __('Referral Transaction') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#payout" data-tab="payout"
                                class="list-group-item list-group-item-action border-0 tab-link ">{{ __('Payout') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="guideline" class="card tab-content">
                        <div class="card-header">
                            <h5>{{ __('GuideLine') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="border p-3">
                                        <div class="form-group">
                                            <h4>{{ __('Refer ' . \Auth::user()->name . ' and earn ') . (isset($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$') . ($referralSetting ? $referralSetting->threshold_amount : null) . __(' per paid signup! ') }}
                                            </h4>
                                            <div class="ps-3">
                                                {!! $referralSetting ? $referralSetting->guidelines : null !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-5">
                                    <div class="form-group">
                                        <h4 class="text-center">{{ __('Share Your Link') }}</h4>
                                        @if (isset($referralSetting->is_enable) && $referralSetting->is_enable == 0)
                                            <small class=" text-danger">{{ __('Super admin has disabled the referral program.') }}</small>
                                            @endif
                                            <a href="#!" class="btn btn-sm btn-light-primary w-100 cp_link @if (isset($referralSetting->is_enable) && $referralSetting->is_enable == 0) disabledCookie @endif"
                                                data-link="{{ route('register', ['ref_id' => \Auth::user()->referral_code]) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                data-bs-original-title="Click to copy business link">
                                                {{ route('register', ['ref' => \Auth::user()->referral_code]) }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-copy ms-1">
                                                    <rect x="9" y="9" width="13" height="13" rx="2"
                                                        ry="2"></rect>
                                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1">
                                                    </path>
                                                </svg>
                                            </a>
                                        

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="referral_transaction" class="card tab-content d-none ">
                        <div class="card-header">
                            <h5>{{ __('Referral Transaction') }}</h5>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> {{ __('Company Name') }}</th>
                                            <th> {{ __('Plan Name') }}</th>
                                            <th> {{ __('Plan Price') }}</th>
                                            <th> {{ __('Comission(%)') }}</th>
                                            <th> {{ __('Comission Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactionDetail as $key => $transaction)
                                            <tr>
                                                <td> {{ ++$key }} </td>
                                                <td>{{ !empty($transaction->getUser) ? $transaction->getUser->name : '-' }}
                                                </td>

                                                <td>{{ !empty($transaction->getPlan) ? $transaction->getPlan->name : '-' }}
                                                </td>
                                                <td>{{ (isset($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$') . $transaction->plan_price }}
                                                </td>
                                                <td>{{ $transaction->commission ? $transaction->commission : '' }}
                                                </td>
                                                <td>{{ (isset($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$') . ($transaction->plan_price * $transaction->commission) / 100 }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="payout" class=" text-white tab-content d-none">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5 class="">{{ __('Payout') }}</h5>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        @if (\Auth::user()->commission_amount > $paidAmount)
                                            @if ($paymentRequest == null)
                                                <a href="#"
                                                    data-url = "{{ route('request.amount.sent', [$paidAmount]) }}"
                                                    data-ajax-popup="true" class="btn btn-primary btn-sm"
                                                    data-title="{{ __('Send Request') }}" data-bs-toggle="tooltip"
                                                    title="{{ __('Send Request') }}">
                                                    <span class="btn-inner--icon"><i
                                                            class="ti ti-corner-up-right"></i></span>
                                                </a>
                                            @else
                                                <a href="{{ route('request.amount.cancel', \Auth::user()->id) }}"
                                                    class="btn btn-danger btn-sm" data-title="{{ __('Cancel Request') }}"
                                                    data-bs-toggle="tooltip" title="{{ __('Cancel Request') }}">
                                                    <span class="btn-inner--icon"><i class="ti ti-x"></i></span>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="border border-2 p-3">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-auto mb-3 mb-sm-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="theme-avtar bg-primary">
                                                            <i class="ti ti-report-money"></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <small class="text-muted">{{ __('Total') }}</small>
                                                            <h6 class="m-0">{{ __('Commission Amount') }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto text-end">
                                                    <h4 class="m-0">
                                                        {{ (isset($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$') . \Auth::user()->commission_amount }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border border-2 p-3">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-auto mb-3 mb-sm-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="theme-avtar bg-primary">
                                                            <i class="ti ti-report-money"></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <small class="text-muted">{{ __('Paid') }}</small>
                                                            <h6 class="m-0">{{ __('Commission Amount') }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto text-end">
                                                    <h4 class="m-0">
                                                        {{ (isset($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$') . $paidAmount }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Payout Request') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th> {{ __('Company Name') }}</th>
                                                <th> {{ __('Requested Date') }}</th>
                                                <th> {{ __('Status') }}</th>
                                                <th> {{ __('Requested Amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transactionsOrder as $key => $transactions)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ \Auth::user()->name }}</td>
                                                    <td>{{ $transactions->date }}</td>
                                                    <td>
                                                        @if ($transactions->status == 0)
                                                            <span
                                                                class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\TransactionOrder::$status[$transactions->status]) }}</span>
                                                        @elseif($transactions->status == 1)
                                                            <span
                                                                class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\TransactionOrder::$status[$transactions->status]) }}</span>
                                                        @elseif($transactions->status == 2)
                                                            <span
                                                                class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\TransactionOrder::$status[$transactions->status]) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ (isset($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$') . $transactions->request_amount }}
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
            </div>

            <!-- [ sample-page ] end -->
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                    ['list', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'unlink']],
                ],
                height: 250,
            });
        });
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 200,
        })

        $('.tab-link').on('click', function() {
            var tabId = $(this).data('tab');
            $('.tab-content').addClass('d-none');
            $('#' + tabId).removeClass('d-none');

            $('.tab-link').removeClass('active');
            $(this).addClass('active');
        });
    </script>
    <script type="text/javascript">
        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('{{ __('Success') }}', '{{ __('Link Copy on Clipboard') }}', 'success');
        });
    </script>
@endpush
