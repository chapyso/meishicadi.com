{{ Form::model($campaigns, ['route' => ['campaigns.update', $campaigns->id], 'method' => 'PUT']) }}
<div class="row">

    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control font-style', 'required' => 'required', 'placeholder' => 'Enter Campaigns Name']) }}
    </div>
    <div class="form-group col-6">
        {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
        {!! Form::select('category', $category, $campaigns->cat_type, [
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
    @php
        $selectedBusiness = explode(',', $campaigns->business);
    @endphp
    <div class="form-group col-md-6">
        {{ Form::label('business', __('Business'), ['class' => 'form-label']) }}
        <div id="business">
            <select class="form-control choices choices-multiple-remove-button" name="business[]" id="business_name"
                multiple required>
                @foreach ($business as $key => $value)
                    <option value="{{ $key }}" {{ in_array($key, $selectedBusiness) ? 'selected' : '' }}>
                        {{ $value }}</option>
                @endforeach
            </select>
        </div>
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
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input class="btn btn-primary" type="submit" value="{{ __('Update') }}">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        var businessSelect;

        function getBusiness(category_id) {
            $.ajax({
                url: '{{ route('campaigns.business') }}',
                type: 'POST',
                data: {
                    "category_id": category_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#business').empty(); // Clear existing options
                    var selectOptions = '';

                    var option =
                        '<select class="form-control choices choices-multiple-remove-button" name="business[]" id="business_name" placeholder="{{ __('Select Business') }}" multiple>';
                    option += '<option value="" disabled>{{ __('Select Business') }}</option>';

                    $.each(data, function(key, value) {
                        option += '<option value="' + key + '">' + value + '</option>';
                    });
                    option += '</select>';

                    $("#business").append(option);

                    // Destroy and reinitialize Choices.js after updating the select element
                    if (businessSelect) {
                        businessSelect.destroy();
                    }
                    businessSelect = new Choices('#business_name', {
                        removeItemButton: true,
                        placeholder: true,
                    });
                }
            });
        }

        businessSelect = new Choices('#business_name', {
            removeItemButton: true,
            placeholder: true,
        });
        $(document).on('change', 'select[name=category]', function() {
            var category_id = $(this).val();
            getBusiness(category_id);
        });

        // var initialCategoryId = $('.business_category').val();
        // getBusiness(initialCategoryId);

        $('.end_date').change(function() {
            calculateTotalDays();
        });
        $('.start_date').change(function() {
            calculateTotalDays();
        });

        function calculateTotalDays() {
            var startDate = $('.start_date').val();
            var endDate = $('.end_date').val();
            if (startDate && endDate) {
                var startDateObj = new Date(startDate);
                var endDateObj = new Date(endDate);
                var timeDiff = endDateObj.getTime() - startDateObj.getTime();
                var totalDays = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                $('.total_days').val(totalDays);
            } else {
                $('.total_days').val('');
            }
        }

        function updateEndDateMin() {
            var startDate = document.querySelector('.start_date').value;
            document.querySelector('.end_date').min = startDate;
        }
    });
</script>
