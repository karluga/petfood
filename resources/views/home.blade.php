@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ __('app.section.my_pets.name') }}</title>
</head>
<body>
@if (session('success'))
<div class="alert alert-success mx-4">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger mx-4">{{ session('error') }}</div>
@endif
<div id="my-pets" class="container">
    <h1>MY PETS</h1>
    <form class="pets-section white-box fs-4" action="{{ route('pets.store', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="gbif_id">Animal Species</label>
                <select class="form-control" id="gbif_id" name="gbif_id" required>
                    @foreach($animals as $gbif_id => $name)
                        <option value="{{ $gbif_id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('gbif_id')
                    <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="nickname">Pet's Name</label>
                <input type="text" name="nickname" class="form-control">
                @error('nickname')
                    <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="filename">Image</label>
                <div class="input-group">
                    <input id="imgInp" type="file" name="filename" class="form-control @error('filename') is-invalid @enderror">
                </div>
                @error('filename')
                    <div class="text-danger fs-5">
                        {{ $message }}
                    </div>
                @enderror
                <img id="imgPreview" class="d-none pet-image mt-2" src="" alt="Pet image">
            </div>
            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary mt-auto">Add Pet</button>
            </div>
        </div>
    </form>
    <div class="alternating-bg mt-3 container">
        @if(!$pets->isEmpty())
        <h2 class="py-2">Pets List</h2>
            @foreach ($pets as $pet)
                <div class="list-item row p-2">
                    <div class="col-md-3"><a href="/{{ app()->getLocale() . '/species/' . $pet->gbif_id }}">{{ $pet->species_name }}</a></div>
                    <div class="col-md-3">{{ $pet->nickname }}</div>
                    <div class="col-md-3">
                        @if ($pet->filename)
                            <img class="pet-image" src="{{ asset('storage/' . $pet->filename) }}" alt="Pet Image">
                        @else
                            No Image
                        @endif
                    </div>
                    <div class="col-md-3">
                        <form action="{{ route('pets.destroy', ['locale' => app()->getLocale(), 'id' => $pet->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return window.confirm('Are you sure you want to delete this pet?')">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <p>No pets added yet.</p>
        @endif
    </div>
</div>

<script>
imgInp.onchange = evt => {
    const [file] = imgInp.files;
    if (file) {
        imgPreview.src = URL.createObjectURL(file);
        imgPreview.classList.remove('d-none');
    }
};
</script>
</body>
@endsection
