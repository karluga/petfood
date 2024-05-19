<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Animals extends Model
{
    use HasFactory;
    private static $LIVESTOCK_IDS = [
        /* your livestock IDs here */
    
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
            ->select('name', 'slug', 'tier', 'appearance', 'food')
            ->where('slug', $type)
            ->where('language', $locale)
            ->first();
    
        // fallback
        if (!$typeInfo) {
            $typeInfo = DB::table('animals')
                ->select('name', 'slug', 'tier', 'appearance', 'food')
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
        // Fetch animals where gbif_id is in LIVESTOCK_IDS
        $livestockItems = DB::table('animals')
            ->whereIn('gbif_id', self::$LIVESTOCK_IDS)
            ->where('language', $locale)
            ->get();

        // Fallback to English if no animals found in the specified locale
        if ($livestockItems->isEmpty()) {
            $livestockItems = DB::table('animals')
                ->whereIn('gbif_id', self::$LIVESTOCK_IDS)
                ->where('language', 'en')
                ->get();
        }

        $livestock = [];

        foreach ($livestockItems as $item) {
            $livestock[] = [
                'name' => $item->name,
                'slug' => $item->slug,
            ];
        }

        return $livestock;
    }

}
