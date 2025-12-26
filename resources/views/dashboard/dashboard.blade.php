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
        
        .dashboard-card {
            min-height: 200px;
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .analytics-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .analytics-card .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            color: white;
        }
        
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
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
                <!-- Welcome Card -->
                <div class="col-lg-4 welcome-card">
                    <div class="border bg-light-success p-3 border-success rounded text-dark h-100 dashboard-card">
                        <div class="d-flex align-items-center mb-4">
                             {{-- //profile photo goes here --}}
                            <div>
                                <h5 class="mb-0">
                                    <span class="d-block" id="greetings"></span>
                                    <strong>{{ $users->name }}</strong>
                                </h5>
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
                
                <!-- Business QR Card -->
                @if ($businessData)
                    <div class="col-md-2">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h6 class="mb-0 text-center">{{ ucFirst($businessData->title) }}</h6>
                                <div class="mb-3 shareqrcode text-center"></div>
                                <div class="d-flex justify-content-between">
                                    <a href="#!" class="btn btn-sm btn-light-primary w-100 cp_link"
                                        data-link="{{ url('/' . $businessData->slug) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title=""
                                        data-bs-original-title="Click to copy business link">
                                        {{ 'Business Link' }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-copy ms-1">
                                            <rect x="9" y="9" width="13" height="13"
                                                rx="2" ry="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
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
                                
                                @php
                                    $plan = \App\Models\Plan::find($users->plan);
                                @endphp
                                @if($plan && $plan->enable_wallet == 'on')
                                    <div class="mt-2">
                                        <a href="{{ route('wallet.index') }}" 
                                           class="btn btn-sm btn-success w-100">
                                            <i class="fas fa-wallet"></i>
                                            {{ __('Manage Wallet') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Quick Stats Cards -->
                <div class="quick-stats">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-briefcase dash-micon"></i>
                            </div>
                            <h6 class="mb-3 mt-3">{{ __('Total Cards') }}</h6>
                            <h3 class="mb-0">{{ $total_bussiness }}</h3>
                            <small class="text-muted">{{ __('Active business cards') }}</small>
                        </div>
                    </div>
                    
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-clipboard-check dash-micon"></i>
                            </div>
                            <h6 class="mb-3 mt-3">{{ __('Total Appointments') }}</h6>
                            <h3 class="mb-0">{{ $total_app }}</h3>
                            <small class="text-muted">{{ __('Scheduled meetings') }}</small>
                        </div>
                    </div>
                    
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="theme-avtar bg-success">
                                <i class="ti ti-users dash-micon"></i>
                            </div>
                            <h6 class="mb-3 mt-3">{{ __('Total Admin') }}</h6>
                            <h3 class="mb-0">{{ $total_staff }}</h3>
                            <small class="text-muted">{{ __('Team members') }}</small>
                        </div>
                    </div>
                    
                    <div class="card dashboard-card analytics-card">
                        <div class="card-body">
                            <div class="theme-avtar bg-white text-primary">
                                <i class="ti ti-chart-bar dash-micon"></i>
                            </div>
                            <h6 class="mb-3 mt-3 text-white">{{ __('Tap Analytics') }}</h6>
                            <div class="d-flex gap-2">
                                <a href="{{ route('tap-analytics.user') }}" class="btn btn-sm btn-light w-100">
                                    <i class="ti ti-eye"></i>
                                    {{ __('View') }}
                                </a>
                                <a href="{{ route('tap-analytics.user') }}" class="btn btn-sm btn-outline-light w-100">
                                    <i class="ti ti-chart-bar"></i>
                                    {{ __('Analytics') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Section -->
                <div class="col-12">
                    <div class="row">
                        <!-- Appointments Chart -->
                        <div class="col-lg-6">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ __('Appointments') }}</h5>
                                        <span class="badge bg-primary">{{ __('Last 7 Days') }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($chartData['data']) && count(array_filter($chartData['data'])) > 0)
                                        <div id="apex-storedashborad" data-color="primary" data-height="280"></div>
                                    @else
                                        <div class="empty-state">
                                            <i class="ti ti-calendar-time"></i>
                                            <h6>{{ __('No appointments data') }}</h6>
                                            <p class="text-muted">{{ __('Appointment data will appear here once you have scheduled meetings.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Platform Chart -->
                        <div class="col-lg-6">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ __('Platform Analytics') }}</h5>
                                        <span class="badge bg-info">{{ __('Last 15 Days') }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($platformarray['data']) && count(array_filter($platformarray['data'])) > 0)
                                        <div id="user_platform-chart"></div>
                                    @else
                                        <div class="empty-state">
                                            <i class="ti ti-device-desktop"></i>
                                            <h6>{{ __('No platform data') }}</h6>
                                            <p class="text-muted">{{ __('Platform analytics will appear here once you have visitor data.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Browser Chart -->
                        <div class="col-lg-4">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ __('Browser Usage') }}</h5>
                                        <span class="badge bg-warning">{{ __('Last 15 Days') }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($browserarray['data']) && count(array_filter($browserarray['data'])) > 0)
                                        <div id="pie-storebrowser"></div>
                                    @else
                                        <div class="empty-state">
                                            <i class="ti ti-world"></i>
                                            <h6>{{ __('No browser data') }}</h6>
                                            <p class="text-muted">{{ __('Browser analytics will appear here once you have visitor data.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Device Chart -->
                        <div class="col-lg-4">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ __('Device Usage') }}</h5>
                                        <span class="badge bg-success">{{ __('Last 15 Days') }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($devicearray['data']) && count(array_filter($devicearray['data'])) > 0)
                                        <div id="pie-storedashborad"></div>
                                    @else
                                        <div class="empty-state">
                                            <i class="ti ti-device-mobile"></i>
                                            <h6>{{ __('No device data') }}</h6>
                                            <p class="text-muted">{{ __('Device analytics will appear here once you have visitor data.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Storage Status -->
                        @if (\Auth::user()->type == 'company')
                            <div class="col-lg-4">
                                <div class="card dashboard-card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">{{ __('Storage Status') }}</h5>
                                            <span class="badge bg-secondary">{{ $users->storage_limit . 'MB' }} / {{ $plan->storage_limit . 'MB' }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div id="device-chart"></div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="progress" style="height: 8px;">
                                                @php
                                                    $storage_percentage = ($users->storage_limit / $plan->storage_limit) * 100;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ min($storage_percentage, 100) }}%" 
                                                     aria-valuenow="{{ $storage_percentage }}" 
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted mt-2 d-block">
                                                {{ number_format($storage_percentage, 1) }}% of storage used
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <img src="{{ isset($qr_detail->image) ? $qr_path . '/' . $qr_detail->image : '' }}" id="image-buffers"
            style="display: none">
    @endsection

    @push('custom-scripts')
        <script src="{{ asset('custom/js/purpose.js') }}"></script>
        @if (isset($plan->enable_qr_code) && $plan->enable_qr_code == 'on')
            <script src="{{ asset('custom/js/jquery.qrcode.min.js') }}"></script>
        @else
            <script src="{{ asset('custom/js/jquery.qrcode.js') }}"></script>
            <script type="text/javascript" src="https://jeromeetienne.github.io/jquery-qrcode/src/qrcode.js"></script>
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
            (function() {
                var options = {
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    series: {!! json_encode($chartData['data']) !!},
                    xaxis: {
                        labels: {
                            format: "MMM",
                            style: {
                                colors: PurposeStyle.colors.gray[600],
                                fontSize: "14px",
                                fontFamily: PurposeStyle.fonts.base,
                                cssClass: "apexcharts-xaxis-label"
                            }
                        },
                        axisBorder: {
                            show: !1
                        },
                        axisTicks: {
                            show: !0,
                            borderType: "solid",
                            color: PurposeStyle.colors.gray[300],
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        },
                        type: "text",
                        categories: {!! json_encode($chartData['label']) !!}
                    },
                    yaxis: {
                        labels: {
                            style: {
                                color: PurposeStyle.colors.gray[600],
                                fontSize: "12px",
                                fontFamily: PurposeStyle.fonts.base
                            }
                        },
                        axisBorder: {
                            show: !1
                        },
                        axisTicks: {
                            show: !0,
                            borderType: "solid",
                            color: PurposeStyle.colors.gray[300],
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        }
                    },

                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        floating: true,
                        offsetY: -25,
                        offsetX: -5
                    },

                };
                var chart = new ApexCharts(document.querySelector("#apex-storedashborad"), options);
                chart.render();
            })();

            var options = {
                chart: {
                    height: 250,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: true,
                },
                series: {!! json_encode($devicearray['data']) !!},
                colors: ["#6fd943", '#ffa21d', '#FF3A6E', '#3ec9d6'],
                labels: {!! json_encode($devicearray['label']) !!},
                legend: {
                    show: true,
                    position: 'bottom',
                },
            };
            var chart = new ApexCharts(document.querySelector("#pie-storedashborad"), options);
            chart.render();

            var options = {
                chart: {
                    height: 250,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: true,
                },
                series: {!! json_encode($browserarray['data']) !!},
                colors: ["#6fd943", '#ffa21d', '#FF3A6E', '#3ec9d6'],
                labels: {!! json_encode($browserarray['label']) !!},
                legend: {
                    show: true,
                    position: 'bottom',
                },
            };
            var chart = new ApexCharts(document.querySelector("#pie-storebrowser"), options);
            chart.render();
        </script>
        <script>
            var WorkedHoursChart = (function() {
                var $chart = $('#user_platform-chart');

                function init($this) {
                    var options = {
                        chart: {
                            height: 250,
                            type: 'bar',
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false
                            },
                            shadow: {
                                enabled: false,
                            },

                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '30%',
                                borderRadius: 10,
                                dataLabels: {
                                    position: 'top',
                                },
                            }
                        },
                        stroke: {
                            show: true,
                            width: 1,
                            colors: ['#fff']
                        },
                        series: [{
                            name: 'Platform',
                            data: {!! json_encode($platformarray['data']) !!},
                        }],
                        xaxis: {
                            labels: {
                                // format: 'MMM',
                                style: {
                                    colors: PurposeStyle.colors.gray[600],
                                    fontSize: '14px',
                                    fontFamily: PurposeStyle.fonts.base,
                                    cssClass: 'apexcharts-xaxis-label',
                                },
                            },
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: true,
                                borderType: 'solid',
                                color: PurposeStyle.colors.gray[300],
                                height: 6,
                                offsetX: 0,
                                offsetY: 0
                            },
                            title: {
                                text: '{{ __('Platform') }}'
                            },
                            categories: {!! json_encode($platformarray['label']) !!},
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    color: PurposeStyle.colors.gray[600],
                                    fontSize: '12px',
                                    fontFamily: PurposeStyle.fonts.base,
                                },
                            },
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: true,
                                borderType: 'solid',
                                color: PurposeStyle.colors.gray[300],
                                height: 6,
                                offsetX: 0,
                                offsetY: 0
                            }
                        },
                        fill: {
                            type: 'solid',
                            opacity: 1

                        },
                        markers: {
                            size: 4,
                            opacity: 0.7,
                            strokeColor: "#fff",
                            strokeWidth: 3,
                            hover: {
                                size: 7,
                            }
                        },
                        grid: {
                            borderColor: PurposeStyle.colors.gray[300],
                            strokeDashArray: 5,
                        },
                        dataLabels: {
                            enabled: false
                        }
                    }
                    // Get data from data attributes
                    var dataset = $this.data().dataset,
                        labels = $this.data().labels,
                        color = $this.data().color,
                        height = $this.data().height,
                        type = $this.data().type;

                    // Inject synamic properties
                    // options.colors = [
                    //     PurposeStyle.colors.theme[color]
                    // ];
                    // options.markers.colors = [
                    //     PurposeStyle.colors.theme[color]
                    // ];
                    options.chart.height = height ? height : 350;
                    // Init chart
                    var chart = new ApexCharts($this[0], options);
                    // Draw chart
                    setTimeout(function() {
                        chart.render();
                    }, 300);
                }

                // Events
                if ($chart.length) {
                    $chart.each(function() {
                        init($(this));
                    });
                }
            })();
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
            (function() {
                var options = {
                    series: [{{ number_format($storage_limit, 2) }}],
                    chart: {
                        height: 350,
                        type: 'radialBar',
                        offsetY: -20,
                        sparkline: {
                            enabled: true
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -90,
                            endAngle: 90,
                            track: {
                                background: "#e7e7e7",
                                strokeWidth: '97%',
                                margin: 5, // margin is in pixels
                            },
                            dataLabels: {
                                name: {
                                    show: true
                                },
                                value: {
                                    offsetY: -50,
                                    fontSize: '20px'
                                }
                            }
                        }
                    },
                    grid: {
                        padding: {
                            top: -10
                        }
                    },
                    colors: ["#6FD943"],
                    labels: ['Used'],
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

                    $(`.qr-link`).text(url_link);
                    @if (isset($plan->enable_qr_code) && $plan->enable_qr_code == 'on')
                        var foreground_color =
                            `{{ isset($qr_detail->foreground_color) ? $qr_detail->foreground_color : '#000000' }}`;
                        var background_color =
                            `{{ isset($qr_detail->background_color) ? $qr_detail->background_color : '#ffffff' }}`;
                        var radius = `{{ isset($qr_detail->radius) ? $qr_detail->radius : 26 }}`;
                        var qr_type = `{{ isset($qr_detail->qr_type) ? $qr_detail->qr_type : 0 }}`;
                        var qr_font = `{{ isset($qr_detail->qr_text) ? $qr_detail->qr_text : 'vCard' }}`;
                        var qr_font_color =
                            `{{ isset($qr_detail->qr_text_color) ? $qr_detail->qr_text_color : '#f50a0a' }}`;
                        var size = `{{ isset($qr_detail->size) ? $qr_detail->size : 9 }}`;

                        $('.shareqrcode').empty().qrcode({
                            render: 'image',
                            size: 500,
                            ecLevel: 'H',
                            minVersion: 3,
                            quiet: 1,
                            text: url_link,
                            fill: foreground_color,
                            background: background_color,
                            radius: .01 * parseInt(radius, 10),
                            mode: parseInt(qr_type, 10),
                            label: qr_font,
                            fontcolor: qr_font_color,
                            image: $("#image-buffers")[0],
                            mSize: .01 * parseInt(size, 10)
                        });
                    @else
                        $('.shareqrcode').qrcode(url_link);
                    @endif
                @endif
            });
        </script>
        <script>
            // Get user's timezone automatically
            function getUserTimezone() {
                try {
                    return Intl.DateTimeFormat().resolvedOptions().timeZone;
                } catch (e) {
                    // Fallback to server timezone or UTC
                    return '{{ !empty(env('APP_TIMEZONE')) ? env('APP_TIMEZONE') : 'UTC' }}';
                }
            }

            // Update greeting based on user's local time
            function updateGreeting() {
                var target = document.getElementById("greetings");
                if (!target) return;

                // Get current time in user's timezone
                var now = new Date();
                var curHr = now.getHours();
                
                // Determine appropriate greeting with translations
                var greeting = "";
                if (curHr >= 5 && curHr < 12) {
                    greeting = "{{ __('Good Morning,') }}";
                } else if (curHr >= 12 && curHr < 17) {
                    greeting = "{{ __('Good Afternoon,') }}";
                } else if (curHr >= 17 && curHr < 22) {
                    greeting = "{{ __('Good Evening,') }}";
                } else {
                    greeting = "{{ __('Good Night,') }}";
                }
                
                target.innerHTML = greeting;
            }

            // Update greeting immediately and set up periodic updates
            updateGreeting();
            
            // Update greeting every minute to handle time changes
            setInterval(updateGreeting, 60000);
        </script>

        <script type="text/javascript">
        @if ($businessData)
            $(document).ready(function() {
                var customURL = {!! json_encode(url('/' . $businessData->slug)) !!};
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
