@extends('layouts.admin')
@push('css-page')

@endpush
@section('page-title')
   {{__('Dashboard')}}
@endsection
@section('title')
   {{__('Dashboard')}}
@endsection
@push('custom-scripts')
<script src="{{ asset('custom/js/purpose.js')}}"></script>
    <script>

        var e = $("#chart-sales");
        !function (e) {
            var t = {
                chart: { 
                    width: "100%",
                    zoom: {enabled: !1},
                    toolbar: {show: !1}, 
                    shadow: {enabled: !1}
                },
                stroke: {
                    width: 6,
                    curve: "smooth"
                },
                series: [{
                    name: "{{__('Order')}}", 
                    data: {!! json_encode($chartData['data']) !!}
                }],
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
                fill: {
                    type: "solid"
                },
                markers: {
                    size: 4, 
                    opacity: .7, 
                    strokeColor: "#fff", 
                    strokeWidth: 3, 
                    hover: {
                        size: 7
                    }
                },
                grid: {
                    borderColor: PurposeStyle.colors.gray[300], 
                    strokeDashArray: 5
                },
                dataLabels: {
                    enabled: !1
                }
            }, 
            a = (
                e.data().dataset, e.data().labels, e.data().color), 
                n = e.data().height, o = e.data().type;
            t.colors = [
                PurposeStyle.colors.theme[a]
            ], 
            t.markers.colors = [
                PurposeStyle.colors.theme[a]
            ], t.chart.height = n || 350, t.chart.type = o || "line";
            // Safely create chart only when ApexCharts is available
            try {
                if (typeof ApexCharts !== 'undefined' && e && e[0]) {
                    var i = new ApexCharts(e[0], t);
                    // Don't render here; the main renderer below handles it
                } else {
                    console.warn('ApexCharts not ready yet, deferring initialization.');
                }
            } catch (err) {
                console.error('Apex config error:', err);
            }
        }($("#chart-sales"));
    </script>
@endpush
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xxl-12">
                <div class="row">
                    <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body" style="min-height: 230px;">
                                <div class="theme-avtar bg-success">
                                    <i class="ti ti-user dash-micon"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2">{{__('Total Users')}}</p>
                                <h3 class="mb-0 mt-3">{{$user->total_user}}</h3>

                                <h6 class="mb-0 mt-2">{{__('PAID USERS')}} : <span class="text-success text-sm ">{{$user['total_paid_user']}}</span></h6>                    
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body" style="min-height: 230px;">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2">{{__('Total Orders')}}</p>
                                <h3 class="mb-0 mt-3">{{$user->total_orders}}</h3>

                                <h6 class="mb-0 mt-2">{{__('Total Order Amount')}} : <span class="text-warning text-sm ">{{$user['total_orders_price']}}</span></h6>                    
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 col-12 ">
                        <div class="card">
                            <div class="card-body" style="min-height: 230px;">
                                <div class="theme-avtar bg-secondary">
                                    <i class="ti ti-trophy"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2">{{__('Total Plans')}}</p>
                                <h3 class="mb-0 mt-3">{{$user['total_plan']}}</h3>

                                <h6 class="mb-0 mt-2">{{__('Most Purchase Plan')}} : <span class="text-primary text-sm ">{{$user['most_purchese_plan']}}</span></h6>                    
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body" style="min-height: 230px;">
                                <div class="theme-avtar bg-info">
                                    <i class="ti ti-refresh"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2">{{__('Pending Renewals')}}</p>
                                <h3 class="mb-0 mt-3">{{$user['pending_expired_requests']}}</h3>

                                <h6 class="mb-0 mt-2">
                                    <a href="{{ route('plan_request.index') }}" class="text-info text-sm">
                                        {{__('View Requests')}} <i class="ti ti-arrow-right"></i>
                                    </a>
                                </h6>                    
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-12 ">
                        <div class="card">
                        <div class="card-header">
                            <h5>{{__('Recent Order')}}</h5>
                        </div>
                        <div class="card-body">
                            <div id="chart-sales"></div>
                        </div>
                    </div>
                    </div>
                </div>
                
                <!-- Recent Pending Expired Plan Requests -->
                @if($user['pending_expired_requests'] > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">
                                        <i class="ti ti-alert-triangle text-warning me-2"></i>
                                        {{__('Recent Pending Plan Renewal Requests')}}
                                    </h5>
                                    <a href="{{ route('plan_request.index') }}" class="btn btn-sm btn-primary">
                                        {{__('View All')}}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{__('User')}}</th>
                                                <th>{{__('Requested Plan')}}</th>
                                                <th>{{__('Request Date')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user['recent_expired_requests'] as $request)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="theme-avtar bg-secondary me-2" style="width: 30px; height: 30px;">
                                                            <i class="ti ti-user" style="font-size: 0.8rem;"></i>
                                                        </div>
                                                        <div>
                                                            <div class="font-weight-bold">{{ $request->user ? $request->user->name : 'User Deleted' }}</div>
                                                            <small class="text-muted">{{ $request->user ? $request->user->email : 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $request->plan->name }}</div>
                                                    <small class="text-muted">
                                                        {{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $request->plan->price }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">{{ \App\Models\Utility::getDateFormated($request->created_at, true) }}</div>
                                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('response.request', [$request->id, 1]) }}" 
                                                           class="btn btn-sm btn-success" 
                                                           data-bs-toggle="tooltip" 
                                                           data-bs-original-title="{{__('Approve')}}">
                                                            <i class="ti ti-check"></i>
                                                        </a>
                                                        <a href="{{ route('response.request', [$request->id, 0]) }}" 
                                                           class="btn btn-sm btn-danger" 
                                                           data-bs-toggle="tooltip" 
                                                           data-bs-original-title="{{__('Reject')}}">
                                                            <i class="ti ti-x"></i>
                                                        </a>
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
                </div>
                @endif
                
            </div>
        </div>
    </div>  
</div>
@endsection 
@push('custom-scripts')
<script src="{{ asset('custom/js/purpose.js') }}"></script>
<script type="text/javascript">
     (function () {
         // Ensure ApexCharts is loaded before rendering to avoid runtime errors
         function start() {
             if (typeof ApexCharts === 'undefined') {
                 return setTimeout(start, 50);
             }
             var options = {
             series: [{
                 name: 'Order',
                //  data: [31, 40, 28, 51, 42, 109, 100]
                data : {!! json_encode($chartData['data']) !!}
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    }
                },
                
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'datetime',
                    categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        };
        try {
            var el = document.querySelector("#chart-sales");
            if (el) {
                var chart = new ApexCharts(el, options);
                chart.render();
            }
        } catch (err) {
            console.error('Apex render error:', err);
        }
     }
     start();
    })();
 </script> 
@endpush




