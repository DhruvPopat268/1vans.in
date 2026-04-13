@extends('layouts.app')

@section('content')
<div class="container">
    <h2>View Drawing File</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-4">
        <strong>File Name:</strong> {{ $filename }}
    </div>

    <div class="border p-2">
        <iframe src="{{ url('/api/view-dwg/' . urlencode($filename)) }}" 
                width="100%" 
                height="800px" 
                style="border:1px solid #ccc;">
            Your browser does not support iframes.
        </iframe>
    </div>

    <div class="mt-3">
        <a href="{{ url('/api/view-dwg/' . urlencode($filename)) }}" class="btn btn-primary" target="_blank">
            Download/View DWG File
        </a>
    </div>
</div>
@endsection
