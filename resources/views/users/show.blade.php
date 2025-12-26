@extends('layouts.app')
@section('title')
    {{ __('messages.user.user_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0  @if(getLogInUser()->language == 'ar') justify-content-start gap-2 @endif">
                <a href="{{ route('users.edit', $user->id) }}">
                <button type="button" class="btn btn-primary @if(getLogInUser()->language == 'ar') ms-4 @else me-4 @endif">{{__('messages.common.edit')}}</button>
                </a>
                <a href="{{ route('users.index') }}">
                <button type="button" class="btn btn-outline-primary @if(getLogInUser()->language == 'ar') float-start @else float-end @endif
                    @if(getLogInUser()->language == 'ar') ms-3 @else me-3 @endif">{{__('messages.common.back')}}</button>
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('users.show_fields')
            <div class="profile-actions mt-4">
                <!-- Add to Home Screen (PWA) -->
                <button id="add-to-home" class="btn btn-primary mb-2">Add to Home Screen</button>
                <!-- Download App -->
                <a href="https://play.google.com/store/apps/details?id=YOUR_APP_ID" class="btn btn-success mb-2" target="_blank">Download App</a>
                <!-- Save to Google Wallet -->
                <button id="save-to-google-wallet" class="btn btn-warning mb-2">Save to Google Wallet</button>
            </div>
        </div>
    </div>
@endsection
<script>
// PWA Add to Home Screen prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    document.getElementById('add-to-home').style.display = 'inline-block';
});
document.getElementById('add-to-home').addEventListener('click', async () => {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        await deferredPrompt.userChoice;
        deferredPrompt = null;
    }
});
// Google Wallet button (placeholder)
document.getElementById('save-to-google-wallet').addEventListener('click', function() {
    alert('Google Wallet integration coming soon!');
});
</script>
