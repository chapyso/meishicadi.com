@extends('layouts.admin')

@section('page-title')
    {{ __('NFC Cards') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('NFC Cards') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Card Number') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nfcCards as $nfcCard)
                                    <tr>
                                        <td>{{ $nfcCard->card_number }}</td>
                                        <td>
                                            @if($nfcCard->status == 1)
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $nfcCard->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="Action">
                                            <div class="action-btn bg-info ms-2">
                                                <a href="{{ route('nfc.edit', $nfcCard->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['nfc.destroy', $nfcCard->id], 'id' => 'delete-form-' . $nfcCard->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                {!! Form::close() !!}
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
@endsection
