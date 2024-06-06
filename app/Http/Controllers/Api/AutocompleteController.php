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
        $from = $request->input('from', 0);
        $to = $request->input('to', 10);
        $step = $to - $from;

        if ($from > $to) {
            return response()->json([
                'error' => 'Invalid range: "from" cannot be greater than "to".',
            ], 400); // 400 Bad Request
        }
    
        // Get food data for the specified locale
        $foodsData = $this->getFoodData($gbif_id, $locale, $filterSafeFoods, $searchQuery, $from, $to);
    
        // Fallback
        if ($foodsData->isEmpty()) {
            $foodsData = $this->getFoodData($gbif_id, 'en', $filterSafeFoods, $searchQuery, $from, $to);
        }

        if ($foodsData->isEmpty()) {
            $errorMessage = __('app.autocomplete.no_data_for_phrase', ['searchQuery' => $searchQuery], $locale);
            return response()->json([
                'error' => 'No food data found for the specified parameters.',
                'message' => $errorMessage,
                'status' => 404
            ], 404); // 404 Not Found
        }

        $tooManyRequestsMessage = __('app.autocomplete.too_many_requests', [], $locale);
        $paginatedFoods = [
            'from' => $from,
            'to' => $to,
            'gbif_id' => $gbif_id,
            'foods' => $foodsData,
            'too_many_requests_message' => $tooManyRequestsMessage,
        ];

        $isEndOfData = count($foodsData) < $step;
        if ($isEndOfData) {
            $endOfDataMessage = __('app.autocomplete.end_of_data', [], $locale);
            $paginatedFoods['end_of_data_message'] = $endOfDataMessage;
        }

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
            ->join('foods', 'food_safety.food_id', '=', 'foods.food_id')
            ->join('safety_categories', function ($join) use ($locale) {
                $join->on('food_safety.safety_id', '=', 'safety_categories.safety_id')
                     ->where('safety_categories.language', '=', $locale);
            })
            ->where('food_safety.gbif_id', $gbif_id)
            ->where('foods.language', $locale);
    
        // Apply filter for safe foods if requested
        if ($filterSafeFoods) {
            $query->where('food_safety.safety_id', 1); // 1 -> safe
        }
    
        // Apply search filter if provided
        if ($searchQuery) {
            // Starts with letter
            if (strlen($searchQuery) === 1) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('foods.food', '=', $searchQuery)
                        ->orWhere('foods.food', 'like', "$searchQuery%");
                })
                ->orderBy('foods.food', 'asc');
            } else {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('foods.food', 'like', "%$searchQuery%");
                        // ->orWhere('foods.description', 'like', "%$searchQuery%");
                })
                ->orderBy('foods.food', 'asc');
            }
        }
    
        // Apply pagination
        $query->skip($from)->take($to - $from);
    
        // Fetch the data
        $foodsData = $query->get();
    
        // Add path prefix to filename only if filename is not empty or null
        $foodsData = $foodsData->map(function ($food) {
            if (!empty($food->filename)) {
                $food->filename = asset('assets/icons/' . $food->filename);
            }
            return $food;
        });
    
        return $foodsData;
    }
}
