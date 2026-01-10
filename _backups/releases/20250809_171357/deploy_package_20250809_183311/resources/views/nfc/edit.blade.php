@extends('layouts.admin')

@section('page-title')
    {{ __('Edit NFC Card') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('nfc.index') }}">{{ __('NFC Cards') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Edit NFC Card') }}</h5>
                </div>
                <div class="card-body">
                    {{ Form::model($nfcCard, ['route' => ['nfc.update', $nfcCard->id], 'method' => 'PUT']) }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('card_number', __('Card Number'), ['class' => 'form-label']) }}
                                {{ Form::text('card_number', null, ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
                                <a href="{{ route('nfc.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
