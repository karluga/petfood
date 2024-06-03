@php
    $translations = [
        // autocomplete.js
        'your_recent_searches' => __('app.autocomplete.your_recent_searches'),
        'something_went_wrong' => __('app.autocomplete.something_went_wrong'),
        'no_data_found' => __('app.autocomplete.no_data_found'),
        'too_many_requests' => __('app.autocomplete.too_many_requests'),
        'min_characters' => __('app.autocomplete.min_characters'),
    ];
@endphp
<script>
    const t = @json($translations);
</script>
@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ __('app.welcome.welcome') }}</title>
    <script src="{{ asset('js/autocomplete.js') }}" defer></script>
</head>
<body id="welcome">
    <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
        <defs>
              <filter id="smooth-edges"><feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" />    
                  <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                  <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
              </filter>
          </defs>
    </svg>
    @if (session('success'))
    <div class="alert alert-success mx-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger mx-4">{{ session('error') }}</div>
    @endif
    <header>
        <h2>{{ __('app.welcome.search_title') }}</h2>
    </header>
    <!-- COMMON PETS -->
    <input type="checkbox" id="categories" class="d-none">
    <div class="common-pets-container">
        <label for="categories" class="open-btn">
            <div class="arrow arrow-left">◀</div>
            <div class="arrow arrow-right">▶</div>
        </label>
        <div class="common-pets">
            @foreach($popularPets as $popularPet)
            <a href="/{{ app()->getLocale() . '/popular/' . $popularPet['slug'] }}" class="triangle" style="--accent-color: {{ $popularPet['hex_color'] }}">
                <div>
                    <div class="text">{{ $popularPet['name'] }}</div>
                    <div class="emoji">{{ $popularPet['emoji'] }}</div>
                </div>
            </a>
            @endforeach
            {{-- HARDCODED --}}
            <a href="{{ route('livestock', ['locale' => app()->getLocale()]) }}" class="triangle" style="--accent-color: #c32070">
                <div>
                    <div class="text">{{ __('app.navigation.livestock.name') }}</div>
                    <div class="emoji">🐷</div>
                </div>
            </a>
        </div>
    </div>

    <!-- SEARCH BAR -->
    <div class="search-dropdown">
        <div id="search-loader" class="horizontal-loader"></div>
        <input type="text" name="search_box" placeholder="{{ __('app.welcome.search_placeholder') }}" id="search_box" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
        <span id="search_result"></span>
    </div>
    @guest
    <div class="benefits">
        <div class="title">
            <span>
                {{ __('app.welcome.benefits_to') }}<!-- sign up -->
            </span>
            <a onclick="signUp()" class="white-txt" id="link"> {{ __('app.welcome.register') }}</a>            
        </div>
        <ol>
            <li>
                {{ __('app.welcome.benefit_1') }}
            </li>
            <li>
                {{ __('app.welcome.benefit_2') }}
            </li>
            <div>
                <img src="" alt="">
            </div>
            <li>
                {{ __('app.welcome.benefit_3') }}
            </li>
        </ol>
    </div>
    @endguest
</body>
@endsection
