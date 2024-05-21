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
        // Retrieve search query and locale from the request
        $searchQuery = $request->input('q');
        $locale = $request->input('locale', app()->getLocale());
    
        // Fetch matching animals from the database based on name or slug and locale
        $results = \DB::table('animals')
            ->where('language', $locale)
            ->where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', "%$searchQuery%")
                      ->orWhere('slug', 'like', "%$searchQuery%");
            })
            ->get();
    
        // Extract relevant data for autocomplete
        $autocompleteData = [];
        foreach ($results as $result) {
            $autocompleteData[] = [
                'rank' => $result->rank,
                'canonicalName' => $result->name,
                'category' => $result->category, // Assuming category is available in the animals table
            ];
        }
    
        // Return the autocomplete data as JSON
        return response()->json($autocompleteData);
    }
    
}
