{{-- BUG fix translation only resetting to english from previous language --}}
{{-- SUGGESTION animate the eyes to look around by spinning --}}
@extends('layouts.app')

@section('content')
<h1>404 | {{ __('app.404') }}</h1>
@svg('assets/pet_food_logo_only_icon.svg', 'petfood-404')
@endsection