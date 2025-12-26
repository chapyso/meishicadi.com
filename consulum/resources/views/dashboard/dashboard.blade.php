@php
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
    $qr_path = \App\Models\Utility::get_file('qrcode');
    $businesses = App\Models\Business::allBusiness();
    $currantBusiness = $users->currentBusiness();
    $bussiness_id = $users->current_business;
@endphp
@extends('layouts.admin')
@push('css-page')
    <style>
        .shareqrcode img {
            width: 65%;
            height: 65%;
        }

        .shareqrcode canvas {
            width: 65%;
            height: 65%;
        }
        
        /* Modern Dashboard Card Styling */
        .dashboard-card {
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            transition: all 0.3s ease;
            overflow: hidden;
            background-color: rgba(0, 0, 0, 0.4) !important;
            backdrop-filter: blur(10px);
        }
        
        .dashboard-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25) !important;
            transform: translateY(-2px);
        }
        
        .dashboard-card .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 1.25rem 1.5rem !important;
            background: transparent !important;
        }
        
        .dashboard-card .card-header h5 {
            font-size: 18px !important;
            font-weight: 600 !important;
            color: #ffffff !important;
            margin-bottom: 0 !important;
            letter-spacing: 0.3px;
        }
        
        .dashboard-card .card-header .float-right {
            font-size: 13px !important;
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 500;
            letter-spacing: 0.2px;
        }
        
        .dashboard-card .card-header small {
            font-size: 12px !important;
            color: rgba(255, 255, 255, 0.6) !important;
            font-weight: 400;
            margin-left: 8px;
        }
        
        .dashboard-card .card-body {
            padding: 1.5rem !important;
        }
        
        /* Chart Container Spacing */
        .chart-container {
            padding: 10px 0;
        }
        
        /* Modern Typography */
        .dashboard-card .card-header h5 {
            font-size: 18px;
            font-weight: 600;
        }
        
        /* Custom Legend Styling */
        .custom-legend-container {
            padding: 12px 0 8px 0;
        }
        
        .custom-legend-item {
            transition: all 0.3s ease;
            padding: 6px 10px;
            border-radius: 8px;
            margin: 4px 6px 4px 0;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .custom-legend-item:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            transform: scale(1.05);
        }
        
        .custom-legend-item img {
            flex-shrink: 0;
            width: 16px !important;
            height: 16px !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
        }
        
        .custom-legend-item span[style*="backgroundColor"] {
            flex-shrink: 0;
            width: 10px !important;
            height: 10px !important;
            margin-right: 6px !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .custom-legend-item span:not([style*="backgroundColor"]) {
            font-size: 11px !important;
            color: #ffffff !important;
            font-weight: 500;
        }
        
        .custom-legend-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            justify-content: flex-start;
            margin-top: 8px;
        }
        
        .custom-legend-more {
            color: rgba(255, 255, 255, 0.6) !important;
            font-size: 11px !important;
            margin-top: 8px !important;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .custom-legend-list {
                flex-direction: column !important;
            }
            
            .dashboard-card .card-header {
                padding: 1rem !important;
            }
            
            .dashboard-card .card-body {
                padding: 1rem !important;
            }
        }
        
        /* Improved Grid Spacing */
        .row > [class*="col-"] {
            margin-bottom: 1.5rem;
        }
        
        /* Make business dropdown text white - very specific selectors */
        .drp-text,
        .drp-text.hide-mob,
        .dash-head-link .drp-text,
        .dash-head-link .drp-text.hide-mob,
        .cust-btn .drp-text,
        .cust-btn .drp-text.hide-mob,
        .drp-language .drp-text,
        .drp-language .drp-text.hide-mob,
        .dash-h-item .drp-text,
        .dash-h-item .drp-text.hide-mob,
        .dropdown-toggle .drp-text,
        .dropdown-toggle .drp-text.hide-mob,
        a.dash-head-link .drp-text,
        a.dash-head-link .drp-text.hide-mob,
        span.drp-text,
        span.drp-text.hide-mob,
        body .drp-text,
        body .drp-text.hide-mob,
        body.theme-1 .drp-text,
        body.theme-2 .drp-text,
        body.theme-3 .drp-text,
        body.theme-4 .drp-text,
        body.theme-5 .drp-text,
        body.theme-6 .drp-text,
        body.theme-7 .drp-text,
        body.theme-8 .drp-text,
        body.theme-9 .drp-text {
            color: #ffffff !important;
        }

        /* Quick Businesses widget */
        .quick-business-card .card-body {
            padding: 1.25rem !important;
        }

        .quick-business-table {
            max-height: 450px;
            overflow-y: auto;
        }

        .quick-business-table .table {
            border-collapse: separate;
            border-spacing: 0 6px;
        }

        .quick-business-table th {
            color: #e5e7eb;
            font-weight: 600;
            font-size: 0.875rem;
            border: none !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
        }

        .quick-business-table td {
            padding: 12px;
            background-color: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            color: #ffffff;
        }

        .quick-business-table tr {
            transition: all 0.2s ease;
        }

        .quick-business-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .quick-business-table .badge {
            border: 1px solid #fff !important;
        }

        .quick-actions .action-btn {
            min-width: 34px;
            height: 34px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .quick-actions .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .quick-actions .action-btn .btn {
            width: 100%;
            height: 100%;
            padding: 0;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('content')

    <div class="row">
        <div class="page-title mb-3">
            <div class="row justify-content-between align-items-center">
                <div class="d-flex col-md-10 mb-3 mb-md-0">
                    <h5 class="h3 mb-0">{{ __('Dashboard') }}</h5>
                        {{-- //business Display Start --}}
                        <ul class="list-unstyled">
                            <li class="dropdown dash-h-item drp-language">
                                <a class="dash-head-link dropdown-toggle arrow-none me-0 cust-btn shadow-sm border border-success"
                                    data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                    aria-expanded="false" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    data-bs-original-title="{{ __('Select your bussiness') }}">
                                    <i class="ti ti-credit-card"></i>
                                    <span class="drp-text hide-mob">{{ __(ucfirst($currantBusiness)) }}</span>
                                    <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                </a>
                                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end page-inner-dropdowm">
                                    @foreach ($businesses as $key => $business)
                                        <a href="{{ route('business.change', $key) }}" class="dropdown-item">
                                            <i
                                                class="@if ($bussiness_id == $key) ti ti-checks text-primary @elseif($currantBusiness == $business) ti ti-checks text-primary @endif "></i>
                                            <span>{{ ucfirst($business) }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                        </ul>

                        {{-- //business Display End --}}
                   
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-4 welcome-card">
                    <div class="border bg-light-success p-3 border-success rounded text-dark h-100">
                        <div class="d-flex align-items-center mb-4">
                             {{-- //profile photo goes here --}}
                            <div>
                                <h5 class="mb-0">
                                    <span class="d-block" id="greetings"></span>
                                                       {{-- //name line goes here --}}              </h5>
                            </div>
                        </div>
                        <p class="mb-0">
                            {{ __('Have a nice day! Digital Transformation is not an end game, It is a constant state of evolution!') }}
                        </p>
                        <div class="btn-group mt-4">
                            <button class="btn  btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-plus me-2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                {{ __('Quick add') }}</button>
                            <div class="dropdown-menu">
                                @can('create Card')
                                    <a href="#" data-size="xl" data-url="{{ route('business.create') }}"
                                        data-ajax-popup="true" data-title="Create New Business" class="dropdown-item"
                                        data-bs-placement="top ">
                                        <span>{{ __('Add new Card') }}</span>
                                    </a>
                                @endcan
                                @can('create user')
                                    <a href="#" data-size="md" data-url="{{ route('users.create') }}"
                                        data-ajax-popup="true" data-title="Create New User" class="dropdown-item"
                                        data-bs-placement="top ">
                                        <span>{{ __('Add new user') }}</span>
                                    </a>
                                @endcan
                                @can('create role')
                                    <a href="#" data-size="lg" data-url="{{ route('roles.create') }}"
                                        data-ajax-popup="true" data-title="Create New Role" class="dropdown-item"
                                        data-bs-placement="top">
                                        <span>{{ __('Add new role') }}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                @if ($businessData)
                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body" style="min-height: 230px;">
                                <h6 class="mb-0 text-center">{{ ucFirst($businessData->title) }}</h6>
                                <div class="mb-3 shareqrcode text-center"></div>
                                <div class="d-flex justify-content-between">
                                    <a href="#!" class="btn btn-sm btn-primary w-100 cp_link"
                                        data-link="{{ url('/' . $businessData->slug) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title=""
                                        data-bs-original-title="Click to copy business link">
                                        {{ 'Business Link' }}
                                    </a>
                                    <a href="#" id="socialShareButton"
                                        class="socialShareButton btn btn-sm btn-primary ms-1 share-btn">
                                        <i class="ti ti-share"></i>
                                    </a>
                                    <div id="sharingButtonsContainer" class="sharingButtonsContainer"
                                        style="display: none;">
                                        <div class="Demo1 d-flex align-items-center justify-content-center hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body" style="min-height: 230px;">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-briefcase dash-micon"></i>
                            </div>
                            <p class="text-muted text-sm mt-4 mb-2"></p>
                            <h6 class="mb-3">{{ __('Total Cards') }}</h6>
                            <h3 class="mb-0">{{ $total_bussiness }} </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body" style="min-height: 230px;">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-clipboard-check dash-micon"></i>
                            </div>
                            <p class="text-muted text-sm mt-4 mb-2"></p>
                            <h6 class="mb-3">{{ __('Total Appointment') }}</h6>
                            <h3 class="mb-0">{{ $total_app }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body" style="min-height: 230px;">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-users dash-micon"></i>
                            </div>
                            <p class="text-muted text-sm mt-4 mb-2"></p>
                            <h6 class="mb-3">{{ __('Total Admin') }}</h6>
                            <h3 class="mb-0">{{ $total_staff }}</h3>
                        </div>
                    </div>
                </div>



                <div class="col-lg-12 mt-2">
                    <div class="card dashboard-card quick-business-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">{{ __('Quick Edit – Businesses') }}</h5>
                            <span class="mb-0 text-sm text-muted">{{ __('Latest 10') }}</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive quick-business-table">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 70px;">{{ __('Profile Photo') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th style="width: 140px;">{{ __('Country') }}</th>
                                            <th style="width: 180px;">{{ __('Generated Date') }}</th>
                                            <th style="width: 90px;">{{ __('Taps') }}</th>
                                            <th class="text-end" style="width: 240px;">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($quickBusinesses as $val)
                                            <tr class="{{ $val->admin_enable == 'off' ? 'row-disabled' : '' }}">
                                                <td>
                                                    <div class="avatar d-flex align-items-center">
                                                        @php
                                                            $cardLogo = \App\Models\Utility::get_file('card_logo/');
                                                            $logoUrl = '';
                                                            if (isset($val->logo) && !empty($val->logo) && !empty($cardLogo)) {
                                                                $logoFilename = ltrim($val->logo, '/');
                                                                $logoUrl = rtrim($cardLogo, '/') . '/' . $logoFilename;
                                                                $logoUrl = preg_replace('#([^:])//+#', '$1/', $logoUrl);
                                                                try {
                                                                    $fileExists = \Storage::disk('public')->exists('card_logo/' . $logoFilename);
                                                                    if (!$fileExists) {
                                                                        $directPath = storage_path('card_logo/' . $logoFilename);
                                                                        $fileExists = file_exists($directPath);
                                                                        if ($fileExists) {
                                                                            $logoUrl = asset('storage/card_logo/' . $logoFilename);
                                                                        }
                                                                    }
                                                                    if (!$fileExists) {
                                                                        $publicPath = storage_path('app/public/card_logo/' . $logoFilename);
                                                                        $fileExists = file_exists($publicPath);
                                                                    }
                                                                    if (!$fileExists) {
                                                                        $logoUrl = asset('custom/img/logo-placeholder-image-21.png');
                                                                    }
                                                                } catch (\Exception $e) {
                                                                    $filePath1 = storage_path('card_logo/' . $logoFilename);
                                                                    $filePath2 = storage_path('app/public/card_logo/' . $logoFilename);

                                                                    if (file_exists($filePath1)) {
                                                                        $logoUrl = asset('storage/card_logo/' . $logoFilename);
                                                                    } elseif (file_exists($filePath2)) {
                                                                    } else {
                                                                        $logoUrl = asset('custom/img/logo-placeholder-image-21.png');
                                                                    }
                                                                }
                                                            } else {
                                                                $logoUrl = asset('custom/img/logo-placeholder-image-21.png');
                                                            }
                                                        @endphp
                                                        <img style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #e5e7eb;"
                                                            class="rounded-circle"
                                                            src="{{ $logoUrl }}"
                                                            alt="{{ ucFirst($val->title) }}"
                                                            onerror="this.onerror=null; this.src='{{ asset('custom/img/logo-placeholder-image-21.png') }}';">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('business.edit', $val->id) }}" class="text-white text-decoration-none fw-semibold">
                                                        {{ ucFirst($val->title) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if (isset($val->country) && $val->country != 'Unknown')
                                                        <span class="badge bg-secondary p-2 px-3 rounded-pill text-white">
                                                            {{ $val->country }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($val->created_at)
                                                        <span class="text-muted">{{ $val->created_at->format('Y-m-d H:i') }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="d-flex align-items-center gap-1">
                                                        <i class="ti ti-cursor-text text-primary" style="font-size: 15px;"></i>
                                                        <span class="fw-semibold">{{ $val->tap_count ?? 0 }}</span>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex align-items-center justify-content-end gap-1 flex-wrap quick-actions">
                                                        @if ($val->status != 'lock' && $val->status != 'locked')
                                                            <div class="action-btn bg-dark" style="border: 1px solid white;">
                                                                <a href="#"
                                                                    class="bs-pass-para btn btn-sm d-inline-flex align-items-center justify-content-center"
                                                                    data-confirm="{{ __('You want to confirm this action') }}"
                                                                    data-text="{{ __('Press Yes to continue or No to go back') }}"
                                                                    data-confirm-yes="duplicate-form-quick-{{ $val->id }}"
                                                                    title="{{ __('Duplicate') }}" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top">
                                                                    <span class="text-white"><i class="ti ti-copy"></i></span>
                                                                </a>
                                                                {!! Form::open([
                                                                    'method' => 'POST',
                                                                    'route' => ['business.duplicate', $val->id],
                                                                    'id' => 'duplicate-form-quick-' . $val->id,
                                                                ]) !!}
                                                                {!! Form::close() !!}
                                                            </div>

                                                            <div class="action-btn bg-success">
                                                                <a href="#"
                                                                    class="btn btn-sm d-inline-flex align-items-center justify-content-center cp_link"
                                                                    data-link="{{ url('/' . $val->slug) }}" data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Click to copy card link') }}">
                                                                    <span class="text-white"><i class="ti ti-link"></i></span>
                                                                </a>
                                                            </div>

                                                            @can('view analytics business')
                                                                <div class="action-btn bg-info">
                                                                    <a href="{{ route('business.analytics', $val->id) }}"
                                                                        class="btn btn-sm d-inline-flex align-items-center justify-content-center"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('Business Analytics') }}">
                                                                        <span class="text-white"><i class="ti ti-brand-google-analytics"></i></span>
                                                                    </a>
                                                                </div>
                                                            @endcan

                                                            @can('calendar appointment')
                                                                <div class="action-btn bg-warning">
                                                                    <a href="{{ route('appointment.calendar', $val->id) }}"
                                                                        class="btn btn-sm d-inline-flex align-items-center justify-content-center"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('Business Calendar') }}">
                                                                        <span class="text-white"><i class="ti ti-calendar"></i></span>
                                                                    </a>
                                                                </div>
                                                            @endcan

                                                            <div class="action-btn bg-info">
                                                                <a href="{{ route('business.edit', $val->id) }}"
                                                                    class="btn btn-sm d-inline-flex align-items-center justify-content-center"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Business Edit') }}">
                                                                    <span class="text-white"><i class="ti ti-edit"></i></span>
                                                                </a>
                                                            </div>

                                                            @can('manage contact')
                                                                <div class="action-btn bg-warning">
                                                                    <a href="{{ route('business.contacts.show', $val->id) }}"
                                                                        class="btn btn-sm d-inline-flex align-items-center justify-content-center"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="{{ __('Business Contacts') }}">
                                                                        <span class="text-white"><i class="ti ti-phone"></i></span>
                                                                    </a>
                                                                </div>
                                                            @endcan

                                                            @can('delete business')
                                                                <div class="action-btn bg-danger">
                                                                    <a href="#"
                                                                        class="bs-pass-para btn btn-sm d-inline-flex align-items-center justify-content-center"
                                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                        data-confirm-yes="delete-form-quick-{{ $val->id }}"
                                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top">
                                                                        <span class="text-white"><i class="ti ti-trash"></i></span>
                                                                    </a>
                                                                </div>
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['business.destroy', $val->id],
                                                                    'id' => 'delete-form-quick-' . $val->id,
                                                                ]) !!}
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        @else
                                                            <span class="badge bg-secondary text-white px-3 py-2">
                                                                <i class="ti ti-lock me-1"></i>{{ __('Locked') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    {{ __('No businesses available yet.') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <a href="{{ route('business.index') }}" class="btn btn-sm btn-primary">
                                    {{ __('View All Businesses') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 ">
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <div class="float-end">
                                <span class="mb-0 text-sm float-right mt-1">{{ __('Last 15 Days') }}</span>
                            </div>
                            <h5 class="mb-0 float-left">{{ __('Browser') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div id="pie-storebrowser" class="chart-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 ">
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <div class="float-end">
                                <span class="mb-0 text-sm float-right mt-1">{{ __('Last 15 Days') }}</span>
                            </div>
                            <h5 class="mb-0 float-left">{{ __('Device') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div id="pie-storedashborad" class="chart-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Storage Limit Chart --}}
                @if (\Auth::user()->type == 'company')
                    <div class="col-md-4 ">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <h5>{{ __('Storage Status') }} <small>({{ $users->storage_limit . 'MB' }} /
                                        {{ $plan->storage_limit . 'MB' }})</small></h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div id="device-chart" class="chart-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Storage Limit Chart End --}}
            </div>
        </div>
        @php
            $qrImageSrc = '';
            $qrPlaceholder = asset('storage/qrcode/qrcode-placeholder.png');
            if (isset($qr_detail->image) && !empty($qr_detail->image)) {
                $qrImagePath = storage_path('app/public/qrcode/' . $qr_detail->image);
                if (file_exists($qrImagePath)) {
                    $qrImageSrc = asset('storage/qrcode/' . $qr_detail->image);
                }
            }
            if (empty($qrImageSrc)) {
                $qrImageSrc = $qrPlaceholder;
            }
        @endphp
        <img src="{{ $qrImageSrc }}" id="image-buffers" style="display: none" alt="qr-logo">
    @endsection

    @push('custom-scripts')
        <script src="{{ asset('custom/js/purpose.js') }}"></script>
        @if (isset($plan->enable_qr_code) && $plan->enable_qr_code == 'on')
            <script src="{{ asset('custom/js/jquery.qrcode.min.js') }}" onload="console.log('[load] jquery.qrcode.min.js loaded')"></script>
        @else
            <script src="{{ asset('custom/js/jquery.qrcode.js') }}" onload="console.log('[load] jquery.qrcode.js loaded')"></script>
            <script type="text/javascript" src="https://jeromeetienne.github.io/jquery-qrcode/src/qrcode.js" onload="console.log('[load] qrcode.js fallback loaded')"></script>
        @endif
        <script type="text/javascript">
            $(document).on("change", "select[name='select_card']", function() {
                var b_id = $("select[name='select_card']").val();
                if (b_id == '0') {
                    window.location.href = '{{ url('/dashboard') }}';
                } else {
                    window.location.href = '{{ url('business/analytics') }}/' + b_id;
                }

            });
        </script>
        <script>
            // Device breakdown
            var deviceOptions = {
                chart: {
                    height: 280,
                    type: 'donut',
                    background: 'transparent',
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '13px',
                        fontWeight: 600,
                        colors: ['#ffffff']
                    },
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        blur: 3,
                        opacity: 0.5
                    }
                },
                series: {!! json_encode($devicearray['data']) !!},
                colors: ['#00E396', '#FFA726', '#FF4560', '#3EC9D6', '#775DD0', '#FEB019', '#008FFB'],
                labels: {!! json_encode($devicearray['label']) !!},
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '12px',
                    fontFamily: PurposeStyle.fonts.base,
                    fontWeight: 500,
                    labels: {
                        colors: '#ffffff'
                    },
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 6,
                        offsetX: -5,
                        offsetY: 0
                    },
                    itemMargin: {
                        horizontal: 8,
                        vertical: 4
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: 600,
                                    color: '#ffffff',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    fontWeight: 700,
                                    color: '#ffffff',
                                    offsetY: 10,
                                    formatter: function(val) {
                                        return val + '%'
                                    }
                                },
                                total: {
                                    show: false
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontSize: '12px',
                        fontFamily: PurposeStyle.fonts.base
                    },
                    y: {
                        formatter: function(val) {
                            return val + '%'
                        }
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['rgba(0, 0, 0, 0.3)']
                }
            };
            var deviceChart = new ApexCharts(document.querySelector("#pie-storedashborad"), deviceOptions);
            deviceChart.render();

            // Browser breakdown
            var browserOptions = {
                chart: {
                    height: 280,
                    type: 'donut',
                    background: 'transparent',
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '13px',
                        fontWeight: 600,
                        colors: ['#ffffff']
                    },
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        blur: 3,
                        opacity: 0.5
                    }
                },
                series: {!! json_encode($browserarray['data']) !!},
                colors: ['#00E396', '#FFA726', '#FF4560', '#3EC9D6', '#775DD0', '#FEB019', '#008FFB'],
                labels: {!! json_encode($browserarray['label']) !!},
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '12px',
                    fontFamily: PurposeStyle.fonts.base,
                    fontWeight: 500,
                    labels: {
                        colors: '#ffffff'
                    },
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 6,
                        offsetX: -5,
                        offsetY: 0
                    },
                    itemMargin: {
                        horizontal: 8,
                        vertical: 4
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: 600,
                                    color: '#ffffff',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    fontWeight: 700,
                                    color: '#ffffff',
                                    offsetY: 10,
                                    formatter: function(val) {
                                        return val + '%'
                                    }
                                },
                                total: {
                                    show: false
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontSize: '12px',
                        fontFamily: PurposeStyle.fonts.base
                    },
                    y: {
                        formatter: function(val) {
                            return val + '%'
                        }
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['rgba(0, 0, 0, 0.3)']
                }
            };
            var browserChart = new ApexCharts(document.querySelector("#pie-storebrowser"), browserOptions);
            browserChart.render();
        </script>
        {{-- AUTO TOOLTIP FOCUS --}}
        <script>
            $(function() {
                $(".dash-head-link.cust-btn").tooltip().tooltip("show");
                setTimeout(() => {
                    $(".dash-head-link.cust-btn").tooltip().tooltip("hide");

                    $(".cust-btn-creat").tooltip().tooltip("show");
                }, 4000);
            });
            $(function() {
                setTimeout(() => {
                    $(".cust-btn-creat").tooltip().tooltip("hide");
                }, 8000);
            });
        </script>
        <script>
            // Force business dropdown text to be white
            $(document).ready(function() {
                function makeDrpTextWhite() {
                    $('.drp-text').each(function() {
                        $(this).css('color', '#ffffff');
                        this.style.setProperty('color', '#ffffff', 'important');
                    });
                    $('.drp-text.hide-mob').each(function() {
                        $(this).css('color', '#ffffff');
                        this.style.setProperty('color', '#ffffff', 'important');
                    });
                    $('.dash-head-link .drp-text').each(function() {
                        $(this).css('color', '#ffffff');
                        this.style.setProperty('color', '#ffffff', 'important');
                    });
                    $('.cust-btn .drp-text').each(function() {
                        $(this).css('color', '#ffffff');
                        this.style.setProperty('color', '#ffffff', 'important');
                    });
                    
                    // Target the span directly
                    $('.dash-head-link span.drp-text, .cust-btn span.drp-text').each(function() {
                        $(this).css('color', '#ffffff');
                        this.style.setProperty('color', '#ffffff', 'important');
                    });
                }
                
                // Run immediately and on interval
                makeDrpTextWhite();
                setTimeout(makeDrpTextWhite, 100);
                setTimeout(makeDrpTextWhite, 500);
                setTimeout(makeDrpTextWhite, 1000);
                
                // Watch for DOM changes
                if (window.MutationObserver) {
                    var observer = new MutationObserver(function(mutations) {
                        makeDrpTextWhite();
                    });
                    observer.observe(document.body, {
                        childList: true,
                        subtree: true
                    });
                }
            });
        </script>
        <script>
            (function() {
                var storagePercentage = {{ number_format($storage_limit, 2) }};
                var storageUsed = {{ $users->storage_limit }};
                var storageTotal = {{ $plan->storage_limit }};
                
                var options = {
                    series: [storagePercentage],
                    chart: {
                        height: 320,
                        type: 'radialBar',
                        offsetY: 0,
                        sparkline: {
                            enabled: false
                        },
                        background: 'transparent',
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -90,
                            endAngle: 90,
                            hollow: {
                                size: '65%',
                            },
                            track: {
                                background: 'rgba(255, 255, 255, 0.1)',
                                strokeWidth: '100%',
                                margin: 8,
                            },
                            dataLabels: {
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: 600,
                                    color: '#ffffff',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '28px',
                                    fontWeight: 700,
                                    color: '#ffffff',
                                    offsetY: 10,
                                    formatter: function(val) {
                                        return val.toFixed(2) + '%'
                                    }
                                }
                            }
                        }
                    },
                    grid: {
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 0,
                            left: 0
                        }
                    },
                    colors: ['#00E396'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'radial',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#00D084'],
                            inverseColors: false,
                            opacityFrom: 1,
                            opacityTo: 0.8,
                            stops: [0, 50, 100]
                        }
                    },
                    labels: ['Used'],
                    stroke: {
                        lineCap: 'round'
                    }
                };
                var chart = new ApexCharts(document.querySelector("#device-chart"), options);
                chart.render();
            })();
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
        <script>
            $(document).ready(function() {
                @if ($businessData)
                    var slug = '{{ $businessData->slug }}';
                    var url_link = `{{ url('/') }}/${slug}`;

                    $('.qr-link').text(url_link);

                    // Guard against missing plugin
                    if (typeof $.fn.qrcode !== 'function') {
                        console.error('[qr] jQuery.qrcode missing before generation');
                        return;
                    }

                    // Build generator with safe image loading
                    var foreground_color = `{{ isset($qr_detail->foreground_color) ? $qr_detail->foreground_color : '#000000' }}`;
                    var background_color = `{{ isset($qr_detail->background_color) ? $qr_detail->background_color : '#ffffff' }}`;
                    var radius = `{{ isset($qr_detail->radius) ? $qr_detail->radius : 26 }}`;
                    var qr_type = `{{ isset($qr_detail->qr_type) ? $qr_detail->qr_type : 0 }}`;
                    var qr_font = `{{ isset($qr_detail->qr_text) ? $qr_detail->qr_text : 'vCard' }}`;
                    var qr_font_color = `{{ isset($qr_detail->qr_text_color) ? $qr_detail->qr_text_color : '#f50a0a' }}`;
                    var size = `{{ isset($qr_detail->size) ? $qr_detail->size : 9 }}`;
                    var logoSrc = $('#image-buffers').attr('src');

                    function renderQRWithLogo(logoImg) {
                        $('.shareqrcode').empty().qrcode({
                            render: 'image',
                            size: 500,
                            ecLevel: 'H',
                            minVersion: 3,
                            quiet: 1,
                            text: url_link,
                            fill: foreground_color,
                            background: background_color,
                            radius: 0.01 * parseInt(radius, 10),
                            mode: parseInt(qr_type, 10),
                            label: qr_font,
                            fontcolor: qr_font_color,
                            image: logoImg,
                            mSize: 0.01 * parseInt(size, 10)
                        });
                        console.log('[qr] QR generated with logo');
                    }

                    function renderQRWithoutLogo() {
                        $('.shareqrcode').empty().qrcode(url_link);
                        console.log('[qr] QR generated without logo fallback');
                    }

                    @if (isset($plan->enable_qr_code) && $plan->enable_qr_code == 'on')
                        if (logoSrc) {
                            var img = new Image();
                            img.onload = function() {
                                console.log('[qr] logo image loaded OK', logoSrc);
                                renderQRWithLogo(img);
                            };
                            img.onerror = function() {
                                console.warn('[qr] logo image failed to load, using fallback', logoSrc);
                                renderQRWithoutLogo();
                            };
                            img.src = logoSrc;
                            console.log('[qr] loading logo image', logoSrc);
                        } else {
                            console.warn('[qr] no logo image provided, using fallback');
                            renderQRWithoutLogo();
                        }
                    @else
                        renderQRWithoutLogo();
                    @endif
                @endif
            });
        </script>
        <script>
            var timezone = '{{ !empty(env('APP_TIMEZONE')) ? env('APP_TIMEZONE') : 'IST' }}';

            let today = new Date(new Date().toLocaleString("en-US", {
                timeZone: timezone
            }));
            var curHr = today.getHours()
            var target = document.getElementById("greetings");

            if (curHr < 12) {
                target.innerHTML = "Good Morning,";
            } else if (curHr < 17) {
                target.innerHTML = "Good Afternoon,";
            } else {
                target.innerHTML = "Good Evening,";
            }
        </script>

        <script type="text/javascript">
        @if ($businessData)
            $(document).ready(function() {
                var customURL = {!! json_encode(url('/' . $businessData->slug)) !!};
                console.log('[socialSharing] initializing', { url: customURL });
                $('.Demo1').socialSharingPlugin({
                    url: customURL,
                    title: $('meta[property="og:title"]').attr('content'),
                    description: $('meta[property="og:description"]').attr('content'),
                    img: $('meta[property="og:image"]').attr('content'),
                    enable: ['whatsapp', 'facebook', 'twitter', 'pinterest', 'linkedin']
                });

                $('.socialShareButton').click(function(e) {
                    e.preventDefault();
                    $('.sharingButtonsContainer').toggle();
                });
            });
            @endif
        </script>
    @endpush
