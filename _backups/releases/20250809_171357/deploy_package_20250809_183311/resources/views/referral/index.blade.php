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
                            <a href="#transaction" data-tab="transaction"
                                class="list-group-item list-group-item-action active
                     border-0 tab-link">{{ __('Transaction') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#payout_request" data-tab="payout_request"
                                class="list-group-item list-group-item-action border-0 tab-link ">{{ __('Payout Request') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#settings" data-tab="settings"
                                class="list-group-item list-group-item-action border-0  tab-link">{{ __('Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="transaction" class="card tab-content">
                        <div class="card-header">
                            <h5>{{ __('Transaction') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> {{ __('Company Name') }}</th>
                                            <th> {{ __('Referral Company Name') }}</th>
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
                                                <td>{{ $transaction->getreferralUser($transaction->referral_code) }}</td>
                                                <td>{{ !empty($transaction->getUser) ? $transaction->getUser->name : '-' }}
                                                </td>
                                                <td>{{ !empty($transaction->getPlan) ? $transaction->getPlan->name : '-' }}
                                                </td>
                                                <td>{{ $admin_payment_setting['CURRENCY_SYMBOL'] . $transaction->plan_price }}
                                                </td>
                                                <td>{{ $transaction->commission ? $transaction->commission : '' }}
                                                </td>
                                                <td>{{ $admin_payment_setting['CURRENCY_SYMBOL'] . ($transaction->plan_price * $transaction->commission) / 100 }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="payout_request" class="card tab-content d-none">
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
                                            <th> {{ __('Requested Amount') }}</th>
                                            <th> {{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payRequests as $key => $transaction)
                                            <tr>
                                                <td> {{ ++$key }} </td>
                                                <td>{{ !empty($transaction->getCompany) ? $transaction->getCompany->name : '-' }}
                                                </td>
                                                <td>{{ $transaction->date }}</td>
                                                <td>{{ $admin_payment_setting['CURRENCY_SYMBOL'] . $transaction->request_amount }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('request.amount.status', [$transaction->id, 1]) }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="ti ti-check"></i>
                                                    </a>
                                                    <a href="{{ route('request.amount.status', [$transaction->id, 0]) }}"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="ti ti-x"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="settings" class="card text-white tab-content d-none">
                        {{ Form::open(['url' => route('referral.store'), 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <h5 class="">{{ __('Settings') }}</h5>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 text-end" onclick="enableSettings()">
                                    <input type="hidden" name="is_comission_setting" value="off">
                                    <input type="checkbox" name="is_comission_setting" id="is_comission_setting"
                                        data-toggle="switchbutton" data-onstyle="primary" 
                                        {{ isset($referralSetting->is_enable) && $referralSetting->is_enable == 1 ? 'checked="checked"' : '' }}>
                                    <label class="custom-label form-label" for="is_comission_setting"></label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body referralDiv @if (isset($referralSetting->is_enable) && $referralSetting->is_enable == 0) disabledCookie @endif">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label h6">{{ __('Commission Percentage(%)') }}</label>
                                        <input type="text" name="commission" class="form-control"
                                            placeholder="Enter Commission Percentage"
                                            value="{{ isset($referralSetting->commision) ? $referralSetting->commision : null }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label h6">{{ __('Minimum Threshold Amount') }}</label>
                                        <input type="text" name="threshold_amount" class="form-control"
                                            placeholder="Enter Minimum Threshold Amount"
                                            value="{{ isset($referralSetting->threshold_amount) ? $referralSetting->threshold_amount : null }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label h6">{{ __('GuideLines') }}</label>
                                        <textarea class="summernote" row="10" cols="50" id="note" name="guideline">{!! isset($referralSetting->guidelines) ? $referralSetting->guidelines : null !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-0 p-1 text-end">
                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-lg btn-primary']) }}
                            </div>
                        </div>

                        {{ Form::close() }}
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
     <script type="text/javascript">
        function enableSettings() {
            const element = $('#is_comission_setting').is(':checked');
            $('.referralDiv').addClass('disabledCookie');
            if (element == true) {
                $('.referralDiv').removeClass('disabledCookie');
                $("#cookie_logging").attr('checked', true);
            } else {
                $('.referralDiv').addClass('disabledCookie');
                $("#cookie_logging").attr('checked', false);
            }
        }
    </script>
@endpush
