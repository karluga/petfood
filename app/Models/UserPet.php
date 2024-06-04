<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gbif_id',
        'nickname',
        'filename',
    ];
    public $timestamps = false;

}
