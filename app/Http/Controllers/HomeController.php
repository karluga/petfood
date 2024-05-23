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
        $slugs = self::getAllSlugs($type->gbif_id, 'popular/');
        
        // Check if the current slug exists in animals table
        $matchingPopularPet = collect($animals)->firstWhere('slug', $slug);
        if ($matchingPopularPet) {
            $type->emoji = $matchingPopularPet['emoji'];
            $type->hex_color = $matchingPopularPet['hex_color'];
        }
    
        return view('animals', [
            'popularPets' => $animals,
            'type' => $type,
            'slugs' => $slugs
        ]);
    }
    
    public function livestock($locale)
    {
        $slugs = 'livestock/';
        $animals = Animals::getPopularPets($locale);
        $livestock = Animals::getLivestock($locale);
        
        return view('animals', [
            'popularPets' => $animals,
            'livestock' => $livestock,
            'slugs' => $slugs
        ]);
    }
    
    public static function getAllSlugs($gbif_id, $prefix)
    {
        $slugs = [];
        foreach (config('languages') as $key => $language) {
            // Translate the slug for each language based on the specified column
            $translatedSlug = DB::table('animals')
                ->select('slug')
                ->where('language', $key)
                ->where('gbif_id', $gbif_id)
                ->first();
            $slugs[$key] = $translatedSlug ? $prefix . $translatedSlug->slug : '';
        }
        return $slugs;
    }
    
    
    // TODO
    public function species($locale, $id)
    {

        return view('species', ['data' => []]);
    }

}
