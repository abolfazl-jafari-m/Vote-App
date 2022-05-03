<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Sluggable;

    public function idea()
    {
        return $this->hasMany(idea::class);
    }

    public function sluggable(): array
    {
        return ["slug" => [
            "source" => "name"
             ]
        ];
    }


}
