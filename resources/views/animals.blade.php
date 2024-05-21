@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ isset($type->name) ? $type->name : (trans('app.navigation.livestock.name') ?? 'Livestock') }}</title>
</head>
<body>
<div id="popular-pets" class="d-flex">
<div class="column-left">
    <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
        <defs>
            <filter id="smooth-edges"><feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" />    
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
            </filter>
        </defs>
    </svg>
    <input type="checkbox" id="categories" class="d-none">
    <div class="common-pets-container popular">
        <label for="categories" class="open-btn">
            <div class="arrow arrow-left">◀</div>
            <div class="arrow arrow-right">▶</div>
        </label>
        <h1 id="p-m-title">COMMON PETS</h1>
        <div class="common-pets">
            @foreach($popularPets as $popularPet)
            <a href="/{{ app()->getLocale() . '/popular/' . $popularPet['slug'] }}" class="triangle {{ (isset($type) && $popularPet['slug'] == optional($type)->slug) ? 'active' : '' }}" style="--accent-color: {{ $popularPet['hex_color'] }}">
                <div>
                    <div class="text">{{ $popularPet['name'] }}</div>
                    <div class="emoji">{{ $popularPet['emoji'] }}</div>
                </div>
            </a>
            @endforeach        
            {{-- HARDCODED --}}
            <a href="/{{ app()->getLocale() . '/' . (trans('app.navigation.livestock.slug') ?? 'livestock') }}" class="triangle {{ request()->route()->getName() == 'livestock' ? 'active' : '' }}" style="--accent-color: #c32070">
                <div>
                    <div class="text">{{ trans('app.navigation.livestock.name') ?? 'Livestock' }}</div>
                    <div class="emoji">🐷</div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="column-right mx-3">
    @if(request()->route()->getName() === 'popular')
        @include('popular')
    @else
        @include('livestock')
    @endif
</div>
</div>
</body>
@endsection