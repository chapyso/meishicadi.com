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
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <h5></h5>
                {{-- Country Filter --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('business.index') }}" id="country-filter-form">
                            <label for="country_filter" class="form-label">{{ __('Filter by Country') }}</label>
                            <select name="country_filter" id="country_filter" class="form-select" onchange="document.getElementById('country-filter-form').submit();">
                                <option value="all" {{ (empty($selectedCountry) || $selectedCountry == 'all') ? 'selected' : '' }}>{{ __('All Countries') }}</option>
                                @if(isset($countriesList) && !empty($countriesList))
                                    @foreach($countriesList as $country)
                                        <option value="{{ $country }}" {{ ($selectedCountry == $country) ? 'selected' : '' }}>{{ $country }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th style="width: 60px;">{{ __('Number') }}</th>
                                <th style="width: 80px;">{{ __('Profile Photo') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th style="width: 120px;">{{ __('Status') }}</th>
                                <th style="width: 150px;">{{ __('Country') }}</th>
                                <th style="width: 180px;">{{ __('Generated Date') }}</th>
                                <th style="width: 100px;">{{ __('Taps') }}</th>
                                <th class="text-end" style="width: 300px;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($business as $val)
                                <tr class="{{ $val->admin_enable == 'off' ? 'row-disabled' : '' }}">
                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                    <td>
                                        <div class="avatar d-flex align-items-center justify-content-center">
                                            @php
                                                $logoUrl = '';
                                                if (isset($val->logo) && !empty($val->logo) && !empty($cardLogo)) {
                                                    // Normalize path to avoid double slashes
                                                    $logoFilename = ltrim($val->logo, '/');
                                                    $logoUrl = rtrim($cardLogo, '/') . '/' . $logoFilename;
                                                    // Normalize URL to remove any double slashes (except after protocol)
                                                    $logoUrl = preg_replace('#([^:])//+#', '$1/', $logoUrl);
                                                    // Verify file exists - check multiple possible locations
                                                    try {
                                                        // First check public disk
                                                        $fileExists = \Storage::disk('public')->exists('card_logo/' . $logoFilename);
                                                        
                                                        // If not found, check direct storage path
                                                        if (!$fileExists) {
                                                            $directPath = storage_path('card_logo/' . $logoFilename);
                                                            $fileExists = file_exists($directPath);
                                                            if ($fileExists) {
                                                                // File exists in direct storage, use asset URL with proper path
                                                                $logoUrl = asset('storage/card_logo/' . $logoFilename);
                                                            }
                                                        }
                                                        
                                                        // Also check storage/app/public path
                                                        if (!$fileExists) {
                                                            $publicPath = storage_path('app/public/card_logo/' . $logoFilename);
                                                            $fileExists = file_exists($publicPath);
                                                        }
                                                        
                                                        if (!$fileExists) {
                                                            $logoUrl = asset('custom/img/logo-placeholder-image-21.png');
                                                        }
                                                    } catch (\Exception $e) {
                                                        // Fallback: try multiple local file checks
                                                        $filePath1 = storage_path('card_logo/' . $logoFilename);
                                                        $filePath2 = storage_path('app/public/card_logo/' . $logoFilename);
                                                        
                                                        if (file_exists($filePath1)) {
                                                            $logoUrl = asset('storage/card_logo/' . $logoFilename);
                                                        } elseif (file_exists($filePath2)) {
                                                            // File exists in public storage
                                                        } else {
                                                            $logoUrl = asset('custom/img/logo-placeholder-image-21.png');
                                                        }
                                                    }
                                                } else {
                                                    $logoUrl = asset('custom/img/logo-placeholder-image-21.png');
                                                }
                                            @endphp
                                            <img style="width: 55px; height: 55px; object-fit: cover; border: 2px solid #e5e7eb;"
                                                class="rounded-circle"
                                                src="{{ $logoUrl }}"
                                                alt="{{ ucFirst($val->title) }}"
                                                onerror="this.onerror=null; this.src='{{ asset('custom/img/logo-placeholder-image-21.png') }}';">
                                        </div>
                                    </td>

                                    <td>
                                        <a href="{{ route('business.edit', $val->id) }}" class="text-dark text-decoration-none">
                                            <b>{{ ucFirst($val->title) }}</b>
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $status = strtolower($val->status ?? 'active');
                                            $statusClass = 'bg-info';
                                            if ($status == 'locked' || $status == 'lock') {
                                                $statusClass = 'bg-danger';
                                            } elseif ($status == 'active') {
                                                $statusClass = 'bg-success';
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }} p-2 px-3 rounded-pill text-white">
                                            {{ ucFirst($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(isset($val->country) && $val->country != 'Unknown')
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
                                            <i class="ti ti-cursor-text text-primary" style="font-size: 16px;"></i>
                                            <span class="fw-semibold">{{ $val->tap_count ?? 0 }}</span>
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-1 flex-wrap">
                                            @if ($val->status != 'lock' && $val->status != 'locked')
                                                <div class="action-btn bg-dark" style="border: 1px solid white;">
                                                    <a href="#"
                                                        class="bs-pass-para btn btn-sm d-inline-flex align-items-center justify-content-center"
                                                        data-confirm="{{ __('You want to confirm this action') }}"
                                                        data-text="{{ __('Press Yes to continue or No to go back') }}"
                                                        data-confirm-yes="duplicate-form-{{ $val->id }}"
                                                        title="{{ __('Duplicate') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <span class="text-white"><i class="ti ti-copy"></i></span>
                                                    </a>
                                                    {!! Form::open([
                                                        'method' => 'POST',
                                                        'route' => ['business.duplicate', $val->id],
                                                        'id' => 'duplicate-form-' . $val->id,
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
                                                            data-confirm-yes="delete-form-{{ $val->id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <span class="text-white"><i class="ti ti-trash"></i></span>
                                                        </a>
                                                    </div>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['business.destroy', $val->id],
                                                        'id' => 'delete-form-' . $val->id,
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <style>
        .action-btn {
            min-width: 36px;
            height: 36px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .action-btn .btn {
            width: 100%;
            height: 100%;
            padding: 0;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn .btn i {
            font-size: 16px;
        }

        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding: 12px 16px;
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.08);
        }

        .avatar img {
            transition: transform 0.2s ease;
        }

        .avatar img:hover {
            transform: scale(1.05);
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.3px;
            border: 1px solid #fff !important;
        }

        /* Pagination styling - white font for numbers */
        /* Target all possible simpleDatatables pagination elements */
        [data-pagination] *,
        [data-pagination] a,
        [data-pagination] button,
        [data-pagination] span,
        nav[data-pagination] *,
        nav[data-pagination] a,
        nav[data-pagination] button,
        nav[data-pagination] span,
        .datatable-pagination *,
        .datatable-pagination a,
        .datatable-pagination button,
        .datatable-pagination span,
        .datatable-pagination li,
        .datatable-pagination li *,
        .datatable-pagination-list,
        .datatable-pagination-list *,
        .datatable-pagination-list a,
        .datatable-pagination-list li,
        .datatable-pagination-list li a,
        .simple-datatables-pagination,
        .simple-datatables-pagination *,
        .simple-datatables-pagination a,
        .simple-datatables-pagination button,
        .simple-datatables-pagination span,
        .simple-datatables-pagination-wrapper,
        .simple-datatables-pagination-wrapper *,
        .simple-datatables-pagination-wrapper a,
        .dataTables_wrapper .dataTables_paginate *,
        .dataTables_wrapper .dataTables_paginate a,
        .dataTables_wrapper .dataTables_paginate span,
        .pagination,
        .pagination *,
        .pagination a,
        .pagination button,
        .pagination span,
        .pagination li,
        .pagination li * {
            color: #ffffff !important;
        }
        
        /* Pagination info text (Showing X to Y of Z entries) */
        .datatable-info,
        .datatable-info *,
        .dataTables_info,
        .dataTables_info *,
        #pc-dt-simple_info,
        .dataTables_wrapper .dataTables_info {
            color: #ffffff !important;
        }
        
        /* More specific selectors for simpleDatatables */
        .datatable-top,
        .datatable-bottom,
        .datatable-bottom *,
        .datatable-top * {
            color: #ffffff !important;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .action-btn {
                min-width: 32px;
                height: 32px;
            }
        }
    </style>
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
        
        // Force pagination numbers to be white
        $(document).ready(function() {
            function makePaginationWhite() {
                // Find all pagination elements and force white color
                $('[data-pagination]').find('*').css('color', '#ffffff');
                $('.datatable-bottom').find('*').css('color', '#ffffff');
                $('.datatable-top').find('*').css('color', '#ffffff');
                
                // Target any nav or list elements that contain numbers
                $('nav').find('a, button, span, li').css('color', '#ffffff');
                
                // Target pagination lists specifically
                $('.datatable-pagination, .datatable-pagination-list').find('*').css('color', '#ffffff');
                
                // Find elements containing numbers in pagination area
                $('.datatable-bottom').find('a, button, span, li').each(function() {
                    var text = $(this).text().trim();
                    // If it's a number or arrow, make it white
                    if (/^\d+$/.test(text) || text === '...' || text === '>' || text === '<' || text === '«' || text === '»') {
                        $(this).css('color', '#ffffff');
                        $(this).find('*').css('color', '#ffffff');
                    }
                });
            }
            
            // Run immediately
            makePaginationWhite();
            
            // Run after a short delay (in case pagination is rendered later)
            setTimeout(makePaginationWhite, 500);
            setTimeout(makePaginationWhite, 1000);
            setTimeout(makePaginationWhite, 2000);
            
            // Watch for DOM changes
            if (window.MutationObserver) {
                var observer = new MutationObserver(function(mutations) {
                    makePaginationWhite();
                });
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>
@endpush
