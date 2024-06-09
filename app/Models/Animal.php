<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Animal extends Model
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
    public const SILHOUETTE_MAPPING = [
        ['gbif_id' => 116891947, 'rank' => 'CLASS', 'name' => 'Mammals', 'image_path' => '/assets/silhouettes/cow.png'],
        ['gbif_id' => 194431376, 'rank' => 'INFRAPHYLUM', 'name' => 'Fish', 'image_path' => '/assets/silhouettes/codfish.png'],
        ['gbif_id' => null, 'rank' => null, 'name' => 'Crustaceans', 'image_path' => '/assets/silhouettes/crab.png'],
        ['gbif_id' => null, 'rank' => null, 'name' => 'Insects', 'image_path' => '/assets/silhouettes/dragonfly.png'],
        ['gbif_id' => 135226770, 'rank' => 'CLASS', 'name' => 'Amphibians', 'image_path' => '/assets/silhouettes/frog.png'],
        ['gbif_id' => 115058156, 'rank' => 'ORDER', 'name' => 'Reptiles', 'image_path' => '/assets/silhouettes/rattlesnake.png'],
        ['gbif_id' => null, 'rank' => null, 'name' => 'Mollusks', 'image_path' => '/assets/silhouettes/snail.png'],
        ['gbif_id' => 167183828, 'rank' => 'CLASS', 'name' => 'Birds', 'image_path' => '/assets/silhouettes/sparrow.png'],
        ['gbif_id' => null, 'rank' => null, 'name' => 'Arachnids', 'image_path' => '/assets/silhouettes/spider.png'],
        ['gbif_id' => null, 'rank' => null, 'name' => 'Echinoderms', 'image_path' => '/assets/silhouettes/starfish.png']
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
                'file_path' => asset('/assets/images/' . $item->gbif_id . '/' . $filename), // TODO check if no image
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
        if (empty($currentData)) {
            abort(404);
        }
        if ($currentData['cover_image_id']) { //check if it is not null
            $coverImageGbifId = DB::table('animals')
                ->where('cover_image_id', $currentData['cover_image_id'])
                ->value('gbif_id');
            if ($coverImageGbifId) {
                $filename = DB::table('animal_pictures')
                    ->where('id', $currentData['cover_image_id'])
                    ->value('filename');
                $currentData['file_path'] = asset('/assets/images/' . $coverImageGbifId . '/' . $filename);
            } else {
                $currentData['file_path'] = null; // meaning data does not exist
            }
        } else {
            // set to null because it's not one of the original array keys
            $currentData['file_path'] = null;
        }
    
        // get silhouette
        if ($currentData['file_path'] === null) {
            $defaultImagePath = $this->fetchDefaultImageForSpecies($gbif_id);
            $currentData['file_path'] = $defaultImagePath !== false ? $defaultImagePath : null;
        }
    
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
    
    protected function fetchDefaultImageForSpecies($gbif_id)
    {
        $response = \Http::get(self::API_URL . $gbif_id . '/parents');
    
        if ($response->successful()) {
            $results = $response->json() ?? [];
            foreach ($results as $result) {
                foreach (self::SILHOUETTE_MAPPING as $mapping) {
                    // Check if 'key' from $result matches 'gbif_id' from $mapping
                    if ($result['key'] == $mapping['gbif_id']) {
                        return $mapping['image_path'];
                    }
                }
            }
        }

        return false;
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
            if ($filename) {
                $speciesData->file_path = asset('/assets/images/' . $gbif_id . '/' . $filename);
            } else {
                // set manually
                $defaultImagePath = $this->fetchDefaultImageForSpecies($gbif_id);
                $speciesData->file_path = $defaultImagePath !== false ? $defaultImagePath : null;
            }
        }

        return $speciesData ? (array) $speciesData : null;
    }
    protected function getAllDescendants($locale, $gbif_id)
    {
        $descendantsData = $this->getDescendantsData($locale, $gbif_id);
        $descendants = [];
        
        // Iterate through descendants data to fetch children
        foreach ($descendantsData as $data) {
            $children = $this->getChildrenData($locale, $data->gbif_id);
            $descendants[] = [
                'closestDescendant' => $data->name,
                'descendants' => $children,
            ];
        }
        
        return $descendants;
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
            $englishData = DB::table('animals')
                ->select('gbif_id', 'name', 'single', 'slug', 'rank', 'cover_image_id', 'appearance', 'food', 'parent_id',
                    DB::raw('(SELECT name FROM animals AS parent WHERE parent.gbif_id = animals.parent_id LIMIT 1) AS parent_name'))
                ->where('parent_id', $gbif_id)
                ->where('language', 'en')
                ->get();
    
            $descendantsData = $englishData;
        } else {
            // Merge data from the specified locale and English if needed
            $englishData = DB::table('animals')
                ->select('gbif_id', 'name', 'single', 'slug', 'rank', 'cover_image_id', 'appearance', 'food', 'parent_id',
                    DB::raw('(SELECT name FROM animals AS parent WHERE parent.gbif_id = animals.parent_id LIMIT 1) AS parent_name'))
                ->where('parent_id', $gbif_id)
                ->where('language', 'en')
                ->get();

            $descendantsData = $descendantsData->merge($englishData)->unique('gbif_id');
        }
    
        // Retrieve filenames of cover images
        foreach ($descendantsData as $key => $data) {
    
            if ($data->cover_image_id) { //check if it is not null
                $coverImageGbifId = DB::table('animals')
                    ->where('cover_image_id', $data->cover_image_id)
                    ->value('gbif_id');
                if ($coverImageGbifId) {
                    $filename = DB::table('animal_pictures')
                        ->where('id', $data->cover_image_id)
                        ->value('filename');
                    $descendantsData[$key]->file_path = asset('/assets/images/' . $coverImageGbifId . '/' . $filename);
                } else {
                    $descendantsData[$key]->file_path = null;
                }
            } else {
                // set to null because it's not one of the original array keys
                $descendantsData[$key]->file_path = null;
            }
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
        $childrenData = DB::table('animals as child')
            ->leftJoin('animals as parent', 'child.parent_id', '=', 'parent.gbif_id')
            ->select(
                'child.id',
                'child.gbif_id',
                'child.name',
                'child.single',
                'child.slug',
                'child.rank',
                'child.cover_image_id',
                'child.appearance',
                'child.food',
                'child.parent_id',
                'parent.name as parent_name'
            )
            ->where('child.parent_id', $parent_gbif_id)
            ->where('child.language', $locale)
            ->get();
    
        // Fallback to English if data not found in the specified locale
        if ($childrenData->isEmpty()) {
            $englishData = DB::table('animals as child')
                ->leftJoin('animals as parent', 'child.parent_id', '=', 'parent.gbif_id')
                ->select(
                    'child.id',
                    'child.gbif_id',
                    'child.name',
                    'child.single',
                    'child.slug',
                    'child.rank',
                    'child.cover_image_id',
                    'child.appearance',
                    'child.food',
                    'child.parent_id',
                    'parent.name as parent_name'
                )
                ->where('child.parent_id', $parent_gbif_id)
                ->where('child.language', 'en')
                ->get();
    
            $childrenData = $englishData;
        } else {
            // Merge data from the specified locale and English if needed
            $englishData = DB::table('animals as child')
                ->leftJoin('animals as parent', 'child.parent_id', '=', 'parent.gbif_id')
                ->select(
                    'child.id',
                    'child.gbif_id',
                    'child.name',
                    'child.single',
                    'child.slug',
                    'child.rank',
                    'child.cover_image_id',
                    'child.appearance',
                    'child.food',
                    'child.parent_id',
                    'parent.name as parent_name'
                )
                ->where('child.parent_id', $parent_gbif_id)
                ->where('child.language', 'en')
                ->get();
    
            $childrenData = $childrenData->merge($englishData)->unique('gbif_id');
        }
    
        // Retrieve filenames of cover images
        foreach ($childrenData as $key => $data) {
            if ($data->cover_image_id) { //check if it is not null
                $coverImageGbifId = DB::table('animals')
                    ->where('cover_image_id', $data->cover_image_id)
                    ->value('gbif_id');
                if ($coverImageGbifId) {
                    $filename = DB::table('animal_pictures')
                        ->where('id', $data->cover_image_id)
                        ->value('filename');
                    $childrenData[$key]->file_path = asset('/assets/images/' . $coverImageGbifId . '/' . $filename);
                } else {
                    $childrenData[$key]->file_path = null;
                }
            } else {
                // set to null because it's not one of the original array keys
                $childrenData[$key]->file_path = null;
            }
        }
    
        // Modify name and single fields to cut off the first part
        foreach ($childrenData as $key => $data) {
            $childrenData[$key]->name = explode('|', $data->name)[0];
            $childrenData[$key]->single = explode('|', $data->single)[0];
        }
    
        return $childrenData->toArray();
    }
    public static function getEnumValues($columnName)
    {
        $tableName = (new static())->getTable();
    
        // Get enum column values
        $columns = \Schema::getColumnListing($tableName);
    
        // Check if the specified column is an enum type
        if (!in_array($columnName, $columns)) {
            return [];
        }
    
        // Fetch enum values using raw SQL query
        $query = "SHOW COLUMNS FROM $tableName WHERE Field = '$columnName'";
        $result = DB::select($query);
    
        // Extract enum values from the result
        $enumStr = $result[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $enumStr, $matches);
        $enumValues = explode("','", $matches[1]);
    
        return $enumValues;
    }
}
