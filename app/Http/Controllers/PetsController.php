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
            'filename' => 'nullable',
        ]);

        // Create a new user pet
        $userPet = new UserPet();
        $userPet->user_id = auth()->id();
        $userPet->gbif_id = $request->gbif_id;
        $userPet->nickname = $request->nickname;
        $userPet->filename = $request->filename;
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
