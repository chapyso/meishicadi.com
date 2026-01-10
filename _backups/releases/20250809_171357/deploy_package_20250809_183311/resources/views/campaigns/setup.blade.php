@extends('layouts.admin')
@php
    $cardLogo = \App\Models\Utility::get_file('card_logo');
@endphp
@section('page-title')
    {{ __('Manage Branch') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Business') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-3">
            @include('layouts.marketplace_setup')
        </div>
        <div class="col-9">
            {{--<div class="card">
                <div class="card-header">
                    <h5>{{ 'Business Settings' }}</h5>
                </div>
                {{ Form::open(['route' => 'campaigns.business.settings', 'method' => 'post']) }}
                <div class="card-body table-border-style">
                    <div class="row my-2">
                        @foreach ($businessDetails as $businessDetail)
                            <div class="col-md-6 my-2">
                                <div class="d-flex align-items-center justify-content-between list_colume_notifi pb-2">
                                    <div class="mb-3 mb-sm-0">
                                        <h6>
                                            <img style="width: 30px; height: 30px;" class="rounded-circle"
                                                src="{{ isset($businessDetail->logo) && !empty($businessDetail->logo) ? $cardLogo . '/' . $businessDetail->logo : asset('custom/img/logo-placeholder-image-21.png') }}"
                                                alt="">
                                            <a href="{{ url('/' . $businessDetail->slug) }}" target="_blank"
                                                class="{{ $businessDetail->directory_status == 'off' ? 'row-disabled' : '' }}"
                                                id="link_{{ $businessDetail->id }}">
                                                <label for="user"
                                                    class="form-label">{{ $businessDetail->title }}</label>
                                            </a>
                                        </h6>
                                    </div>
                                    <div class="text-end">
                                        <div class="form-check form-switch custom-switch-v1 mb-2">
                                            <input type="checkbox"
                                                name="businesses[{{ $businessDetail->id }}][directory_status]"
                                                class="form-check-input input-primary is_business" value="on"
                                                {{ $businessDetail->directory_status == 'on' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="user_disable"></label>
                                            <input type="hidden" name="businesses[{{ $businessDetail->id }}][id]"
                                                value="{{ $businessDetail->id }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-end">
                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-print-invoice btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>--}}

            <div class="card">
                <div class="card-header">
                    <h5>{{ 'Cost Per Day Settings' }}</h5>
                </div>
                {{ Form::open(['route' => 'wholesale.cost-setting', 'method' => 'post']) }}
                <div class="card-body table-border-style">
                    <div class="row my-2">
                        <div class="repeater">
                            <div class="col-lg-12 text-end">
                                <a data-repeater-create type="button" value="Add" class="submitbtn btn btn-sm btn-primary">
                                    <i class="ti ti-plus text-white"></i>
                                </a>
                            </div>
                            <div data-repeater-list="category_group" class="form-group">
                                @if (!empty($costDetail) && count($costDetail) > 0)
                                    @foreach ($costDetail as $cost)
                                        <div data-repeater-item class="row align-items-end py-2">
                                            <input type="hidden" name="id" class="cat_id" value="{{ $cost->id }}" />
                                            <div class="col-lg-3">
                                                {{ Form::label('min', __('Min Days')) }}
                                                <input class="dtpiker form-control" type="text" name="min" value="{{ $cost->min }}" />
                                            </div>
                                            <div class="col-lg-3">
                                                {{ Form::label('max', __('Max Days')) }}
                                                <input class="dtpiker form-control" type="text" name="max" value="{{ $cost->max }}" />
                                            </div>
                                            <div class="col-lg-3">
                                                {{ Form::label('price', __('Per Day Price')) }}
                                                <input class="dtpiker form-control" type="text" name="price" value="{{ $cost->price }}" />
                                            </div>
                                            <div class="col-lg-3">
                                                <a data-repeater-delete href="javascript:void(0)" class="btn btn-sm btn-danger mb-2">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div data-repeater-item class="row align-items-end py-2">
                                        <div class="col-lg-3">
                                            {{ Form::label('min', __('Min Days')) }}
                                            <input class="dtpiker form-control" type="text" name="min" value="" />
                                        </div>
                                        <div class="col-lg-3">
                                            {{ Form::label('max', __('Max Days')) }}
                                            <input class="dtpiker form-control" type="text" name="max" value="" />
                                        </div>
                                        <div class="col-lg-3">
                                            {{ Form::label('price', __('Per Day Price')) }}
                                            <input class="dtpiker form-control" type="text" name="price" value="" />
                                        </div>
                                        <div class="col-lg-3">
                                            <a data-repeater-delete href="javascript:void(0)" class="btn btn-sm btn-danger mb-2">
                                                <i class="ti ti-trash text-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <input type="hidden" name="deleted_ids" id="deleted_ids" value="">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-print-invoice btn-primary']) }}
                </div>
                {{ Form::close() }}

            </div>

        </div>

    </div>
@endsection
@push('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
    <script>
        $(document).ready(function () {
            var deletedIds = [];

            $('.repeater').repeater({
                initEmpty: {{ empty($costDetail) ? 'true' : 'false' }},
                defaultValues: {
                    'min': '',
                    'max': '',
                    'price': ''
                },
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    var id = $(this).find('.cat_id').val();
                    if (id) {
                        deletedIds.push(id);
                        $('#deleted_ids').val(deletedIds.join(','));
                    }
                    $(this).slideUp(deleteElement);
                },
                ready: function (setIndexes) {
                    // Optional callback when repeater is ready
                }
            });
        });
    </script>
@endpush
