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
    <form action="{{ route('admin.foods.create') }}" method="POST" class="d-flex flex-wrap justify-content-center">
        <div class="white-box mr-2">
            @csrf
            <h1>ADD FOODS</h1>
            <div class="form-group required">
                <label for="language" class="mb-1">Localization</label>
                <select class="form-control" id="language" name="language">
                    @foreach($supportedLanguages as $code => $language)
                        <option value="{{ $code }}" {{ old('language') == $code ? 'selected' : '' }}>{{ $language['name'] }}</option>
                    @endforeach
                </select>
                @error('language')
                    <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group required">
                <label for="food" class="mb-1">Food name</label>
                <input type="text" class="form-control" id="food" name="food" value="{{ old('food') }}">
                @error('food')
                    <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description" class="mb-1">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
</body>
@endsection
