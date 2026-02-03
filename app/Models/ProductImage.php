<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'producto_id',
        'path',
        'original_name',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    /**
     * Get the product that owns this image.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
