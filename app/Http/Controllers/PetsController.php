<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPet;

class PetsController extends Controller
{

    public function index()
    {
        $locale = app()->getLocale();
    
        $animals = \DB::table('animals')
            ->where('language', $locale)
            ->pluck('name', 'gbif_id');    
    
        $pets = \DB::table('user_pets')
            ->select('user_pets.*', 'animals.single as species_name')
            ->join('animals', 'user_pets.gbif_id', '=', 'animals.gbif_id')
            ->where('user_id', auth()->id())
            ->where('animals.language', $locale) // Filter pets based on current locale
            ->get();
        
        return view('home', compact('animals', 'pets'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'gbif_id' => 'required',
            'nickname' => 'nullable',
            'filename' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Check if the pet already exists for the user
        $query = UserPet::where('user_id', auth()->id())
                        ->where('gbif_id', $request->gbif_id);
    
        if ($request->filled('nickname')) {
            $query->where('nickname', $request->nickname);
        } else {
            $query->whereNull('nickname');
        }
    
        $existingPet = $query->first();
    
        if ($existingPet) {
            if ($request->filled('nickname')) {
                $errorMessage = 'You already added this animal with the same name.';
            } else {
                $errorMessage = 'You already added this species.';
            }
            return redirect()->back()->withInput()->withErrors(['gbif_id' => $errorMessage]);
        }
    
        // Create a new user pet
        $userPet = new UserPet();
        $userPet->user_id = auth()->id();
        $userPet->gbif_id = $request->gbif_id;
        $userPet->nickname = $request->nickname;
    
        // Handle file upload
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/pet_images', $filename);
            $userPet->filename = 'pet_images/' . $filename;
        }
    
        $userPet->save();
    
        return redirect()->route('home', ['locale' => app()->getLocale()])->with('success', 'Pet added successfully!');
    }

    public function destroy($locale, $id)
    {
        $pet = UserPet::find($id);
        if (!$pet) {
            return redirect()->route('home', ['locale' => app()->getLocale()])->with('error', 'Pet not found!');
        }
        if ($pet->user_id !== auth()->id()) {
            return redirect()->route('home', ['locale' => app()->getLocale()])->with('error', 'Unauthorized action!');
        }
        $pet->delete();
    
        return redirect()->route('home', ['locale' => app()->getLocale()])->with('success', 'Pet deleted successfully!');
    }
    
}
