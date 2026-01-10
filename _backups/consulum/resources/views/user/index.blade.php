@extends('layouts.admin')
@php
    // $profile=asset(Storage::url('uploads/avatar/'));
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
    {{ __('Manage Users') }}
@endsection
@section('title')
    {{ __('Manage Users') }}
@endsection
@section('action-btn')
    @can('create user')
        <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
            data-bs-placement="top">
            <a href="#" data-size="md" data-url="{{ route('users.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
                title="{{ __('Create') }}" data-title="{{ __('Create New User') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
            @if (Auth::user()->type == 'company')
                <a href="{{ route('userlogs.index') }}" class="btn btn-sm btn-primary btn-icon m-1" data-size="lg"
                    data-bs-whatever="{{ __('UserlogDetail') }}"> <span class="text-white">
                        <i class="ti ti-user" data-bs-toggle="tooltip"
                            data-bs-original-title="{{ __('Userlog Detail') }}"></i></span>
                </a>
            @endif
        </div>
    @endcan
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('User') }}</li>
@endsection
@section('content')
    @if (Auth::user()->type == 'super admin')
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllUsers" name="selectAllUsers">
                    <label class="form-check-label" for="selectAllUsers">
                        {{ __('Select All') }}
                    </label>
                </div>
                <button type="button" id="bulkDeleteBtn" class="btn btn-sm btn-danger d-none" 
                    data-bs-toggle="modal" data-bs-target="#bulkDeleteModal">
                    <i class="ti ti-trash"></i> {{ __('Delete Selected') }}
                </button>
            </div>
        </div>
    @endif
    <div class="row">
        @foreach ($users as $user)
            <div class="col-xl-3 mb-3">
                <div class="card text-center">
                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">
                        <div class="border-0 pb-0 ">
                            <h6 class="mb-0">
                                <div class="badge p-2 px-3 rounded bg-primary">{{ ucfirst($user->type) }}</div>
                            </h6>
                        </div>
                        <div class="d-flex align-items-center">
                            @if (Auth::user()->type == 'super admin')
                                <div class="form-check me-2">
                                    <input class="form-check-input user-checkbox" type="checkbox" 
                                        value="{{ $user->id }}" id="userCheckbox{{ $user->id }}" 
                                        data-user-id="{{ $user->id }}">
                                </div>
                            @endif
                            <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="feather icon-more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @can('edit user')
                                        <a href="#" class="dropdown-item user-drop"
                                            data-url="{{ route('users.edit', $user->id) }}" data-ajax-popup="true"
                                            data-title="{{ __('Update User') }}"><i class="ti ti-edit"></i><span
                                                class="ml-2">{{ __('Edit') }}</span></a>
                                    @endcan
                                    @can('change password account')
                                        <a href="#" class="dropdown-item user-drop" data-ajax-popup="true"
                                            data-title="{{ __('Reset Password') }}"
                                            data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"><i
                                                class="ti ti-key"></i>
                                            <span class="ml-2">{{ __('Reset Password') }}</span></a>
                                    @endcan
                                    @can('delete user')
                                        <a href="#" class="bs-pass-para dropdown-item user-drop"
                                            data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $user->id }}" title="{{ __('Delete') }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-trash"></i><span
                                                class="ml-2">{{ __('Delete') }}</span></a>
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['users.destroy', $user->id],
                                            'id' => 'delete-form-' . $user->id,
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                    @if (\Auth::user()->type == 'company')
                                        <a href="{{ route('userlogs.index', ['month' => '', 'user' => $user->id]) }}"
                                            class="dropdown-item user-drop" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('User Log') }}">
                                            <i class="ti ti-history"></i>
                                            <span class="ml-2">{{ __('Logged Details') }}</span></a>
                                    @endif
                                    @if (Auth::user()->type == 'super admin')
                                        <a href="{{ route('login.with.company', $user->id) }}" class="dropdown-item user-drop"
                                            data-bs-original-title="{{ __('Login As Company') }}">
                                            <i class="ti ti-replace"></i>
                                            <span class="ml-2"> {{ __('Login As Company') }}</span>
                                        </a>
                                    @endif
                                    @if ($user->is_enable_login == 1)
                                        <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                            class="dropdown-item user-drop">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-danger ml-2"> {{ __('Login Disable') }}</span>
                                        </a>
                                    @elseif ($user->is_enable_login == 0 && $user->password == null)
                                        <a href="#" data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}"
                                            data-ajax-popup="true" data-size="md" class="dropdown-item login_enable user-drop"
                                            data-title="{{ __('New Password') }}">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-success ml-2"> {{ __('Login Enable') }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                            class="dropdown-item user-drop">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-success ml-2"> {{ __('Login Enable') }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                        <div class="avatar">
                            <a href="{{ !empty($user->avatar) ? $profile . '/' . $user->avatar : $profile . '/avatar.png' }}"
                                target="_blank">
                                <img src="{{ !empty($user->avatar) ? $profile . '/' . $user->avatar : $profile . '/avatar.png' }}"
                                    class="rounded-circle img_users_fix_size">
                            </a>
                        </div>
                        <h4 class="mt-2">{{ $user->name }}</h4>
                        <small>{{ $user->email }}</small>
                        @if (\Auth::user()->type == 'super admin')
                            <div class=" mb-0 mt-3">
                                <div class=" p-3">
                                    <div class="row">
                                        <div class="col-5 text-start">
                                            <h6 class="mb-0  mt-1">
                                                {{ !empty($user->currentPlan) ? $user->currentPlan->name : '' }}</h6>
                                        </div>
                                        <div class="col-7 text-end">
                                            <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}"
                                                class="btn btn-sm btn-primary btn-icon" data-size="lg"
                                                data-ajax-popup="true"
                                                data-title="{{ __('Upgrade Plan') }}">{{ __('Upgrade Plan') }}</a>
                                        </div>

                                        <div class="col-6 text-start mt-4">
                                            <h6 class="mb-0 px-3">{{ $user->getTotalAppoinments() }}</h6>
                                            <p class="text-muted text-sm mb-0">{{ __('Appointments') }}</p>
                                        </div>

                                        <div class="col-6 text-end mt-4">
                                            <a href="#" data-url="{{ route('business.upgrade', $user->id) }}"
                                                class="btn btn-sm btn-primary btn-icon" data-size="lg"
                                                data-ajax-popup="true"
                                                data-title="{{ __('Business Info') }}">{{ __('Businesses') }}</a>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 mb-0">

                                <button class="btn btn-sm btn-neutral mt-3 font-weight-500">
                                    <a>{{ __('Plan Expired : ') }}
                                        {{ !empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : __('Lifetime') }}</a>
                                </button>

                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        @can('create user')
            <div class="col-md-3">
                <a href="#" class="btn-addnew-project" data-ajax-popup="true" data-size="md"
                    data-title="{{ __('Create New User') }}" data-url="{{ route('users.create') }}">
                    <div class="badge bg-primary proj-add-icon">
                        <i class="ti ti-plus"></i>
                    </div>
                    <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
                    <p class="text-muted text-center">{{ __('Click here to add New User') }}</p>
                </a>
            </div>
        @endcan
    </div>

    @if (Auth::user()->type == 'super admin')
        <!-- Bulk Delete Confirmation Modal -->
        <div class="modal fade" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkDeleteModalLabel">{{ __('Confirm Bulk Deletion') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Are you sure you want to delete the selected users? This action cannot be undone.') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-danger" id="confirmBulkDelete">{{ __('Delete') }}</button>
                    </div>
                </div>
            </div>
        </div>

        @push('custom-scripts')
        <script>
            $(document).ready(function() {
                // Handle Select All checkbox
                $('#selectAllUsers').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    $('.user-checkbox').prop('checked', isChecked);
                    toggleDeleteButton();
                });

                // Handle individual checkboxes
                $(document).on('change', '.user-checkbox', function() {
                    updateSelectAllCheckbox();
                    toggleDeleteButton();
                });

                // Update Select All checkbox state
                function updateSelectAllCheckbox() {
                    const totalCheckboxes = $('.user-checkbox').length;
                    const checkedCheckboxes = $('.user-checkbox:checked').length;
                    $('#selectAllUsers').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
                }

                // Toggle Delete Selected button visibility
                function toggleDeleteButton() {
                    const checkedCount = $('.user-checkbox:checked').length;
                    if (checkedCount > 0) {
                        $('#bulkDeleteBtn').removeClass('d-none');
                    } else {
                        $('#bulkDeleteBtn').addClass('d-none');
                    }
                }

                // Handle bulk delete confirmation
                $('#confirmBulkDelete').on('click', function() {
                    const selectedUserIds = [];
                    $('.user-checkbox:checked').each(function() {
                        selectedUserIds.push($(this).data('user-id'));
                    });

                    if (selectedUserIds.length === 0) {
                        toastrs('{{ __('Error') }}', '{{ __('No users selected.') }}', 'error');
                        $('#bulkDeleteModal').modal('hide');
                        return;
                    }

                    // Disable button during request
                    $(this).prop('disabled', true).text('{{ __('Deleting...') }}');

                    // Send AJAX request
                    $.ajax({
                        url: '{{ route("users.bulk.destroy") }}',
                        method: 'DELETE',
                        data: {
                            user_ids: selectedUserIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastrs('{{ __('Success') }}', response.message, 'success');
                                // Remove deleted user cards from DOM
                                selectedUserIds.forEach(function(userId) {
                                    $('#userCheckbox' + userId).closest('.col-xl-3').fadeOut(300, function() {
                                        $(this).remove();
                                        // Check if any users are left
                                        if ($('.user-checkbox').length === 0) {
                                            location.reload();
                                        }
                                    });
                                });
                                // Reset select all and hide delete button
                                $('#selectAllUsers').prop('checked', false);
                                $('#bulkDeleteBtn').addClass('d-none');
                            } else {
                                toastrs('{{ __('Error') }}', response.message || '{{ __('Failed to delete selected users. Please try again.') }}', 'error');
                            }
                            $('#bulkDeleteModal').modal('hide');
                            $('#confirmBulkDelete').prop('disabled', false).text('{{ __('Delete') }}');
                        },
                        error: function(xhr) {
                            let errorMessage = '{{ __('Failed to delete selected users. Please try again.') }}';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            toastrs('{{ __('Error') }}', errorMessage, 'error');
                            $('#bulkDeleteModal').modal('hide');
                            $('#confirmBulkDelete').prop('disabled', false).text('{{ __('Delete') }}');
                        }
                    });
                });
            });
        </script>
        @endpush
    @endif
@endsection
