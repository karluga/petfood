<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Animals extends Model
{
    use HasFactory;
    public const API_URL = 'https://api.gbif.org/v1/species/'; 
    public const LIVESTOCK_IDS = [ // all rank FAMILY or GENUS or SPECIES or SUBSPECIES
        199467270, //rabbit
        180179873, //cattle|bos
        209386215, //pigs|swine
        177669418, //sheep|ovis aires
        116893048, //goat|capra hircus
        160796498, //duck|anas platyrhynchos
        217145891, //chickens|gallus gallus
        217127894, //horse|equus ferus caballus
    ];

    public static function getPopularPets($locale)
    {
        // Reset popular items each day
        return Cache::remember('popular_pets_' . $locale, 86400, function () use ($locale) {
            $commonItems = DB::table('common')->select('gbif_id', 'emoji', 'hex_color')->where('on_display', 1)->get();
            $animals = [];

            foreach ($commonItems as $item) {
                $animal = DB::table('animals')
                    ->select('name', 'slug')
                    ->where('gbif_id', $item->gbif_id)
                    ->where('language', $locale)
                    ->first();

                // fallback
                if (!$animal) {
                    $animal = DB::table('animals')
                        ->select('name', 'slug')
                        ->where('gbif_id', $item->gbif_id)
                        ->where('language', 'en')
                        ->first();
                }

                if ($animal) {
                    // Extract the first name if there are multiple names like 'Aves|Birds'
                    $animalName = explode('|', $animal->name)[0];

                    $animals[] = [
                        'name' => $animalName,
                        'slug' => $animal->slug,
                        'emoji' => $item->emoji,
                        'hex_color' => $item->hex_color,
                    ];
                }
            }

            return $animals;
        });
    }

    public static function getTypeInfo($locale, $type)
    {
        $typeInfo = DB::table('animals')
            ->select('gbif_id', 'name', 'slug', 'rank', 'cover_image_id', 'appearance', 'food')            
            ->where('slug', $type)
            ->where('language', $locale)
            ->first();
    
        // fallback
        if (!$typeInfo) {
            $typeInfo = DB::table('animals')
                ->select('gbif_id', 'name', 'slug', 'rank', 'cover_image_id', 'appearance', 'food')
                ->where('slug', $type)
                ->where('language', 'en')
                ->first();
        }
    
        if ($typeInfo) {
            $typeName = explode('|', $typeInfo->name)[0];
            $typeInfo->name = $typeName;
        }
    
        return $typeInfo;
    }
    public static function getLivestock($locale)
    {
        $cacheKey = 'livestock_' . $locale;
    
        // Check if data is cached
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
    
        // Fetch animals where gbif_id is in LIVESTOCK_IDS
        $livestockItems = DB::table('animals')
            ->whereIn('gbif_id', self::LIVESTOCK_IDS)
            ->where('language', $locale)
            ->get();
    
        // Fallback to English if no animals found in the specified locale
        if ($livestockItems->isEmpty()) {
            $livestockItems = DB::table('animals')
                ->whereIn('gbif_id', self::LIVESTOCK_IDS)
                ->where('language', 'en')
                ->get();
        }
    
        $livestock = [];
    
        foreach ($livestockItems as $item) {
            $name = explode('|', $item->name)[0];
    
            // Make HTTP request to GBIF API to get animal details
            $response = \Http::get(self::API_URL . $item->gbif_id);
    
            if ($response->ok()) {
                $order = $response->json()['order'];
            } else {
                // If the request fails, fallback to a default value
                $order = 'Unknown';
            }
    
            $filename = DB::table('animal_pictures')
            ->where('id', $item->cover_image_id)
            ->value('filename');

            // Group animals by their order and include filename
            $livestock[$order][] = [
                'gbif_id' => $item->gbif_id,
                'name' => $name,
                'slug' => $item->slug,
                'order' => $order,
                'file_path' => $item->gbif_id . '/' . $filename,
            ];
        }
    
        // Cache the data
        Cache::put($cacheKey, $livestock, now()->addHours(24));
    
        return $livestock;
    }
    protected function getParentRankData($locale, $gbif_id)
    {
        $speciesData = [];
        $currentData = $this->getSpeciesData($locale, $gbif_id);
        // do until no more data
        while ($currentData) {
            $speciesData[] = $currentData;
            $parentData = $this->getSpeciesData($locale, $currentData['parent_id']);
            if ($parentData) {
                $currentData = $parentData;
            } else {
                break;
            }
        }
        // sort from largest to smallest
        $speciesData = array_reverse($speciesData);
        return $speciesData;
    }
    protected function getSpeciesData($locale, $gbif_id)
    {
        $speciesData = DB::table('animals')
            ->select('gbif_id', 'name', 'single', 'slug', 'rank', 'cover_image_id', 'appearance', 'food', 'parent_id')
            ->where('gbif_id', $gbif_id)
            ->where('language', $locale)
            ->first();
    
        // Fallback
        if (!$speciesData) {
            $speciesData = DB::table('animals')
                ->select('gbif_id', 'name', 'single', 'slug', 'rank', 'cover_image_id', 'appearance', 'food', 'parent_id')
                ->where('gbif_id', $gbif_id)
                ->where('language', 'en')
                ->first();
        }
    
        // Modify name and single fields to cut off the first part
        if ($speciesData) {
            $speciesData->name = explode('|', $speciesData->name)[0];
            $speciesData->single = explode('|', $speciesData->single)[0];

            $filename = DB::table('animal_pictures')
                ->where('id', $speciesData->cover_image_id)
                ->value('filename');

            $speciesData->file_path = $gbif_id . '/' . $filename;
        }
    
        return $speciesData ? (array) $speciesData : null;
    }
    protected function getAllDescendants($locale, $gbif_id)
    {
        $descendantsData = [];
        $currentData = $this->getDescendantsData($locale, $gbif_id);
    
        // Iterate until there are no more descendants
        while ($currentData) {
            $descendantsData[] = $currentData;
    
            // Get the gbif_id of the first object in $currentData
            $currentGbifId = $currentData[0]->gbif_id;
    
            // Fetch children data using the gbif_id
            $childrenData = $this->getChildrenData($locale, $currentGbifId);
    
            // If there are children, update $currentData to the children and continue
            if (!empty($childrenData)) {
                $currentData = $childrenData;
            } else {
                break; // No more descendants, exit loop
            }
        }
    
        return $descendantsData;
    }
    
    protected function getDescendantsData($locale, $gbif_id)
    {
        // Retrieve data of the specified GBIF ID and include parent name
        $descendantsData = DB::table('animals')
            ->select('gbif_id', 'name', 'single', 'slug', 'rank', 'cover_image_id', 'appearance', 'food', 'parent_id',
                DB::raw('(SELECT name FROM animals AS parent WHERE parent.gbif_id = animals.parent_id LIMIT 1) AS parent_name'))
            ->where('parent_id', $gbif_id)
            ->where('language', $locale)
            ->get();
    
        // Fallback to English if data not found in the specified locale
        if ($descendantsData->isEmpty()) {
            $descendantsData = DB::table('animals')
                ->select('gbif_id', 'name', 'single', 'slug', 'rank', 'cover_image_id', 'appearance', 'food', 'parent_id',
                    DB::raw('(SELECT name FROM animals AS parent WHERE parent.gbif_id = animals.parent_id LIMIT 1) AS parent_name'))
                ->where('parent_id', $gbif_id)
                ->where('language', 'en')
                ->get();
        }
    
        // Retrieve filenames of cover images
        foreach ($descendantsData as $key => $data) {
            $filename = DB::table('animal_pictures')
                ->where('id', $data->cover_image_id)
                ->value('filename');
            $descendantsData[$key]->filename = $filename;
        }
    
        // Modify name and single fields to cut off the first part
        foreach ($descendantsData as $key => $data) {
            $descendantsData[$key]->name = explode('|', $data->name)[0];
            $descendantsData[$key]->single = explode('|', $data->single)[0];
        }
    
        return $descendantsData->toArray();
    }
    
    protected function getChildrenData($locale, $parent_gbif_id)
    {
        // Retrieve data of the children of the specified parent GBIF ID and include parent name
        $childrenData = DB::table('animals')
            ->select('gbif_id', 'name', 'single', 'slug', 'rank', 'cover_image_id', 'appearance', 'food', 'parent_id',
                DB::raw('(SELECT name FROM animals AS parent WHERE parent.gbif_id = animals.parent_id) AS parent_name'))
            ->where('parent_id', $parent_gbif_id)
            ->where('language', $locale)
            ->get();
    
        // Fallback to English if data not found in the specified locale
        if ($childrenData->isEmpty()) {
            $childrenData = DB::table('animals')
                ->select('gbif_id', 'name', 'single', 'slug', 'rank', 'cover_image_id', 'appearance', 'food', 'parent_id',
                    DB::raw('(SELECT name FROM animals AS parent WHERE parent.gbif_id = animals.parent_id) AS parent_name'))
                ->where('parent_id', $parent_gbif_id)
                ->where('language', 'en')
                ->get();
        }
    
        // Retrieve filenames of cover images
        foreach ($childrenData as $key => $data) {
            $filename = DB::table('animal_pictures')
                ->where('id', $data->cover_image_id)
                ->value('filename');
            $childrenData[$key]->filename = $filename;
        }
    
        // Modify name and single fields to cut off the first part
        foreach ($childrenData as $key => $data) {
            $childrenData[$key]->name = explode('|', $data->name)[0];
            $childrenData[$key]->single = explode('|', $data->single)[0];
        }
    
        return $childrenData->toArray();
    }
    
    

}
