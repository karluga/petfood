<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Animals;

class HomeController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        return view('home');
    }
    
    public function pets()
    {
        return view('pets');
    }

    public function welcome($locale)
    {
        $animals = Animals::getPopularPets($locale);
        return view('welcome', ['popularPets' => $animals]);
    }

    public function popular($locale, $slug)
    {
        $animals = Animals::getPopularPets($locale);
        $type = Animals::getTypeInfo($locale, $slug);
        // TODO add function to animals that takes all the well-known subtypes of the animal, if possible (for example the chinchilla is already a species by itself)
        // if it is a species by itself, then return the newly made view species.blade.php
        // what counts as species: rank FAMILY
    
        $matchingPopularPet = collect($animals)->firstWhere('slug', $slug);
        if ($matchingPopularPet) {
            $type->emoji = $matchingPopularPet['emoji'];
            $type->hex_color = $matchingPopularPet['hex_color'];
        }
    
        return view('animals', ['popularPets' => $animals, 'type' => $type]);
    }
    public function livestock($locale, $slug)
    {
        $animals = Animals::getPopularPets($locale);
        $livestock = Animals::getLivestock($locale);
        return view('animals', ['popularPets' => $animals, 'livestock' => $livestock]);
    }

    // TODO
    public function species($locale, $id)
    {

        return view('species', ['data' => []]);
    }

}
