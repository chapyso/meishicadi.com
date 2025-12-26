{{ Form::open(['route' => ['request.amount.store', $user->id], 'method' => 'post']) }}

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('request_amount', __('Request Amount'), ['class' => 'form-label']) }}
            {{ Form::number('request_amount', $user->commission_amount-$paidAmount, ['class' => 'form-control', 'placeholder' => __('Enter Request Amount'), 'required' => 'required']) }}
        </div>
    </div>
</div>



<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Send') }}" class="btn  btn-primary">
</div>

{{ Form::close() }}
