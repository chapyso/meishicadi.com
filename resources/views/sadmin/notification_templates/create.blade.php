@extends('layouts.app')
@section('title', 'Add Notification Template')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Add Notification Template</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('notification-templates.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="type" class="form-label">Type (unique key)</label>
                    <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}" required>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" required>
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label">Body</label>
                    <textarea class="form-control wysiwyg" id="body" name="body" rows="10" required>{{ old('body') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="variables" class="form-label">Variables (comma separated, e.g. user_name,order_id)</label>
                    <input type="text" class="form-control" id="variables" name="variables" value="{{ old('variables') }}">
                </div>
                <button type="submit" class="btn btn-success">Create</button>
                <a href="{{ route('notification-templates.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('body');
</script>
@endsection 