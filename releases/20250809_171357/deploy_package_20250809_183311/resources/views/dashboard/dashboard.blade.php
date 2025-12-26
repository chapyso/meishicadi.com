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
            padding: 10px 10px;
        }
        .shareqrcode canvas {
            width: 65%;
            height: 65%;
            padding: 10px 10px;
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
                <div class="col-md-10 mb-3 mb-md-0">
                    <h5 class="h3 font-weight-400 mb-0">{{ __('Dashboard') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-4 welcome-card">
                    <div class="border bg-light-success p-3 border-success rounded text-dark h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-2">
                                <img class="theme-avtar"
                                    src="{{ !empty($users->avatar) ? $profile . '/' . $users->avatar : $profile . '/avatar.png' }}" />
                            </div>
                            <div>
                                <h5 class="mb-0">
                                    <span class="d-block" id="greetings">{{ __('Good Afternoon,') }}</span>
                                    <b class="f-w-700">{{ __(Auth::user()->name) }}</b>
                                </h5>
                            </div>
                        </div>
                        <p class="mb-0">
                            {{ __('Have a nice day! Did you know that you can quickly add your favorite card to the business?') }}
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
                                @can('create business')
                                <a href="#" data-size="xl" data-url="{{ route('business.create') }}"
                                    data-ajax-popup="true" data-title="Create New Business" class="dropdown-item"
                                    data-bs-placement="top ">
                                    <span>{{ __('Add new Business') }}</span>
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
                                <h6 class="mb-0">{{ ucFirst($businessData->title) }}</h6>
                                <div class="shareqrcode"></div>
                                <a href="#!" class="btn btn-light-primary w-100 cp_link"
                                    data-link="{{ url('/' . $businessData->slug) }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title=""
                                    data-bs-original-title="Click to copy business link">
                                    {{ 'Business Link' }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy ms-3">
                                        <rect x="9" y="9" width="13" height="13" rx="2"
                                            ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                </a>
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
                            <h6 class="mb-3">{{ __('Total Business') }}</h6>
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
                            <h6 class="mb-3">{{ __('Total Appointments') }}</h6>
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
                                                <h6 class="mb-3">{{ __('Total Clients') }}</h6>
                    <h3 class="mb-0">{{ $total_staff }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-end">
                                <span class="mb-0 float-right">{{ __('Last 7 Days') }}</span>
                            </div>
                            <h5>{{ __('Appointments') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="apex-storedashborad" data-color="primary" data-height="280"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-end">
                                <span class="mb-0 text-sm float-right mt-1">{{ __('Last 15 Days') }}</span>
                            </div>
                            <h5 class="mb-0 float-left">{{ __('Platform') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div id="user_platform-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-end">
                                <span class="mb-0 text-sm float-right mt-1">{{ __('Last 15 Days') }}</span>
                            </div>
                            <h5 class="mb-0 float-left">{{ __('Browser') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div id="pie-storebrowser"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-end">
                                <span class="mb-0 text-sm float-right mt-1">{{ __('Last 15 Days') }}</span>
                            </div>
                            <h5 class="mb-0 float-left">{{ __('Device') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div id="pie-storedashborad"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (\Auth::user()->type == 'company')
                    <div class="col-lg-4 ">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <span class="mb-0 text-sm float-right mt-1">{{ $users->storage_limit . 'MB' }} / {{ $plan->storage_limit . 'MB' }}</span>
                                </div>
                                <h5 class="mb-0 float-left">{{ __('Storage') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div id="device-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
                                fontFamily: PurposeStyle.colors.base,
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
                                fontFamily: PurposeStyle.colors.base
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
                                    fontFamily: PurposeStyle.colors.base,
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
                                    fontFamily: PurposeStyle.colors.base,
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
    @endpush
