@extends('admin.layouts.dashboard')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | Admin Dashboard</title>
</head>
<body>
@if (session('success'))
<div class="alert alert-success mx-4">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger mx-4">{{ session('error') }}</div>
@endif
<div id="admin-dashboard" class="d-flex">
    <form action="{{ route('admin.animal.create') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-wrap justify-content-center">
        <div class="white-box mr-2">
            @csrf
            <h1>ADD FOODS</h1>
            <div class="form-group">
                <label for="food_name" class="mb-1">Food name</label>
                <input type="text" value="{{ old('food_name') }}" class="form-control" id="food_name" name="food_name" rows="3">
                @error('food_name')
                <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
</div>
</body>
@endsection
