@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | Welcome</title>
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
    <header>
        <h2>What to give/not to give my pet?</h2>
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
            <a href="{{ route('livestock', ['locale' => app()->getLocale(), 'livestock' => __('app.navigation.livestock.slug')]) }}" class="triangle" style="--accent-color: darkred">
                <div>
                    <div class="text">{{ __('app.navigation.livestock.name') }}</div>
                    <div class="emoji">🐷</div>
                </div>
            </a>
        </div>
    </div>

    <!-- SEARCH BAR -->
    <div class="search-dropdown">
        <input type="text" name="search_box" placeholder="Write your pets species here..." id="search_box" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onkeyup="javascript:load_data(this.value)" onfocus="javascript:load_search_history()" />
        <span id="search_result"></span>
    </div>
    @guest
    <div class="benefits">
        <div class="title">
            <span>
                Benefits to<!-- sign up -->
            </span>
            <a onclick="signUp()" class="white-txt" id="link">Sign Up</a>            
        </div>
        <ol>
            <li>
                You get to input your animals on the created profile, therefore quickly find the things you want to feed them.
            </li>
            <li>
                If we verify you as a pet owner, we will give you the rights to edit or create the websites content alongside others. You will get one or more glorified title under your profile based on which pets you own.
            </li>
            <div>
                image
            </div>
            <li>
                You get to make suggestions for any content changes or updates.
            </li>
        </ol>
    </div>
    @endguest
</body>
@endsection
