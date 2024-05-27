@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ $species['single'] }}</title>
</head>
<body>
    {{-- {{ dd($data);}} --}}
<div id="species">
    <div class="breadcrumbs mb-3">
        @foreach($data as $key => $species)
            @if($key == 0)
                <div class="rank rank-{{ $key + 1 }}">
                    @svg('assets/pet_food_logo_only_icon.svg', 'petfood-logo-only-icon')
                    <span>{{ strtoupper($species['name']) }}</span>
                </div>
            @else
                {{-- UNFINISHED --}}
                <a href="#{{ $species['slug'] }}" class="rank rank-{{ $key + 1 }}">{{ strtoupper($species['single']) }}</a>
            @endif
        @endforeach
    </div>
    <div class="container d-flex p-0">
        <div class="white-box mr-3 d-flex flex-column">
            <h1>FOOD LIST</h1>
            {{-- Food search with javascript --}}
            <div>
                <input type="text" id="search_species_food">
                <span id="search_clear">X</span>
            </div>
            <div>
                <h2>Filter <i class="fa-solid fa-filter"></i></h2>
                <input type="checkbox" name="by_popularity" id="by_popularity">
                <label for="by_popularity">By popularity</label>
                <input type="checkbox" name="safe_to_feed" id="food_safety">
                <label for="food_safety">Safe to feed</label>
            </div>
            <ul id="food_safety" class="mt-2">
                <li>
                    <div>Carrot</div>
                    <div class="item" style="background: #4EC04E">
                        <span class="mr-3">{{ __('app.section.species.food_safety.safe_to_feed') }}</span>
                        <img src="{{ asset('assets/icons/checkmark.png') }}" height="40" alt="Checkmark icon">
                    </div>
                    <a href="#">
                        Read more
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                    
                    {{-- warning.png, checkmark.png, xmark.png --}}
                    {{-- <div>No data</div> --}}
                </li>
            </ul>
            <div class="text-center mt-3">
                <button id="load_more">
                    <span>▼</span>
                    LOAD MORE
                    <span>▼</span>
                </button>                
            </div>

        </div>
        <div class="thumbnail-container mr-3">
            <img src="{{ asset('/assets/images/' . $species['file_path']) }}" alt="" class="cover-img">
            <div class="canonical-name">
                @if($locale == 'en')
                    <span class="the">The</span>
                    <span>{{ $species['single'] }}</span>
                @elseif($locale == 'lv')
                    <span class="the">Kungs</span>
                    <span>{{ $species['single']  }}</span>
                @else
                    <span>{{ $species['single']  }}</span>
                @endif
            </div>
            @if($class)
                <div class="class-name">{{ $class }}</div>
            @endif
        </div>
    </div>
</div>
</body>
@endsection