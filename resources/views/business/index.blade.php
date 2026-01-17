@php

$cardLogo = \App\Models\Utility::get_file('card_logo/');
@endphp
@extends('layouts.admin')
@section('page-title')
    {{ __('Business') }}
@endsection
@section('title')
    {{ __('Business') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Business') }}</li>
@endsection
@section('action-btn')
    @can('create business')
        <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
            data-bs-placement="top">
            <a href="#" data-size="xl" data-url="{{ route('business.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create New Business') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
@endsection
@section('content')
    <style>
        .tap-counter-btn {
            transition: all 0.3s ease;
        }
        .tap-counter-btn:hover {
            transform: scale(1.05);
        }
        .tap-count-number {
            font-weight: bold;
            font-size: 0.9em;
        }
        .action-btn.bg-dark {
            background-color: #343a40 !important;
        }
        .action-btn.bg-dark:hover {
            background-color: #23272b !important;
        }
    </style>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Number') }}</th>
                                <th>{{ __('Profile Photo') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Generated Date') }}</th>
                                <th class="text-end">{{ __('Edit User') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($business as $val)
                                <tr class="{{ $val->admin_enable == 'off' ? 'row-disabled' : '' }}" >
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <div class="avatar">
                                            <img style="width: 55px;height: 55px;object-fit: cover" class="rounded-circle "
                                                src="{{ isset($val->logo) && !empty($val->logo) ? $cardLogo . '/' . $val->logo : asset('custom/img/logo-placeholder-image-21.png') }}"
                                                alt="">
                                        </div>
                                    </td>

                                    <td class="">
                                        @can('manage business')
                                            <a class=""
                                                href="{{ route('business.edit', $val->id) }}"><b>{{ ucFirst($val->title) }}</b></a>
                                        @else
                                            <span class="text-muted"><b>{{ ucFirst($val->title) }}</b></span>
                                        @endcan
                                    </td>
                                    <td><span
                                            class="badge fix_badge @if ($val->status == 'locked') bg-danger @else bg-info @endif p-2 px-3 rounded">{{ ucFirst($val->status) }}</span>
                                    </td>
                                    @php
                                        $now = $val->created_at;
                                        $date = $now->format('Y-m-d');
                                        $time = $now->format('H:i:s');
                                    @endphp
                                    <td>{{ $val->created_at }}</td>

                                    <td class="text-end">
                                        @if ($val->status != 'locked')
                                            <div class="action-btn bg-success  ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link"
                                                    data-link="{{ url('/' . $val->slug) }}" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Click to copy card link') }}"> <span
                                                        class="text-white"> <i class="ti ti-copy text-white"></i></span></a>
                                            </div>
                                            @can('view analytics business')
                                                <div class="action-btn bg-info  ms-2">
                                                    <a href="{{ route('business.analytics', $val->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Analytics') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-brand-google-analytics  text-white"></i></span></a>
                                                </div>
                                                <div class="action-btn bg-dark  ms-2">
                                                    <a href="#" 
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center tap-counter-btn"
                                                        data-business-id="{{ $val->id }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Tap Counter') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-tap  text-white"></i> <span class="tap-count-number ms-1">0</span></span></a>
                                                </div>
                                            @endcan
                                            @can('calendar appointment')
                                                <div class="action-btn bg-warning  ms-2">
                                                    <a href="{{ route('appointment.calendar', $val->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Calender') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-calendar text-white"></i></span></a>
                                                </div>
                                            @endcan
                                            @can('manage business')
                                                <div class="action-btn bg-info  ms-2">
                                                    <a href="{{ route('business.edit', $val->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Edit') }}"> <span
                                                            class="text-white"> <i class="ti ti-edit text-white"></i></span></a>
                                                </div>
                                            @endcan
                                            @can('manage contact')
                                                <div class="action-btn bg-warning  ms-2">
                                                    <a href="{{ route('business.contacts.show', $val->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Contacts') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-phone text-white"></i></span></a>
                                                </div>
                                            @endcan
                                            @can('create business')
                                                @if(Auth::user()->type != 'super admin')
                                                    <div class="action-btn bg-success  ms-2">
                                                        <a href="{{ route('wallet.options', $val->id) }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Wallet Pass') }}"> <span
                                                                class="text-white"> <i
                                                                    class="ti ti-wallet text-white"></i></span></a>
                                                    </div>
                                                @endif
                                            @endcan
                                            {{-- <div class="action-btn bg-dark ms-2">
                                                {{ Form::open(['route' => ['business.status', $val->id], 'class' => 'm-0']) }}
                                                @method('POST')
                                                <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                    data-bs-toggle="tooltip" title=""
                                                    data-bs-original-title="Business lock"
                                                    aria-label="Business lock"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can business lock. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $val->id }}"><i
                                                        class="ti ti-lock text-white text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div> --}}
                                            @can('delete business')
                                                <div class="action-btn bg-danger ms-2">
                                                    <a href="#"
                                                        class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $val->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><span class="text-white"><i
                                                                class="ti ti-trash"></i></span></a>
                                                </div>
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['business.destroy', $val->id],
                                                    'id' => 'delete-form-' . $val->id,
                                                ]) !!}
                                                {!! Form::close() !!}
                                            @else
                                                <span class="edit-icon align-middle bg-gray"><i
                                                        class="fas fa-lock text-white"></i></span>
                                            @endcan
                                            {{-- @else
                                            <div class="action-btn bg-dark  ms-2">
                                                {{ Form::open(['route' => ['business.status', $val->id], 'class' => 'm-0']) }}
                                                @method('POST')
                                                <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                    data-bs-toggle="tooltip" title=""
                                                    data-bs-original-title="Business Unlock"
                                                    aria-label="Business Unlock"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can business unlock. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $val->id }}">
                                                    <i class="ti ti-lock-open"></i></a>

                                                {!! Form::close() !!}
                                            </div> --}}
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script type="text/javascript">
        // Load tap counts for all businesses
        $(document).ready(function() {
            $('.tap-counter-btn').each(function() {
                var businessId = $(this).data('business-id');
                var $countElement = $(this).find('.tap-count-number');
                
                // Load tap count for this business
                $.ajax({
                    url: '/business/tap-count/' + businessId,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $countElement.text(response.tap_count);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading tap count for business ' + businessId);
                    }
                });
            });
        });



        // Tap counter button click handler
        $('.tap-counter-btn').on('click', function(e) {
            e.preventDefault();
            var businessId = $(this).data('business-id');
            var tapCount = $(this).find('.tap-count-number').text();
            
            // Show tap count details
            toastrs('{{ __("Info") }}', '{{ __("Tap Count") }}: ' + tapCount + ' {{ __("taps") }}', 'info');
        });
    </script>
@endpush
