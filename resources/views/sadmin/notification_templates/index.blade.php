@extends('layouts.app')
@section('title', 'Notification Templates')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Notification Templates</h1>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('notification-templates.create') }}" class="btn btn-success me-2">Add Notification Template</a>
        <form method="POST" action="{{ route('notification-templates.sendTest') }}" class="d-inline-flex align-items-center">
            @csrf
            <input type="email" name="email" class="form-control me-2" placeholder="Test email address" required style="width:220px;">
            <button type="submit" class="btn btn-primary">Send Test Emails</button>
        </form>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($templates as $template)
                    <tr>
                        <td>{{ $template->type }}</td>
                        <td>{{ $template->subject }}</td>
                        <td>
                            <a href="{{ route('notification-templates.edit', $template->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 