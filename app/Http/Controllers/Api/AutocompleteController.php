<?php

// app/Http/Controllers/AutocompleteController.php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class AutocompleteController extends Controller
{
    // Define static variables for GBIF API
    private $kingdomKey = 101683523;
    private $rank = 'GENUS';
    private $apiUrl = 'https://api.gbif.org/v1/species/search';

    public function autocomplete(Request $request)
    {
        // Retrieve search query from the request
        $searchQuery = $request->input('q');

        // Make a request to the GBIF API
        $response = Http::get($this->apiUrl, [
            'q' => $searchQuery,
            'rank' => $this->rank,
            'kingdomKey' => $this->kingdomKey,
        ]);

        // Parse the response
        $data = $response->json();
        $results = $data['results'] ?? [];

        // Extract relevant data for autocomplete
        $autocompleteData = [];
        foreach ($results as $result) {
            // Extract class and canonicalName
            $class = $result['class'] ?? null;
            $canonicalName = $result['canonicalName'] ?? null;

            // Add to autocomplete data if both class and canonicalName are present
            if ($class !== null && $canonicalName !== null) {
                $autocompleteData[] = [
                    'class' => $class,
                    'canonicalName' => $canonicalName,
                ];
            }
        }

        // Return the autocomplete data as JSON
        return response()->json($autocompleteData);
    }
}
