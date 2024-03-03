@extends('layouts.app')

@section('content')
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pet Food</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('js/autocomplete.js') }}">
</head>
<body>
    <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
        <defs>
              <filter id="smooth-edges"><feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" />    
                  <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                  <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
              </filter>
          </defs>
    </svg>
    <!--USER-INFO-->
    <div class="user">
        <text></text>
    </div>
    <header>
        <h2>What to give/not to give my pet?</h2>
    </header>
    <!-- COMMON PETS -->
    <input type="checkbox" id="categories" class="d-none">
    <div class="common-pets">
        <label for="categories" class="open-btn">
            <div class="arrow arrow-left">◀</div>
            <div class="arrow arrow-right">▶</div>
        </label>
        <a href="" class="triangle active" data-accent="#9b2dda">
            <div>
                <div class="text">Rodents
                    {{-- Mice, Rats, Guinea Pigs, Hamsters, Gerbils --}}
                </div><div class="emoji">🐀
                </div>
            </div>
        </a>
        <a href="" class="triangle active" data-accent="darkred">
            <div>
                <div class="text">Reptiles
                    {{-- Snakes, Turtles, Lizards, Geckos, Bearded Dragons --}}
                </div><div class="emoji">🐢
                </div>
            </div>
        </a>
        <a href="" class="triangle active" data-accent="darkgreen">
            <div>
                <div class="text">Aves
                    {{-- Parrots, Canaries, Finches, Budgerigars, Cockatiels --}}
                </div><div class="emoji">🦜
                </div>
            </div>
        </a>
        <a href="" class="triangle" data-accent="darkred">
            <div>
                <div class="text">Amphibians
                    {{-- Frogs, Toads, Salamanders --}}
                </div><div class="emoji">🐸
                </div>
            </div>
        </a>
        <a href="" class="triangle" data-accent="darkred">
            <div>
                <div class="text">Livestock
                    {{-- Cows, Pigs, Sheep, Goats, Ducks, Chickens (often kept on farms) --}}
                </div><div class="emoji">🐷
                </div>
            </div>
        </a>
        <a href="" class="triangle" data-accent="darkred">
            <div>
                <div class="text">Cats
                </div><div class="emoji">😺
                </div>
            </div>
        </a>
        <a href="" class="triangle" data-accent="darkred">
            <div>
                <div class="text">Dogs
                </div><div class="emoji">🐶
                </div>
            </div>
        </a>
        <a href="" class="triangle" data-accent="darkred">
            <div>
                <div class="text">Hedgehog
                </div><div class="emoji">🦔
                </div>
            </div>
        </a>
        <a href="" class="triangle" data-accent="darkred">
            <div>
                <div class="text">Chinchillas
                </div><div class="emoji">🐹
                </div>
            </div>
        </a>
        <a href="" class="triangle" data-accent="darkred">
            <div>
                <div class="text">Fish
                    {{-- Goldfish, Betta Fish, Guppies, Tetras, Cichlids --}}
                </div><div class="emoji">🐟
                </div>
            </div>
        </a>
        <a href="" class="triangle" data-accent="darkred">
            <div>
                <div class="text">Rabbits
                </div><div class="emoji">🐰
                </div>
            </div>
        </a>
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
<script src="autocomplete.js"></script>
@endsection