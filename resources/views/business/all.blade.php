@extends('layouts.admin')
@section('content')
    <h1>All Businesses</h1>
    <ul>
        @foreach($businesses as $business)
            <li>{{ $business->title }} ({{ $business->slug }})</li>
        @endforeach
    </ul>
@endsection 