@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ __('app.section.species.name') }}</title>
</head>
<body>
<div id="species">
    <div class="breadcrumbs">
        @svg('assets/pet_food_logo_only_icon.svg', 'petfood-logo-only-icon')
        <a>CLASSES</a>
        <a>AMPHIBIAN</a>
        <a>FROG</a>
    </div>
    <div class="thumbnail-container">
        <img src="" alt="" class="cover-img">
        <div class="canonical-name">
            {{-- if language english --}}
            The
            <span>FROG</span>
        </div>
        <div class="class-name">Amphibian</div>
    </div>
    <div class="white-box">
        <h1>FOOD LIST</h1>
        {{-- Food search with javascript --}}
        <input type="text">
        <h2>Filter <i class="fa-solid fa-filter"></i></h2>
        <input type="checkbox" name="by_popularity" id="by_popularity">
        <label for="by_popularity">By popularity</label>
        <input type="checkbox" name="safe_to_feed" id="food_safety">
        <label for="food_safety">Safe to feed</label>
        <ul id="food_safety">
            <li>
                <div>Carrot</div>
                <div>Safe to feed</div>
                {{-- warning.png, checkmark.png, xmark.png --}}
                <img src="{{ asset('assets/icons/checkmark.png') }}" height="40" alt="Checkmark icon">

            </li>
        </ul>
    </div>
</div>
</body>
@endsection