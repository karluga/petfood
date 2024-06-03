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

        // Add validation rules for new inputs
        $request->validate([
            'gbif_id' => 'required|string',
            'single_name' => 'required|string',
            'plural_name' => 'required|string',
            'category' => 'required|in:wild,domestic,exotic',
            'rank' => 'required|string',
            'appearance' => 'nullable|string',
            'food' => 'nullable|string',
            'language' => 'required|string',
            'parent_id' => 'nullable|string',
            'slug' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle image upload
        $imagePaths = [];
        $coverImageId = null;
        
        try {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    // Generate filename based on whether single name is provided or not
                    $imageName = $request->single ? $request->single . ($index + 1) : 'animal' . ($index + 1);
        
                    // Check if single name column has value and modify filename accordingly
                    if (!empty($request->single_name)) {
                        $imageName = $request->single_name . ($index + 1);
                    }
        
                    // Append original uploaded image extension
                    $imageName .= '.' . $image->getClientOriginalExtension();
        
                    // Move image to appropriate directory
                    $imagePath = 'assets/images/' . $request->gbif_id;
                    $image->move(public_path($imagePath), $imageName);
        
                    // Store image path in array
                    $imagePaths[] = $imagePath . '/' . $imageName;
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error uploading image: ' . $e->getMessage());
        }
        
        // Create animal pictures records
        try {
            foreach ($imagePaths as $imagePath) {
                // Extract filename from image path
                $filename = basename($imagePath);
                $insertedId = \DB::table('animal_pictures')->insertGetId([
                    'gbif_id' => $request->gbif_id,
                    'filename' => $filename
                ]);
                if ($request->has('cover_image') && $request->cover_image == $index) {
                    $coverImageId = $insertedId; // Store the cover image ID
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error inserting image into database: ' . $e->getMessage());
        }
        
        // Create animal record
        try {
            $pet = new Animal();
            $pet->gbif_id = $request->gbif_id;
            $pet->name = $request->plural_name;
            $pet->single = $request->single_name;
            $pet->category = $request->category;
            $pet->rank = $request->rank;
            $pet->appearance = $request->appearance;
            $pet->food = $request->food;
            $pet->language = $request->language;
            $pet->parent_id = $request->parent_id;
            $pet->slug = $request->slug;
            $pet->cover_image_id = $coverImageId; // Assign cover image ID
            $pet->save();
        } catch (\Exception $e) {
            return back()->with('error', 'Error saving pet record: ' . $e->getMessage());
        }
        
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
