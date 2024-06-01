<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Animal;

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
        $animals = Animal::getPopularPets($locale);
        return view('welcome', ['popularPets' => $animals]);
    }
    public function popular($locale, $slug)
    {
        $type = Animal::getTypeInfo($locale, $slug);
        if (empty($type)) {
            abort(404);
        }
        // Fetch the popular pet type information
        $animals = Animal::getPopularPets($locale);
        $slugs = self::getAllSlugs($type->gbif_id, 'popular/');
        
        // Check if the current slug exists in the animals table
        $matchingPopularPet = collect($animals)->firstWhere('slug', $slug);
        if ($matchingPopularPet) {
            $type->emoji = $matchingPopularPet['emoji'];
            $type->hex_color = $matchingPopularPet['hex_color'];
        }

        // Fetch descendants of the popular pet
        $descendants = Animal::getAllDescendants($locale, $type->gbif_id);
        if (empty($descendants)) {
            abort(404);
        }
        // Rank of the first closest descendant
        $closestDescendant = $descendants[0]['closestDescendant'];
        $closestDescendantRank = $descendants[0]['descendants'][0]->rank; // Get rank from the first descendant
        
        // Set the title based on the rank with translations
        $title = __('app.animals.ranks.' . $closestDescendantRank);
        
        // If translation not found, default to the rank itself
        if ($title === 'app.animals.ranks.' . $closestDescendantRank) {
            $title = $closestDescendantRank;
        }
        
        return view('animals', [
            'popularPets' => $animals,
            'type' => $type,
            'slugs' => $slugs,
            'descendants' => $descendants,
            'closestDescendantRank' => $closestDescendantRank,
            'title' => $title,
        ]);
    }
    
    
    
    public function livestock($locale)
    {
        $slugs = 'livestock/';
        $animals = Animal::getPopularPets($locale);
        $livestock = Animal::getLivestock($locale);
        
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
    public function species($locale, $gbif_id)
    {
        $slugs = 'species/' . $gbif_id;
        $speciesData = Animal::getParentRankData($locale, $gbif_id);
        if (empty($speciesData)) {
            abort(404);
        }
        $species = end($speciesData);
        $class = '';
        
        foreach ($speciesData as $tier) {
            if ($tier['rank'] === 'CLASS') {
                $class = $tier['single'];
                break;
            }
        }
        
        return view('species', ['locale' => $locale, 'data' => $speciesData, 'species' => $species, 'class' => $class, 'slugs' => $slugs]);
    }
}
