<?php

// app/Http/Controllers/AutocompleteController.php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class AutocompleteController extends Controller
{
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
    public function getFoodsForAnimal($gbif_id, Request $request)
    {
        $locale = $request->input('locale', app()->getLocale());
        $searchQuery = $request->input('q');
        $filterSafeFoods = $request->input('safe_only');
        $from = $request->input('from', 0); // Default value is 0
        $to = $request->input('to', 10); // Default value is 7

        if ($from > $to) {
            return response()->json([
                'error' => 'Invalid range: "from" cannot be greater than "to".',
            ], 400); // 400 Bad Request
        }
    
        // Get food data for the specified locale
        $foodsData = $this->getFoodData($gbif_id, $locale, $filterSafeFoods, $searchQuery, $from, $to);
    
        // Fallback to English (en) locale if no data is found
        if ($foodsData->isEmpty()) {
            $foodsData = $this->getFoodData($gbif_id, 'en', $filterSafeFoods, $searchQuery, $from, $to);
        }

        // Check if data is empty and return a 404 error if needed
        if ($foodsData->isEmpty()) {
            return response()->json([
                'error' => 'No food data found for the specified parameters.',
            ], 404); // 404 Not Found
        }
        // Prepare the paginated result
        $paginatedFoods = [
            'from' => $from,
            'to' => $to,
            'gbif_id' => $gbif_id,
            'foods' => $foodsData,
        ];
    
        return response()->json([
            'data' => $paginatedFoods,
        ]);
    }
    
    private function getFoodData($gbif_id, $locale, $filterSafeFoods, $searchQuery, $from, $to)
    {
        $query = \DB::table('food_safety')
            ->select(
                'food_safety.id',
                'food_safety.food_id',
                'foods.food',
                'safety_categories.language',
                'safety_categories.filename',
                'safety_categories.hex_color',
                'safety_categories.name as safety_label'
            )
            ->join('foods', 'food_safety.food_id', '=', 'foods.id')
            ->join('safety_categories', function ($join) use ($locale) {
                $join->on('food_safety.safety_id', '=', 'safety_categories.safety_id')
                     ->where('safety_categories.language', '=', $locale);
            })
            ->where('food_safety.gbif_id', $gbif_id)
            ->where('foods.language', $locale);
    
        // Apply filter for safe foods if requested
        if ($filterSafeFoods) {
            $query->where('food_safety.safety_id', 1); // Assuming safety_id 1 corresponds to safe foods
        }
    
        // Apply search filter if provided
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('foods.food', 'like', "%$searchQuery%")
                    ->orWhere('foods.description', 'like', "%$searchQuery%");
            });
        }
    
        // Apply pagination
        $query->skip($from)->take($to - $from);
    
        return $query->get();
    }
}
