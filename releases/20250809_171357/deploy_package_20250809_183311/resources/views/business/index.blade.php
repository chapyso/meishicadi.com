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
        /* Modern Card Styling */
        .modern-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }
        
        .card-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
            border: none;
        }
        
        /* Modern Search Bar */
        .search-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }
        
        .search-input-modern {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .search-input-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .search-btn-modern {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            background: white;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .search-btn-modern:hover {
            background: #f8f9fa;
            border-color: #667eea;
            color: #667eea;
        }
        
        /* Modern Table Styling */
        .table-modern {
            border-radius: 12px;
            overflow: hidden;
            border: none;
        }
        
        .table-modern thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            padding: 16px 12px;
            font-weight: 600;
            color: #495057;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .table-modern tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            transform: scale(1.01);
        }
        
        .table-modern tbody td {
            padding: 16px 12px;
            vertical-align: middle;
            border: none;
        }
        
        /* Modern Avatar Styling */
        .avatar-modern {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        
        .avatar-modern:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }
        
        /* Modern Status Badge */
        .status-badge-modern {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .status-active {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .status-locked {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        
        /* Clickable Status Button */
        .status-toggle-btn {
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .status-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .status-toggle-btn:hover::before {
            left: 100%;
        }
        
        .status-toggle-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .status-toggle-btn:active {
            transform: scale(0.95);
        }
        
        /* Loading state for status button */
        .status-toggle-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        /* Success message styling */
        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }
        
        .alert-error {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }
        
        /* Modern Action Buttons */
        .action-btn-modern {
            border-radius: 6px;
            padding: 6px 8px;
            margin: 0 2px;
            transition: all 0.2s ease;
            border: none;
            font-size: 11px;
            min-width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .action-btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-btn-modern:hover::before {
            left: 100%;
        }
        
        .action-btn-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }
        
        .action-btn-modern:active {
            transform: translateY(0);
        }
        
        .action-btn-modern.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }
        
        .action-btn-modern.bg-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%) !important;
        }
        
        .action-btn-modern.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
        }
        
        .action-btn-modern.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%) !important;
        }
        
        .action-btn-modern.bg-dark {
            background: linear-gradient(135deg, #343a40 0%, #495057 100%) !important;
        }
        
        /* Action Button Container */
        .action-buttons-container {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            justify-content: flex-end;
            align-items: center;
            min-height: 36px;
            padding: 2px;
        }
        
        /* Responsive button layout */
        @media (max-width: 1200px) {
            .action-buttons-container {
                gap: 2px;
            }
            
            .action-btn-modern {
                min-width: 28px;
                height: 28px;
                padding: 4px 6px;
                font-size: 10px;
            }
        }
        
        /* Button Group Styling */
        .btn-group-modern {
            display: inline-flex;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .btn-group-modern .action-btn-modern {
            border-radius: 0;
            margin: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-group-modern .action-btn-modern:last-child {
            border-right: none;
        }
        
        /* Tap Counter Styling */
        .tap-counter-btn {
            transition: all 0.2s ease;
        }
        
        .tap-counter-btn:hover {
            transform: scale(1.02);
        }
        
        .tap-count-number {
            font-weight: 600;
            font-size: 10px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 1px 4px;
            margin-left: 2px;
        }
        
        /* Button animation on load */
        .action-btn-modern {
            animation: fadeInUp 0.3s ease forwards;
            opacity: 0;
            transform: translateY(10px);
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Stagger animation for buttons */
        .action-btn-modern:nth-child(1) { animation-delay: 0.1s; }
        .action-btn-modern:nth-child(2) { animation-delay: 0.2s; }
        .action-btn-modern:nth-child(3) { animation-delay: 0.3s; }
        .action-btn-modern:nth-child(4) { animation-delay: 0.4s; }
        .action-btn-modern:nth-child(5) { animation-delay: 0.5s; }
        .action-btn-modern:nth-child(6) { animation-delay: 0.6s; }
        .action-btn-modern:nth-child(7) { animation-delay: 0.7s; }
        
        /* Business Name Link */
        .business-name-link {
            color: #495057;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .business-name-link:hover {
            color: #667eea;
            text-decoration: none;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .search-container {
                padding: 1rem;
            }
            
            .table-modern thead th,
            .table-modern tbody td {
                padding: 12px 8px;
                font-size: 13px;
            }
            
            .action-btn-modern {
                padding: 6px 8px;
                margin: 0 2px;
            }
        }
        
        /* Loading Animation */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
    <div class="col-xl-12">
        <div class="card modern-card">
            <div class="card-header card-header-modern">
                <h4 class="mb-0"><i class="ti ti-building me-2"></i>{{ __('Business Management') }}</h4>
                <p class="mb-0 opacity-75">{{ __('Manage and monitor your business cards') }}</p>
            </div>
            <div class="card-body">
                
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Modern Search Bar -->
                <div class="search-container">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="ti ti-search text-muted"></i>
                                </span>
                                <input type="text" id="businessSearch" class="form-control search-input-modern" 
                                       placeholder="{{ __('Search by business name...') }}" 
                                       aria-label="{{ __('Search businesses') }}">
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <span class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                {{ __('Found') }} <span id="resultCount">{{ count($business) }}</span> {{ __('businesses') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-modern" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th><i class="ti ti-hash me-1"></i>{{ __('Number') }}</th>
                                <th><i class="ti ti-user me-1"></i>{{ __('Profile Photo') }}</th>
                                <th><i class="ti ti-building me-1"></i>{{ __('Name') }}</th>
                                <th><i class="ti ti-circle-check me-1"></i>{{ __('Status') }}</th>
                                <th><i class="ti ti-calendar me-1"></i>{{ __('Generated Date') }}</th>
                                <th class="text-end"><i class="ti ti-settings me-1"></i>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($business as $val)
                                <tr class="{{ $val->admin_enable == 'off' ? 'row-disabled' : '' }}" >
                                    <td>
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">{{ $loop->index + 1 }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="avatar-modern"
                                                src="{{ isset($val->logo) && !empty($val->logo) ? $cardLogo . '/' . $val->logo : asset('custom/img/logo-placeholder-image-21.png') }}"
                                                alt="{{ ucFirst($val->title) }}"
                                                title="{{ ucFirst($val->title) }}">
                                        </div>
                                    </td>

                                    <td>
                                        @can('manage business')
                                            <a class="business-name-link"
                                                href="{{ route('business.edit', $val->id) }}">
                                                <strong>{{ ucFirst($val->title) }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="ti ti-calendar me-1"></i>
                                                    {{ $val->created_at->format('M d, Y') }}
                                                </small>
                                            </a>
                                        @else
                                            <span class="text-muted">
                                                <strong>{{ ucFirst($val->title) }}</strong>
                                                <br>
                                                <small>
                                                    <i class="ti ti-calendar me-1"></i>
                                                    {{ $val->created_at->format('M d, Y') }}
                                                </small>
                                            </span>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('manage business')
                                            <form method="POST" action="{{ route('business.status', $val->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="status-badge-modern @if ($val->status == 'locked') status-locked @else status-active @endif status-toggle-btn" 
                                                        data-business-id="{{ $val->id }}"
                                                        data-current-status="{{ $val->status }}"
                                                        title="{{ $val->status == 'locked' ? __('Click to activate') : __('Click to deactivate') }}">
                                                    <i class="ti @if ($val->status == 'locked') ti-lock @else ti-check @endif me-1"></i>
                                                    {{ ucFirst($val->status) }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="status-badge-modern @if ($val->status == 'locked') status-locked @else status-active @endif">
                                                <i class="ti @if ($val->status == 'locked') ti-lock @else ti-check @endif me-1"></i>
                                                {{ ucFirst($val->status) }}
                                            </span>
                                        @endcan
                                    </td>
                                    @php
                                        $now = $val->created_at;
                                        $date = $now->format('Y-m-d');
                                        $time = $now->format('H:i:s');
                                    @endphp
                                    <td>{{ $val->created_at }}</td>

                                    <td class="text-end">
                                        <div class="action-buttons-container">
                                        @if ($val->status != 'locked')
                                            <div class="action-btn-modern bg-success">
                                                <a href="#"
                                                    class="d-inline-flex align-items-center justify-content-center cp_link"
                                                    data-link="{{ url('/' . $val->slug) }}" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Click to copy card link') }}"> 
                                                    <i class="ti ti-copy text-white"></i>
                                                </a>
                                            </div>
                                            @can('view analytics business')
                                                <div class="action-btn-modern bg-info">
                                                    <a href="{{ route('business.analytics', $val->id) }}"
                                                        class="d-inline-flex align-items-center justify-content-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Analytics') }}"> 
                                                        <i class="ti ti-brand-google-analytics text-white"></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn-modern bg-dark">
                                                    <a href="#" 
                                                        class="d-inline-flex align-items-center justify-content-center tap-counter-btn"
                                                        data-business-id="{{ $val->id }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Tap Counter') }}"> 
                                                        <i class="ti ti-tap text-white"></i> 
                                                        <span class="tap-count-number ms-1 text-white">0</span>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('calendar appointment')
                                                <div class="action-btn-modern bg-warning">
                                                    <a href="{{ route('appointment.calendar', $val->id) }}"
                                                        class="d-inline-flex align-items-center justify-content-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Calendar') }}"> 
                                                        <i class="ti ti-calendar text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('manage business')
                                                <div class="action-btn-modern bg-info">
                                                    <a href="{{ route('business.edit', $val->id) }}"
                                                        class="d-inline-flex align-items-center justify-content-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Edit') }}"> 
                                                        <i class="ti ti-edit text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('manage contact')
                                                <div class="action-btn-modern bg-warning">
                                                    <a href="{{ route('business.contacts.show', $val->id) }}"
                                                        class="d-inline-flex align-items-center justify-content-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Contacts') }}"> 
                                                        <i class="ti ti-phone text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('create business')
                                                @if(Auth::user()->type != 'super admin')
                                                    <div class="action-btn-modern bg-success">
                                                        <a href="{{ route('wallet.options', $val->id) }}"
                                                            class="d-inline-flex align-items-center justify-content-center"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Wallet Pass') }}"> 
                                                            <i class="ti ti-wallet text-white"></i>
                                                        </a>
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
                                                <div class="action-btn-modern bg-danger">
                                                    <a href="#"
                                                        class="bs-pass-para d-inline-flex align-items-center justify-content-center"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $val->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                </div>
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['business.destroy', $val->id],
                                                    'id' => 'delete-form-' . $val->id,
                                                ]) !!}
                                                {!! Form::close() !!}
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="ti ti-lock text-white"></i>
                                                </span>
                                            @endcan
                                        </div>
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

        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('{{ __('Success') }}', '{{ __('Link Copy on Clipboard') }}', 'success');
        });

        // Tap counter button click handler
        $('.tap-counter-btn').on('click', function(e) {
            e.preventDefault();
            var businessId = $(this).data('business-id');
            var tapCount = $(this).find('.tap-count-number').text();
            
            // Show tap count details
            toastrs('{{ __("Info") }}', '{{ __("Tap Count") }}: ' + tapCount + ' {{ __("taps") }}', 'info');
        });

        // Business search functionality
        $('#businessSearch').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();
            var table = $('#pc-dt-simple');
            var rows = table.find('tbody tr');
            var visibleCount = 0;
            
            rows.each(function() {
                var businessName = $(this).find('td:nth-child(3)').text().toLowerCase();
                if (businessName.includes(searchTerm)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });
            
            // Update row numbers for visible rows
            updateRowNumbers();
            
            // Update result count
            $('#resultCount').text(visibleCount);
        });



        // Function to update row numbers
        function updateRowNumbers() {
            var visibleRows = $('#pc-dt-simple tbody tr:visible');
            visibleRows.each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }
        
        // Status toggle functionality
        $('.status-toggle-btn').on('click', function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var businessId = $btn.data('business-id');
            var currentStatus = $btn.data('current-status');
            var newStatus = currentStatus === 'locked' ? 'active' : 'locked';
            var isActivation = currentStatus === 'locked';
            var confirmMessage = isActivation 
                ? '{{ __("Are you sure you want to activate this business?") }}'
                : '{{ __("Are you sure you want to deactivate this business? This will show an error page to visitors.") }}';
            
            // Function to handle form submission
            function submitForm() {
                // Show loading state
                $btn.prop('disabled', true);
                $btn.html('<i class="ti ti-loader ti-spin me-1"></i>{{ __("Updating...") }}');
                
                // Submit the form
                $btn.closest('form').submit();
            }
            
            // Try to use modern notification, fallback to browser confirm
            try {
                if (typeof ModernNotification !== 'undefined' && ModernNotification.show) {
                    ModernNotification.show({
                        title: isActivation ? '{{ __("Activate Business") }}' : '{{ __("Deactivate Business") }}',
                        message: confirmMessage,
                        type: isActivation ? 'success' : 'warning',
                        confirmText: isActivation ? '{{ __("Activate") }}' : '{{ __("Deactivate") }}',
                        confirmType: isActivation ? 'success' : 'danger'
                    }).then(function(confirmed) {
                        if (confirmed) {
                            submitForm();
                        }
                    }).catch(function(error) {
                        console.error('Modern notification error:', error);
                        // Fallback to browser confirm
                        if (confirm(confirmMessage)) {
                            submitForm();
                        }
                    });
                } else {
                    // Fallback to browser confirm
                    if (confirm(confirmMessage)) {
                        submitForm();
                    }
                }
            } catch (error) {
                console.error('Error in status toggle:', error);
                // Final fallback to browser confirm
                if (confirm(confirmMessage)) {
                    submitForm();
                }
            }
        });
        });
    </script>
@endpush
