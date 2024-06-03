
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
            <h1>ADD SAFETIES</h1>
            <div class="form-group">
                <label for="animal_gbif_id" class="mb-1">Animal Name</label>
                <select class="form-control" id="animal_gbif_id" name="animal_gbif_id">
                    @foreach($animals as $gbif_id => $name)
                        <option value="{{ $gbif_id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('animal_gbif_id')
                <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="food_id" class="mb-1">Food</label>
                <select class="form-control" id="food_id" name="food_id">
                    @foreach($foods as $id => $food)
                        <option value="{{ $id }}">{{ $food }}</option>
                    @endforeach
                </select>
                @error('food_id')
                <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="safety_category" class="mb-1">Safety Category</label>
                <select class="form-control" id="safety_category" name="safety_category">
                    @foreach($safeties as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('safety_category')
                <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>    
</div>
</body>
@endsection