<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;

class AdminController extends Controller
{
    public const API_URL = 'https://api.gbif.org/v1/species/';

    public function showForm()
    {
        $ranks = Animal::getEnumValues('rank');
        $categories = Animal::getEnumValues('category');
        $supportedLanguages = config('languages');

        return view('admin.animals', compact('ranks', 'categories', 'supportedLanguages'));
    }

    public function createSpecies(Request $request)
    {
        if ($this->checkExistingPet($request->gbif_id, $request->language)) {
            return back()->withErrors(['gbif_id' => 'A pet with the same GBIF ID and locale already exists.']);
        }
        // Check if the request was submitted by the Autofill button
        if ($request->has('autofill')) {
            // Fetch data from GBIF API
            $gbifId = $request->input('gbif_id');
            if (empty($gbifId)) {
                return back()->withErrors(['gbif_id' => 'GBIF ID is required.']);
            }
    
            $response = \Http::get(self::API_URL . $gbifId);
    
            if ($response->failed()) {
                return back()->withErrors(['gbif_id' => 'Failed to fetch data from GBIF API.']);
            }
    
            $data = $response->json();
            if (!is_array($data)) {
                return back()->withErrors(['gbif_id' => 'Invalid response from GBIF API.']);
            }
    
            if (isset($data['kingdom']) && $data['kingdom'] !== 'Animalia') {
                return back()->withErrors(['gbif_id' => 'The species has to be an animal']);
            }
    
            // Pass the retrieved data to the request
            $request->merge(['data' => $data]);
    
            // Render the view with the data
            $ranks = Animal::getEnumValues('rank');
            $categories = Animal::getEnumValues('category');
            $supportedLanguages = config('languages');
    
            return view('admin.animals', [
                'ranks' => $ranks, 
                'categories' => $categories, 
                'supportedLanguages' => $supportedLanguages, 
                'data' => $data, 
                'gbifId' => $gbifId
            ]);
        }
    
        // Validate the request and create the species
        $request->validate([
            'gbif_id' => 'required|string',
            'name' => 'required|string',
            'single' => 'required|string',
            'category' => 'required|in:wild,domestic,exotic',
            'rank' => 'required|string',
            'appearance' => 'nullable|string',
            'food' => 'nullable|string',
            'language' => 'required|string'
        ]);
    
    
        // If no existing pet, create a new one
        $pet = new Animal();
        $pet->gbif_id = $request->gbif_id;
        $pet->name = $request->name;
        $pet->single = $request->single;
        $pet->category = $request->category;
        $pet->rank = $request->rank;
        $pet->appearance = $request->appearance;
        $pet->food = $request->food;
        $pet->language = $request->language;
        $pet->save();
    
        // Redirect back to the dashboard with success message
        return redirect()->route('admin.animal.index')->with('success', 'Pet added successfully.');
    }
    

    /**
     * Check if a pet with the given GBIF ID and language exists.
     *
     * @param string $gbifId
     * @param string $language
     * @return bool
     */
    private function checkExistingPet($gbifId, $language)
    {
        return Animal::where('gbif_id', $gbifId)
            ->where('language', $language)
            ->exists();
    }
}
