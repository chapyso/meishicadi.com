{{ Form::open(['url' => route('campaigns.store'), 'enctype' => 'multipart/form-data']) }}
<div class="row">

  <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control font-style', 'required' => 'required', 'placeholder' => 'Enter Campaigns Name']) }}
    </div>
    {{-- <div class="col-6 form-group">
        {{ Form::label('Category', __('Category'), ['class' => 'form-control-label']) }}
        {{ Form::text('category', $campaigns->category->name, ['class' => 'form-control mt-2 ', 'required' => 'required', 'readonly' => 'readonly']) }}
        {{ Form::hidden('category', $campaigns->category, ['class' => 'form-control mt-2 ']) }}
        @error('category')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div> --}}
    {{-- <div class="col-6 form-group">
        {{ Form::label('business', __('Business'), ['class' => 'form-control-label']) }}
        {{ Form::text('businesses', $campaigns->businesses->title, ['class' => 'form-control mt-2 ', 'required' => 'required', 'readonly' => 'readonly']) }}
        {{ Form::hidden('business', $campaigns->business, ['class' => 'form-control mt-2 ']) }}
        @error('business')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div> --}}
    <div class="form-group col-6">
        {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
        {!! Form::select('category', $category, null, [
            'class' => 'form-control select2 business_category ',
            'required' => 'required',
        ]) !!}
        @error('category')
            <small class="invalid-role" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
        @enderror
        <small><a href="{{ route('category.index') }}">{{ __('You can add new category from here') }}</a></small>
    </div>

    <div class="form-group col-md-6">
        {{ Form::label('business', __('Business'), ['class' => 'form-label']) }}
        {!! Form::select('business', [], null, [
            'class' => 'form-control select2 ',
            'required' => 'required',
            'id' => 'business',
        ]) !!}
    </div>
    <div class="col-6 form-group">
        {{ Form::label('Start Date', __('Start Date'), ['class' => 'form-control-label']) }}
        {{ Form::date('start_date', null, ['class' => 'form-control mt-2 start_date', 'required' => 'required', 'min' => \Carbon\Carbon::now()->format('Y-m-d'), 'onchange' => 'updateEndDateMin()']) }}
        @error('start_date')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-6 form-group">
        {{ Form::label('End Date', __('End Date'), ['class' => 'form-control-label']) }}
        {{ Form::date('end_date', null, ['class' => 'form-control mt-2 end_date', 'required' => 'required']) }}
        @error('end_date')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-6 form-group">
        {{ Form::label('total_days', __('Total Days'), ['class' => 'form-label']) }}
        {{ Form::number('total_days', null, ['class' => 'form-control total_days', 'readonly' => 'readonly']) }}
        @error('total_days')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-6 form-group">
        {{ Form::label('total_cost', __('Total Cost'), ['class' => 'form-label']) }}
        {{ Form::number('total_cost', null, ['class' => 'form-control total_cost', 'readonly' => 'readonly']) }}

        @error('total_cost')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <div class="row coupon-div d-none">
        <div class="col-md-11">
            <div class="form-group">
                <label for="bank_coupon" class="form-label">{{ __('Coupon') }}</label>
                <input type="text" id="bank_coupon" name="coupon" class="form-control coupon"
                    placeholder="{{ __('Enter Coupon Code') }}">
            </div>
        </div>
        <div class="col-1 my-auto">
            <a href="#" class="apply-btn btn btn-lg btn-primary btn-create apply-coupon-promote mt-2"
                data-bs-toggle="tooltip" data-bs-title="{{ __('Apply') }}"><span><i
                        data-feather="save"></i></span></a>
        </div>
        <small>{{ __('After apply coupon the final cost is ::') }} <b> <span class="coupon-price">-</span></b> </small>
    </div>

    <div class="row form-group">
        {{ Form::label('total_cost', __('Payment Method'), ['class' => 'form-label']) }}
        @php
            $enabledPayments = \App\Models\Utility::isEnablePayment();
        @endphp

        @foreach ($enabledPayments as $payment)
            <div class="col-sm-12 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="ms-3">
                                    <label for="{{ $payment }}-payment">
                                        <h5 class="mb-0 text-capitalize pointer payment-option"
                                            value="{{ $payment }}">{{ $payment }}</h5>
                                    </label>
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input payment_method" name="payment_method"
                                    id="{{ $payment }}-payment" type="radio" value="{{ $payment }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input class="btn btn-primary" type="submit" value="{{ __('Create') }}">
</div>
{{ Form::close() }}
<script>
    var selectedBusinessId = '{{ isset($promoteData->business) ? $promoteData->business : 0 }}';

    function getBusiness(category_id) {
        $.ajax({
            url: '{{ route('campaigns.business') }}',
            type: 'POST',
            data: {
                "category_id": category_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#business').empty();
                $('#business').append('<option value="">Select Business</option>');
                $.each(data, function(key, value) {
                    var selected = (key == selectedBusinessId) ? 'selected' : '';
                    $('#business').append('<option value="' + key + '" ' + selected + '>' + value +
                        '</option>');
                });
            }
        });
    }


    $(document).on('change', 'select[name=category]', function() {
        var category_id = $(this).val();
        getBusiness(category_id);
    });

    $(document).ready(function() {
        var initialCategoryId = '{{ isset($promoteData->category) ? $promoteData->category : 0 }}';
        if (initialCategoryId) {
            getBusiness(initialCategoryId);
        }
    });

    function findPrice(total_days) {
        $.ajax({
                url: '{{ route('campaigns.costing') }}',
                type: 'POST',
                data: {
                    "total_days": total_days,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.costData.price) {
                            $('.total_cost').val(data.costData.price*total_days);
                            $('.coupon-div').removeClass('d-none');
                        } else {
                            $('.total_cost').val('');
                            $('.coupon-div').addClass('d-none');
                        }
                    }
                });
        }

        function calculateTotalDaysTotalCost() {
            var startDate = $('.start_date').val();
            var endDate = $('.end_date').val();

            if (startDate && endDate) {
                var startDateObj = new Date(startDate);
                var endDateObj = new Date(endDate);
                var timeDiff = endDateObj.getTime() - startDateObj.getTime();
                var totalDays = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                $('.total_days').val(totalDays);
                findPrice(totalDays);
            } else {
                $('.total_days').val('');
            }
            var totalDays = parseInt($('.total_days').val(), 10);
        }
        $('.end_date, .start_date').change(calculateTotalDaysTotalCost);



        feather.replace();
        $(document).on('click', '.apply-coupon-promote', function() {
            var ele = $(this);
            var coupon = ele.closest('.row').find('.coupon').val();

            $.ajax({
                url: '{{ route('apply.coupon.promote') }}',
                type: 'GET',
                datType: 'json',
                data: {
                    coupon: coupon,
                    total_cost: $('.total_cost').val()
                },
                headers: {
                    'Content-Type': 'application/json'
                },
                success: function(data) {
                    if (data.is_success == true) {
                        $('.coupon-price').text(data.final_price);
                        $('.total_cost').val(data.price);
                        toastrs('{{ __('Success') }}', data.message, 'success');
                    } else if (data.is_success == false) {
                        toastrs('{{ __('Error') }}', data.message, 'error');
                    } else {
                        toastrs('{{ __('Error') }}', 'Coupon code is required', 'error');
                    }
                }
            })
        });

        function updateEndDateMin() {
            var startDate = document.querySelector('.start_date').value;
            document.querySelector('.end_date').min = startDate;
        }
</script>
