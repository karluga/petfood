<?php

// app/Http/Controllers/AutocompleteController.php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class AutocompleteController extends Controller
{
    protected $foodSafetyTranslations = [
        'safe' => [
            'icon' => 'checkmark.png',
            'hex_color' => '#4EC04E',
        ],
        'dangerous' => [
            'icon' => 'xmark.png',
            'hex_color' => '#D12121',
        ],
        'be_careful' => [
            'icon' => 'warning.png',
            'hex_color' => '#FFA500',
        ],
        'unknown' => [
            'icon' => null,
            'hex_color' => '#808080',
        ],
    ];

    public function autocomplete(Request $request)
    {
        $searchQuery = $request->input('q');
        $locale = $request->input('locale', app()->getLocale());
    
        $results = \DB::table('animals')
            ->where('language', $locale)
            ->where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', "%$searchQuery%")
                      ->orWhere('slug', 'like', "%$searchQuery%");
            })
            ->get();
    
        $data = [];
        foreach ($results as $result) {
            $name = explode('|', $result->name)[0];
            $data[] = [
                'gbif_id' => $result->gbif_id,
                'rank' => $result->rank,
                'name' => $name,
                'category' => $result->category,
            ];
        }
    
        return response()->json([
            'data' => $data,
            'locale' => $locale,
        ]);
    }
    public function getSafeFoodsForAnimal($gbif_id)
    {
        //
    }
}