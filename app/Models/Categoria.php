<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'slug'
    ];

    /**
     * Get the productos that belong to this category.
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
