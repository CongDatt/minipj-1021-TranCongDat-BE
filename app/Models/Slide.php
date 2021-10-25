<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class Slide extends Model
{
    use HasFactory;

    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
