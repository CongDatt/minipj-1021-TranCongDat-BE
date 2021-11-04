<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['file_name','file_path','disk','file_size','fileable_type','fileable_id'];

    public function fileable()
    {
        return $this->morphTo();
    }

}
