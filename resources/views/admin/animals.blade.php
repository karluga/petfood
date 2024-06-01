@extends('admin.layouts.dashboard')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | Admin Dashboard</title>
</head>
<body>
    <div id="admin-dashboard" class="d-flex">
        <div class="white-box">
            <form action="{{ route('admin.animal.create') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="gbif_id" class="mb-1">
                        <span>GBIF ID from </span>
                        <a href="https://api.gbif.org/v1/species/search?q=chinchilla">
                            @svg('assets/icons/gbif-logo.svg', 'gbif-logo')
                        </a>
                    </label>
                    <div class="input-group">
                        <input type="text" value="{{ isset($data['key']) ? $data['key'] : '' }}" class="form-control" id="gbif_id" name="gbif_id">
                        <button type="submit" name="autofill" class="btn btn-primary d-flex" id="autofillBtn">Autofill</button>
                    </div>
                    @error('gbif_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
    
                <div class="form-group">
                    <label for="name" class="mb-1">Name</label>
                    <div class="input-group">
                        <input type="text" value="{{ isset($data['canonicalName']) ? $data['canonicalName'] : '' }}" class="form-control" id="single_name" name="single_name" placeholder="Single">
                        <input type="text" class="form-control" id="plural_name" name="plural_name" placeholder="Plural">
                    </div>
                </div>            
                <div class="form-group">
                    <label for="language" class="mb-1">Localization</label>
                    <select class="form-control" id="language" name="language">
                        @foreach($supportedLanguages as $code => $language)
                            <option value="{{ $code }}">{{ $language['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="category" class="mb-1">Category</label>
                    <select class="form-control" id="category" name="category">
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="rank" class="mb-1">Rank</label>
                    <select class="form-control" id="rank" name="rank">
                        @foreach($ranks as $rank)
                            <option value="{{ $rank }}" {{ isset($data) && $data['rank'] === $rank ? 'selected' : '' }}>{{ $rank }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="appearance" class="mb-1">Appearance</label>
                    <textarea class="form-control" id="appearance" name="appearance" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="food" class="mb-1">Food</label>
                    <textarea class="form-control" id="food" name="food" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</body>
@endsection
