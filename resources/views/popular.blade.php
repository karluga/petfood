@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ $type->name }}</title>
</head>
<body>
<div id="popular-pets">
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
    <div class="common-pets">
        <h1 id="p-title">COMMON PETS</h1>
        <h1 id="p-m-title">{{ $type->name }}</h1>
        <label for="categories" class="open-btn">
            <div class="arrow arrow-left">◀</div>
            <div class="arrow arrow-right">▶</div>
        </label>
        @foreach($popularPets as $popularPet)
        <a href="{{ $popularPet['slug'] }}" class="triangle {{ ($popularPet['slug'] == $type->slug) ? 'active' : '' }}" style="--accent-color: {{ $popularPet['hex_color'] }}">
            <div>
                <div class="text">{{ $popularPet['name'] }}</div>
                <div class="emoji">{{ $popularPet['emoji'] }}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>
<div class="column-right">

</div>
</div>
</body>
@endsection