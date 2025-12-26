<div class="modal-header">
    <h5 class="modal-title">{{ __('Extend Subscription') }} - {{ $user->name }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <strong>{{ __('Current Plan') }}:</strong> {{ !empty($user->currentPlan) ? $user->currentPlan->name : 'No Plan' }}<br>
                <strong>{{ __('Current Expiry') }}:</strong> {{ !empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : __('Lifetime') }}
            </div>
        </div>
    </div>
    
    <form id="subscription-extension-form">
        <div class="row">
            <div class="col-12">
                <label for="extension_period" class="form-label">{{ __('Select Extension Period') }}</label>
                <select class="form-select" id="extension_period" name="extension_period" required>
                    <option value="">{{ __('Choose extension period...') }}</option>
                    <option value="1">{{ __('1 Month') }}</option>
                    <option value="2">{{ __('2 Months') }}</option>
                    <option value="3">{{ __('3 Months') }}</option>
                    <option value="6">{{ __('6 Months') }}</option>
                    <option value="12">{{ __('1 Year') }}</option>
                    <option value="24">{{ __('2 Years') }}</option>
                    <option value="36">{{ __('3 Years') }}</option>
                </select>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div id="extension-preview" class="alert alert-success" style="display: none;">
                    <strong>{{ __('New Expiry Date') }}:</strong> <span id="new-expiry-date"></span>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <button type="button" class="btn btn-info" id="test-btn">{{ __('Test Connection') }}</button>
    <button type="button" class="btn btn-primary" id="extend-subscription-btn" disabled>{{ __('Extend Subscription') }}</button>
</div>

<script>
$(document).ready(function() {
    console.log('Subscription extension modal loaded');
    
    // Test button functionality
    $('#test-btn').click(function() {
        console.log('Test button clicked');
        alert('Modal JavaScript is working!');
    });
    
    // Handle extension period change
    $('#extension_period').change(function() {
        const selectedPeriod = $(this).val();
        const btn = $('#extend-subscription-btn');
        
        console.log('Extension period changed:', selectedPeriod);
        
        if (selectedPeriod) {
            btn.prop('disabled', false);
            
            // Calculate and show preview
            const currentDate = '{{ $user->plan_expire_date }}';
            if (currentDate && currentDate !== 'null') {
                const currentExpire = new Date(currentDate);
                const newExpire = new Date(currentExpire);
                newExpire.setMonth(newExpire.getMonth() + parseInt(selectedPeriod));
                
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                $('#new-expiry-date').text(newExpire.toLocaleDateString('en-US', options));
                $('#extension-preview').show();
            } else {
                $('#extension-preview').hide();
            }
        } else {
            btn.prop('disabled', true);
            $('#extension-preview').hide();
        }
    });
    
    // Handle form submission
    $('#extend-subscription-btn').click(function(e) {
        e.preventDefault();
        
        const form = $('#subscription-extension-form');
        const btn = $(this);
        const originalText = btn.text();
        
        console.log('Extend subscription button clicked');
        console.log('Form data:', form.serialize());
        console.log('URL:', '{{ route("subscription.extend", $user->id) }}');
        
        // Validate form
        const extensionPeriod = $('#extension_period').val();
        if (!extensionPeriod) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '{{ __("Validation Error") }}',
                    text: '{{ __("Please select an extension period.") }}',
                    icon: 'warning',
                    confirmButtonText: '{{ __("OK") }}',
                    confirmButtonColor: '#ffc107'
                });
            } else {
                try {
                    show_toastr('{{ __("Error") }}', '{{ __("Please select an extension period.") }}', 'error');
                } catch (e) {
                    alert('{{ __("Error") }}: {{ __("Please select an extension period.") }}');
                }
            }
            return;
        }
        
        // Show loading state with better animation
        btn.prop('disabled', true).html('<i class="ti ti-loader ti-spin me-2"></i> {{ __("Processing...") }}');
        
        // Add loading overlay to modal
        const modal = $('#commonModal');
        modal.find('.modal-content').append('<div class="modal-loading-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.8); display: flex; align-items: center; justify-content: center; z-index: 1050;"><div class="text-center"><i class="ti ti-loader ti-spin" style="font-size: 2rem; color: #007bff;"></i><p class="mt-2">{{ __("Processing your request...") }}</p></div></div>');
        
        // No CSRF token needed - excluded from verification
        console.log('CSRF protection bypassed for this endpoint');
        
        const formData = form.serialize();
        console.log('Form data being sent:', formData);
        
        $.ajax({
            url: '{{ route("subscription.extend", $user->id) }}',
            method: 'POST',
            data: formData,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            timeout: 30000, // 30 second timeout
            success: function(response) {
                console.log('Success response received:', response);
                console.log('Response type:', typeof response);
                console.log('Response keys:', Object.keys(response));
                if (response.success) {
                    // Show success message
                    console.log('Showing success notification');
                    
                    // Reset button state immediately
                    btn.prop('disabled', false).html(originalText);
                    
                    // Remove loading overlay
                    $('.modal-loading-overlay').remove();
                    
                    // Show SweetAlert confirmation
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '{{ __("Subscription Extended Successfully!") }}',
                            html: `
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="ti ti-check-circle text-success" style="font-size: 3rem; color: #28a745;"></i>
                                    </div>
                                    <div class="text-left">
                                        <p><strong>{{ __("Extension Details:") }}</strong></p>
                                        <ul class="text-left" style="list-style: none; padding-left: 0;">
                                            <li><strong>{{ __("User:") }}</strong> {{ $user->name }}</li>
                                            <li><strong>{{ __("Extension Period:") }}</strong> ${$('#extension_period option:selected').text()}</li>
                                            <li><strong>{{ __("New Expiry Date:") }}</strong> ${response.new_expire_date}</li>
                                        </ul>
                                        <div class="alert alert-info mt-3">
                                            <i class="ti ti-mail"></i> {{ __("Email notifications have been sent to the user and admin.") }}
                                        </div>
                                    </div>
                                </div>
                            `,
                            icon: 'success',
                            confirmButtonText: '{{ __("OK") }}',
                            confirmButtonColor: '#28a745',
                            width: '500px',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        }).then((result) => {
                            // Close modal after user clicks OK
                            $('#commonModal').modal('hide');
                            
                            // Update the user card in the main view
                            updateUserCard('{{ $user->id }}', response.new_expire_date);
                            
                            // Reload the page after a short delay to refresh the user list
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        });
                    } else {
                        // Fallback to toastr if SweetAlert is not available
                        try {
                            show_toastr('{{ __("Success") }}', response.message, 'success');
                        } catch (e) {
                            console.log('show_toastr failed, using alert:', e);
                            alert('{{ __("Success") }}: ' + response.message);
                        }
                        
                        // Close modal and reload
                        $('#commonModal').modal('hide');
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                    
                    // Show additional confirmation notification with details
                    const extensionPeriod = $('#extension_period option:selected').text();
                    
                    // Check if SweetAlert is available
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '{{ __("Subscription Extended!") }}',
                            html: `
                                <div class="text-left">
                                    <p><strong>{{ __("Extension Details:") }}</strong></p>
                                    <ul class="text-left" style="list-style: none; padding-left: 0;">
                                        <li><strong>{{ __("Extension Period:") }}</strong> ${extensionPeriod}</li>
                                        <li><strong>{{ __("New Expiry Date:") }}</strong> ${response.new_expire_date}</li>
                                        <li><strong>{{ __("User:") }}</strong> {{ $user->name }}</li>
                                    </ul>
                                    <p class="mt-3">{{ __("Email notifications have been sent to the user and admin.") }}</p>
                                </div>
                            `,
                            icon: 'success',
                            confirmButtonText: '{{ __("OK") }}',
                            confirmButtonColor: '#28a745',
                            width: '500px'
                        });
                    } else {
                        console.log('SweetAlert not available, showing basic alert');
                        alert('{{ __("Subscription Extended Successfully!") }}\n\n{{ __("Extension Period:") }}: ' + extensionPeriod + '\n{{ __("New Expiry Date:") }}: ' + response.new_expire_date);
                    }
                    
                    // Update the user card in the main view
                    updateUserCard('{{ $user->id }}', response.new_expire_date);
                    
                    // Close modal
                    $('#commonModal').modal('hide');
                    
                    // Reload the page after a short delay to refresh the user list
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    show_toastr('{{ __("Error") }}', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error Details:', {
                    xhr: xhr, 
                    status: status, 
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status,
                    readyState: xhr.readyState,
                    responseHeaders: xhr.getAllResponseHeaders()
                });
                
                let errorMessage = '{{ __("An error occurred while extending the subscription.") }}';
                
                if (xhr.status === 419) {
                    errorMessage = '{{ __("CSRF token mismatch. Please refresh the page and try again.") }}';
                } else if (xhr.status === 422) {
                    errorMessage = '{{ __("Validation error. Please check your input.") }}';
                } else if (xhr.status === 404) {
                    errorMessage = '{{ __("User not found.") }}';
                } else if (xhr.status === 500) {
                    errorMessage = '{{ __("Server error. Please try again later.") }}';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        console.log('Could not parse response as JSON');
                        if (xhr.responseText.includes('CSRF')) {
                            errorMessage = '{{ __("CSRF token mismatch. Please refresh the page and try again.") }}';
                        }
                    }
                }
                
                // Show error notification with SweetAlert
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '{{ __("Error") }}',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: '{{ __("OK") }}',
                        confirmButtonColor: '#dc3545',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                } else {
                    // Fallback to toastr
                    try {
                        show_toastr('{{ __("Error") }}', errorMessage, 'error');
                    } catch (e) {
                        alert('{{ __("Error") }}: ' + errorMessage);
                    }
                }
            },
            complete: function(xhr, status) {
                console.log('AJAX request completed with status:', status);
                console.log('Response status:', xhr.status);
                console.log('Response readyState:', xhr.readyState);
                
                // Only reset button if it's still in processing state
                if (btn.text().includes('Processing')) {
                    btn.prop('disabled', false).html(originalText);
                }
                
                // Remove loading overlay
                $('.modal-loading-overlay').remove();
            }
        });
    });
    
    // Function to update user card in main view
    function updateUserCard(userId, newExpireDate) {
        console.log('Updating user card:', userId, newExpireDate);
        const userCard = $(`.col-xl-3:has([data-user-id="${userId}"])`);
        if (userCard.length) {
            const expireButton = userCard.find('.btn-neutral');
            if (expireButton.length) {
                expireButton.find('a').text('{{ __("Plan Expired : ") }}' + newExpireDate);
            }
            
            // Add success animation to the card
            userCard.addClass('border-success');
            userCard.css('border', '2px solid #28a745');
            userCard.css('box-shadow', '0 0 15px rgba(40, 167, 69, 0.4)');
            userCard.css('transform', 'scale(1.02)');
            userCard.css('transition', 'all 0.3s ease');
            
            // Add a success badge
            const successBadge = $('<div class="position-absolute top-0 end-0 m-2"><span class="badge bg-success"><i class="ti ti-check me-1"></i>{{ __("Updated") }}</span></div>');
            userCard.css('position', 'relative').append(successBadge);
            
            // Remove the success styling after 3 seconds
            setTimeout(function() {
                userCard.removeClass('border-success');
                userCard.css('border', '');
                userCard.css('box-shadow', '');
                userCard.css('transform', '');
                successBadge.fadeOut(300, function() { $(this).remove(); });
            }, 3000);
        }
    }
});
</script> 