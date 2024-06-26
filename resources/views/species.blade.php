@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ $species['single'] }}</title>
    <script src="{{ asset('js/load_foods.js') }}" defer></script>
</head>
<body>
<div id="species">
    <div class="breadcrumbs mb-3">
        @php
            $alternateRank = 2;
        @endphp
        @foreach($data as $key => $species)
            @if($key == 0)
                <div class="rank rank-{{ $key + 1 }}">
                    @svg('assets/pet_food_logo_only_icon.svg', 'petfood-logo-only-icon')
                    <a href="/{{ app()->getLocale() . '/species/' . $species['gbif_id'] }}" class="rank rank-{{ $key + 1 }} text-uppercase">{{ $species['name'] }}</a>
                </div>
            @else
                @php
                    $currentRank = $alternateRank;
                    $alternateRank = ($currentRank == 2) ? 3 : 2;
                @endphp
                <a href="/{{ app()->getLocale() . '/species/' . $species['gbif_id'] }}" class="rank rank-{{ $currentRank }} text-uppercase">{{ $species['single'] }}</a>
            @endif
        @endforeach
    </div>
    <div class="container d-flex p-0 flex-wrap">
        <div class="white-box mr-3 d-flex flex-column position-relative">
            <div id="food-list-loader" class="horizontal-loader"></div>
            <h1>{{ __('app.section.species.food_list_title') }}</h1>
            {{-- Food search with javascript --}}
            <div>
                <input type="text" id="search_species_food" placeholder="{{ __('app.section.species.search_placeholder') }}">
                <span id="search_clear">X</span>
            </div>
            <div>
                <h2>{{ __('app.section.species.filter') }} <i class="fa-solid fa-filter"></i></h2>
                {{-- <input type="checkbox" name="by_popularity" id="by_popularity">
                <label for="by_popularity">By popularity</label> --}}
                <input type="checkbox" name="safe_to_feed" id="safe_to_feed">
                <label for="safe_to_feed">{{ __('app.section.species.food_safety.safe_to_feed') }}</label>
            </div>
            <ul id="food_list_container" class="mt-2"> <!-- Changed ID to 'food_list' -->
                <!-- Food items will be dynamically added here -->
            </ul>
            <button id="load_more" class="mx-auto">
                <span>▼</span>
                {{ __('app.section.species.load_more') }}
                <span>▼</span>
            </button>
        </div>
        <div class="thumbnail-container">
            <img src="{{ $species['file_path'] ? $species['file_path'] : '/assets/noimg.jpg' }}" alt="" class="cover-img">
            <div class="canonical-name">
                @if($locale == 'lv' || $locale == 'en')
                    <span class="the">{{ __('app.section.species.the') }}</span>
                    <span>{{ $species['single'] }}</span>
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