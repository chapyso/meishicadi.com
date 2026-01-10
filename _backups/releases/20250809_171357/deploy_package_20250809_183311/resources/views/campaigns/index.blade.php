@extends('layouts.admin')
@php
    $cardLogo = \App\Models\Utility::get_file('card_logo');
@endphp
@section('page-title')
    {{ __('Campaigns') }}
@endsection
@section('title')
    {{ __('Campaigns') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Campaigns') }}</li>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Campaigns') }}</li>
@endsection
@section('action-btn')
    @if (Auth::user()->type == 'company')
        <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
            data-bs-placement="top">
            <a href="#" data-size="lg" data-url="{{ route('campaigns.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create New Campaigns') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endif
@endsection
@section('content')
    <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
        <div class=" mt-2 " id="multiCollapseExample1" style="">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['campaigns.index'], 'method' => 'get', 'id' => 'cam_filter']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        @if (Auth::user()->type == 'super admin')
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                <div class="btn-box">
                                    {{ Form::label('user', __('User'), ['class' => 'form-label']) }}
                                    {{ Form::select('user', $userList, isset($_GET['user']) ? $_GET['user'] : '', ['class' => 'form-control select ', 'id' => 'user_id']) }}
                                </div>
                            </div>
                        @endif
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('cat_type', __('Category'), ['class' => 'form-label']) }}
                                {{ Form::select('cat_type', $catList, isset($_GET['cat_type']) ? $_GET['cat_type'] : '', ['class' => 'form-control select ', 'id' => 'user_id']) }}
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('business', __('Business'), ['class' => 'form-label']) }}
                                {{ Form::select('business', $businessList, isset($_GET['business']) ? $_GET['business'] : '', ['class' => 'form-control select ', 'id' => 'user_id']) }}
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('start_date', __('Start date'), ['class' => 'form-label']) }}
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ isset($_GET['start_date']) ? $_GET['start_date'] : '' }}" placeholder ="">
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('end_date', __('End date'), ['class' => 'form-label']) }}
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ isset($_GET['end_date']) ? $_GET['end_date'] : '' }}" placeholder ="">
                            </div>
                        </div>

                        <div class="col-auto float-end ms-2 mt-4">
                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('cam_filter').submit(); return false;"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('campaigns.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                title="" data-bs-original-title="Reset">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style ">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <th>{{ __('Campaign Name') }} </th>
                            <th>{{ __('User') }} </th>
                            <th>{{ __('Category') }} </th>
                            <th>{{ __('Business Name') }} </th>
                            <th>{{ __('Start date') }} </th>
                            <th>{{ __('End Date') }} </th>
                            <th>{{ __('Total Days') }} </th>
                            <th>{{ __('Total Amount') }} </th>
                            <th>{{ __('Payment Method') }} </th>
                            <th>{{ __('Status') }} </th>

                            <th width="200px">{{ __('Action') }} </th>

                        </thead>
                        <tbody>
                            @foreach ($campaignsData as $campaigns)
                                <tr>
                                    <td>{{ ucfirst($campaigns->name) }}</td>
                                    <td>{{ ucfirst($campaigns->users->name) }}</td>
                                    <td>{{ $campaigns->categories->name }}</td>
                                    <td>{{ $campaigns->businesses->title }}</td>
                                    <td>{{ $campaigns->start_date }}</td>
                                    <td>{{ $campaigns->end_date }}</td>
                                    <td>{{ $campaigns->total_days }}</td>
                                    <td>{{ $campaigns->total_cost }}</td>
                                    <td>{{ $campaigns->payment_method }}</td>
                                    <td>
                                        <span
                                            class="badge fix_badge 
                                            @if ($campaigns->status == 0) bg-danger 
                                            @elseif ($campaigns->status == 1) bg-info 
                                            @elseif ($campaigns->status == 2) bg-warning 
                                            @elseif ($campaigns->status == 3) bg-secondary @endif 
                                            p-2 px-3 rounded">
                                            {{ \App\Models\Campaigns::$statuses[$campaigns->status] }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-btn-wrp d-flex align-items-center gap-1">
                                            @if (Auth::user()->type != 'company')
                                                @if ($campaigns->approval == 1)
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="checkbox" name="campaigns_status"
                                                            class="form-check-input input-primary campaigns_status_active"
                                                            value="1" data-id="{{ $campaigns->id }}"
                                                            data-name="campaigns"
                                                            {{ $campaigns->status == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="campaigns_status"></label>
                                                    </div>
                                                @endif
                                                @if (is_null($campaigns->approval) && !is_null($campaigns->total_cost))
                                                    <div class="action-btn bg-warning ">
                                                        <a href="#" class="btn btn-sm  btn-icon ml-0 "
                                                            data-url="{{ route('view.status.campaigns', $campaigns->id) }}"
                                                            data-size="md" data-ajax-popup="true"
                                                            data-title="{{ __('Change Status') }}"
                                                            title="{{ __('Status') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <span class="text-white"><i
                                                                    class="ti ti-caret-right text-white"></i></span></a>
                                                    </div>
                                                @endif
                                                <div class="action-btn bg-primary  ms-2">
                                                    <a class="btn btn-sm  btn-icon ml-0" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom"
                                                        data-bs-original-title="{{ __('Preview') }}"
                                                        href="{{ url('/' . $campaigns->businesses->slug) }}"
                                                        target="-blank"><span class="text-white"><i
                                                                class="ti ti-eye"></i></span></a>
                                                </div>
                                            @endif
                                            <div class="action-btn bg-info  ">
                                                <a href="{{ route('campaigns.business.analytics', $campaigns->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Analytics') }}"> <span
                                                        class="text-white"> <i
                                                            class="ti ti-brand-google-analytics  text-white"></i></span></a>
                                            </div>

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
    <script>
        $(document).on("click", ".campaigns_status_active", function() {
            var id = $(this).attr('data-id');
            var is_disable = ($(this).is(':checked')) ? $(this).val() : 0;

            $.ajax({
                url: '{{ route('campaigns.enable') }}',
                type: 'POST',
                data: {
                    "is_disable": is_disable,
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.is_success == true) {
                        toastrs('{{ __('Success') }}', data.msg, 'success');
                    } else if (data.is_success == false) {
                        toastrs('{{ __('Error') }}', data.msg, 'error');
                        $('.campaigns_status_active[data-id="' + id + '"]').prop('checked', !
                            is_disable);
                    }
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                }
            });
        });
    </script>
@endpush
