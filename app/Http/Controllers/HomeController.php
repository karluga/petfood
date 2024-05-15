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
    
        // Pass the animals array to the view
        return view('welcome', ['popularPets' => $animals]);
    }

    public function popular($locale, $slug)
    {
        $animals = Animals::getPopularPets($locale);
        $type = Animals::getTypeInfo($locale, $slug);
        return view('popular', ['popularPets' => $animals, 'type' => $type]);
    }
}
