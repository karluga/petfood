<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Animals extends Model
{
    use HasFactory;

    public static function getPopularPets($locale)
    {
        // Reset popular items each day
        return Cache::remember('popular_pets_' . $locale, 86400, function () use ($locale) {
            $commonItems = DB::table('common')->select('gbif_id', 'emoji', 'hex_color')->where('on_display', 1)->get();
            $animals = [];

            foreach ($commonItems as $item) {
                $animal = DB::table('gbif_classification')
                    ->select('name', 'slug')
                    ->where('gbif_id', $item->gbif_id)
                    ->where('language', $locale)
                    ->first();

                // fallback
                if (!$animal) {
                    $animal = DB::table('gbif_classification')
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
        $typeInfo = DB::table('gbif_classification')
            ->select('name', 'slug', 'tier', 'appearance', 'food')
            ->where('slug', $type)
            ->where('language', $locale)
            ->first();
    
        // fallback
        if (!$typeInfo) {
            $typeInfo = DB::table('gbif_classification')
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
}
