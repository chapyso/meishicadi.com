@extends('layouts.app')
@section('title', 'Edit Notification Template')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4 text-center">Edit Notification Template</h1>
    <div class="row justify-content-center">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm" style="margin-bottom: 2rem;">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('notification-templates.update', $template->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Subject<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $template->subject) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="variables" class="form-label">Variables (comma separated)</label>
                                @php
                                    $vars = $template->variables;
                                    if (is_array($vars)) {
                                        $vars = implode(',', $vars);
                                    } elseif (is_string($vars)) {
                                        $try = json_decode($vars, true);
                                        if (is_array($try)) {
                                            $vars = implode(',', $try);
                                        }
                                    }
                                @endphp
                                <input type="text" class="form-control" id="variables" name="variables" value="{{ old('variables', $vars) }}" placeholder="e.g. user_name,order_id">
                                <small class="form-text text-muted">Use these variables in the subject/body as <code>{variable}</code></small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Body<span class="text-danger">*</span></label>
                            <textarea class="form-control wysiwyg" id="body" name="body" rows="8" required>{{ old('body', $template->body) }}</textarea>
                        </div>
                        <div class="d-flex justify-content-start gap-2">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ route('notification-templates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    if (window.CKEDITOR && CKEDITOR.instances.body) {
        CKEDITOR.instances.body.destroy(true);
    }
    CKEDITOR.replace('body');
</script>
@endsection 