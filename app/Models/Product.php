<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'description',
        'category_id',
        'price',
        'stock',
        'image',
    ];

    // Accessor for image URL
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('storage/products/' . $image), // Corrected URL
        );
    }

    // Define the relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)->withPivot('quantity', 'price');
    }
}

