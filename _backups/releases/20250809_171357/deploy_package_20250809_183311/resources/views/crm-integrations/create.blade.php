@extends('layouts.admin')

@section('page-title')
    {{ __('Add CRM Integration') }}
@endsection

@section('title')
    {{ __('Add CRM Integration') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm-integrations.index') }}">{{ __('CRM Integrations') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Add Integration') }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Connect Your CRM') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('crm-integrations.store') }}" method="POST" id="integrationForm">
                    @csrf
                    
                    <!-- CRM Type Selection -->
                    <div class="mb-4">
                        <label class="form-label">{{ __('Select CRM Platform') }}</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-2 cursor-pointer" id="hubspot-card" onclick="selectCrmType('hubspot')">
                                    <div class="card-body text-center">
                                        <i class="fab fa-hubspot fa-3x text-orange mb-3"></i>
                                        <h6 class="card-title">HubSpot</h6>
                                        <p class="card-text small text-muted">Sync contacts to HubSpot CRM</p>
                                        <div class="form-check d-none">
                                            <input class="form-check-input" type="radio" name="crm_type" value="hubspot" id="hubspot-radio">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-2 cursor-pointer" id="zoho-card" onclick="selectCrmType('zoho')">
                                    <div class="card-body text-center">
                                        <i class="fas fa-cloud fa-3x text-blue mb-3"></i>
                                        <h6 class="card-title">Zoho CRM</h6>
                                        <p class="card-text small text-muted">Sync contacts to Zoho CRM</p>
                                        <div class="form-check d-none">
                                            <input class="form-check-input" type="radio" name="crm_type" value="zoho" id="zoho-radio">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('crm_type')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Integration Details -->
                    <div class="mb-4">
                        <label for="name" class="form-label">{{ __('Integration Name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('e.g., My HubSpot Integration') }}" required>
                        @error('name')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="business_id" class="form-label">{{ __('Business (Optional)') }}</label>
                        <select class="form-select" id="business_id" name="business_id">
                            <option value="">{{ __('All Businesses') }}</option>
                            @foreach($businesses as $business)
                                <option value="{{ $business->id }}" {{ old('business_id') == $business->id ? 'selected' : '' }}>
                                    {{ $business->title }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('Leave empty to sync contacts from all your businesses') }}</small>
                    </div>

                    <!-- API Configuration -->
                    <div class="mb-4">
                        <label for="api_key" class="form-label">{{ __('API Key') }}</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="api_key" name="api_key" value="{{ old('api_key') }}" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-primary" type="button" id="testConnection">
                                <i class="fas fa-plug me-1"></i>{{ __('Test Connection') }}
                            </button>
                        </div>
                        <div id="apiHelp" class="form-text"></div>
                        @error('api_key')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sync Settings -->
                    <div class="mb-4">
                        <label class="form-label">{{ __('Sync Settings') }}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="auto_sync" name="auto_sync" value="1" {{ old('auto_sync') ? 'checked' : '' }}>
                            <label class="form-check-label" for="auto_sync">
                                {{ __('Enable automatic sync') }}
                            </label>
                        </div>
                        <small class="text-muted">{{ __('When enabled, new contacts will be automatically synced to your CRM') }}</small>
                    </div>

                    <!-- Field Mapping -->
                    <div class="mb-4" id="fieldMappingSection" style="display: none;">
                        <label class="form-label">{{ __('Field Mapping') }}</label>
                        <p class="text-muted">{{ __('Map your Meishi contact fields to CRM fields') }}</p>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Meishi Field') }}</th>
                                        <th>{{ __('CRM Field') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label class="form-label mb-0">{{ __('Name') }}</label>
                                            <small class="text-muted d-block">{{ __('Contact name') }}</small>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="field_mapping[name]" id="crm_name_field" value="firstname">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="form-label mb-0">{{ __('Email') }}</label>
                                            <small class="text-muted d-block">{{ __('Contact email') }}</small>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="field_mapping[email]" id="crm_email_field" value="email">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="form-label mb-0">{{ __('Phone') }}</label>
                                            <small class="text-muted d-block">{{ __('Contact phone') }}</small>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="field_mapping[phone]" id="crm_phone_field" value="phone">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="form-label mb-0">{{ __('Message') }}</label>
                                            <small class="text-muted d-block">{{ __('Contact message') }}</small>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="field_mapping[message]" id="crm_message_field" value="description">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Connection Test Result -->
                    <div id="connectionResult" class="mb-4" style="display: none;">
                        <div class="alert" id="connectionAlert">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="connectionMessage"></span>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('crm-integrations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>{{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i>{{ __('Create Integration') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
let selectedCrmType = '';

function selectCrmType(crmType) {
    selectedCrmType = crmType;
    
    // Update card styles
    $('.card').removeClass('border-primary');
    $(`#${crmType}-card`).addClass('border-primary');
    
    // Update radio button
    $(`#${crmType}-radio`).prop('checked', true);
    
    // Update field mapping
    updateFieldMapping(crmType);
    
    // Show field mapping section
    $('#fieldMappingSection').show();
    
    // Update API help text
    updateApiHelp(crmType);
}

function updateFieldMapping(crmType) {
    const mappings = {
        hubspot: {
            name: 'firstname',
            email: 'email',
            phone: 'phone',
            message: 'description'
        },
        zoho: {
            name: 'First_Name',
            email: 'Email',
            phone: 'Phone',
            message: 'Description'
        }
    };
    
    const mapping = mappings[crmType];
    if (mapping) {
        $('#crm_name_field').val(mapping.name);
        $('#crm_email_field').val(mapping.email);
        $('#crm_phone_field').val(mapping.phone);
        $('#crm_message_field').val(mapping.message);
    }
}

function updateApiHelp(crmType) {
    const helpTexts = {
        hubspot: 'Get your HubSpot API key from Settings > Account Setup > Integrations > API Keys',
        zoho: 'Get your Zoho CRM API token from Setup > Developer Space > Self-Client'
    };
    
    $('#apiHelp').text(helpTexts[crmType] || '');
}

// Toggle password visibility
$('#togglePassword').on('click', function() {
    const input = $('#api_key');
    const icon = $(this).find('i');
    
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});

// Test connection
$('#testConnection').on('click', function() {
    const apiKey = $('#api_key').val();
    const crmType = selectedCrmType;
    
    if (!apiKey || !crmType) {
        showConnectionResult('warning', 'Please select a CRM type and enter your API key');
        return;
    }
    
    // Show loading state
    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Testing...');
    
    $.ajax({
        url: '{{ route("crm-integrations.test-connection") }}',
        method: 'POST',
        data: {
            crm_type: crmType,
            api_key: apiKey,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showConnectionResult('success', 'Connection successful! Your API key is valid.');
            } else {
                showConnectionResult('danger', 'Connection failed: ' + response.message);
            }
        },
        error: function() {
            showConnectionResult('danger', 'Connection test failed. Please check your API key and try again.');
        },
        complete: function() {
            $('#testConnection').prop('disabled', false).html('<i class="fas fa-plug me-1"></i>Test Connection');
        }
    });
});

function showConnectionResult(type, message) {
    const alert = $('#connectionAlert');
    const messageSpan = $('#connectionMessage');
    
    alert.removeClass('alert-success alert-warning alert-danger').addClass(`alert-${type}`);
    messageSpan.text(message);
    
    $('#connectionResult').show();
}

// Form validation
$('#integrationForm').on('submit', function(e) {
    if (!selectedCrmType) {
        e.preventDefault();
        alert('Please select a CRM type');
        return false;
    }
    
    const apiKey = $('#api_key').val();
    if (!apiKey) {
        e.preventDefault();
        alert('Please enter your API key');
        return false;
    }
});

// Initialize
$(document).ready(function() {
    // Add cursor pointer to CRM cards
    $('.card').css('cursor', 'pointer');
    
    // Handle card hover effects
    $('.card').hover(
        function() { $(this).addClass('shadow-sm'); },
        function() { $(this).removeClass('shadow-sm'); }
    );
});
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

.border-primary {
    border-color: #007bff !important;
}
</style>
@endpush 