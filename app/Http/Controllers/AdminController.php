<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
    
            if (isset($data['kingdom']) && $data['kingdom'] !== 'Animalia' && $data['kingdom'] !== 'Metazoa') {
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
        $validator = Validator::make($request->all(), [
            'gbif_id' => 'required|string',
            'single_name' => 'required|string',
            'plural_name' => 'required|string',
            'category' => 'required|in:wild,domestic,exotic',
            'rank' => 'required|string',
            'appearance' => 'nullable|string',
            'food' => 'nullable|string',
            'language' => 'required|string|size:2',
            'parent_id' => 'nullable|string',
            'slug' => [
                'required',
                'string',
                'regex:/^[a-z0-9_-]+$/',
                'unique:animals,slug,' . $request->input('language') . ',language'
            ],
            'images.*' => [
                'image',
                // 'max:10240',
                // MAX 10MB (custom)
                function ($attribute, $value, $fail) use ($request) {
                    $fileSize = $request->file($attribute)->getSize() / 1024 / 1024; // Get file size in MB
                    if ($fileSize > 10) {
                        $fail('The file size <strong title="' . $fileSize*1024*1024 . 'KB">' . number_format($fileSize, 2) . 'MB</strong> exceeds the maximum allowed size of 10MB.');
                    }
                },
                // 'mimes:jpeg,png,jpg',
                // Allowed file types (custom)
                function ($attribute, $value, $fail) use ($request) {
                    $allowedMimes = ['jpeg', 'png', 'jpg'];
                    $uploadedMimeType = $request->file($attribute)->getClientOriginalExtension();
                    
                    if (!in_array($uploadedMimeType, $allowedMimes)) {
                        $fail("The filetype $uploadedMimeType is not allowed. Choose from: " . implode(', ', $allowedMimes) . '.');
                    }
                }
            ]
        ], [
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, dashes, and underscores.',
            'slug.unique' => 'The slug must be unique for the given language.'
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator);
        }
        
        // dd('Uploaded images:', $request->file('images'));
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

                    // Get the full path to the uploaded image
                    $fullImagePath = public_path($imagePath . '/' . $imageName);

                    // Set permissions explicitly after moving the file
                    chmod($fullImagePath, 0755);

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
                    // dd($coverImageId, $insertedId);
                    // dd($coverImageId);
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error inserting image into database: ' . $e->getMessage());
        }
        // dd($coverImageId);

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
            $pet->cover_image_id = 80; // Assign cover image ID
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


    public function showSafeties()
    {
        // Fetch all safety categories where language is 'en'
        $safeties = \DB::table('safety_categories')
            ->where('language', 'en')
            ->pluck('name', 'id');
    
        // Fetch all available food entries
        $foods = \DB::table('foods')
            ->where('language', 'en')
            ->pluck('food', 'food_id');
    
        // Fetch all animals where language is 'en' and pluck gbif_id and name
        $animals = \DB::table('animals')
            ->where('language', 'en')
            ->pluck('name', 'gbif_id');
    
        return view('admin.safeties', compact('safeties', 'foods', 'animals'));
    }
    public function createSafeties(Request $request)
    {
        $request->validate([
            'gbif_id' => 'required|string',
            'safety_id' => 'required',
            'food_id' => 'required',
        ]);
    
        try {
            $existingRecord = \DB::table('food_safety')
                ->where('gbif_id', $request->gbif_id)
                ->where('food_id', $request->food_id)
                ->where('safety_id', $request->safety_id)
                ->first();

            if ($existingRecord) {
                return back()->with('error', 'A safety record with the same entry already exists!');
            }
    
            \DB::table('food_safety')->insert([
                'gbif_id' => $request->gbif_id,
                'food_id' => $request->food_id,
                'safety_id' => $request->safety_id,
            ]);
            
            return back()->with('success', 'Safety record created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating safety record: ' . $e->getMessage());
        }
    }
    
    public function showFoods()
    {
        $supportedLanguages = config('languages');
        return view('admin.foods', compact('supportedLanguages'));
    }

    public function createFoods(Request $request)
    {
        $request->validate([
            'language' => 'required|string|size:2',
            'food' => 'required|string',
            'description' => 'nullable|string',
        ]);
        try {
            $maxFoodId = \DB::table('foods')
                ->where('language', $request->language)
                ->max('food_id');
            $foodId = $maxFoodId + 1;
    
            \DB::table('foods')->insert([
                'language' => $request->language,
                'food_id' => $foodId,
                'food' => $request->food,
                'description' => $request->description,
            ]);
            
            return back()->with('success', 'Food record created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating food record: ' . $e->getMessage());
        }
    }
    
}
