<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;
use App\Models\File;
use App\Models\Order;

/**
 * @method static where(string $string, int $int)
 */
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description','is_free_shipping','quantity','category_id','original_price',
        'is_gift','is_hot','discount','order_id'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function file(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
