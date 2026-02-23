<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($book) {
            if (empty($book->slug)) {
                $book->slug = \Illuminate\Support\Str::slug($book->title);
            }
        });
    }

    protected $fillable = [
        'product_type',
        'stock_quantity',
        'weight',
        'sku',
        'shipping_charge',
        'format_price_pdf',
        'format_price_hardcopy',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'cover_image',
        'pdf_file',
        'is_active',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
    ];

    public function hasStock($quantity = 1)
    {
        if ($this->product_type === 'digital')
            return true;
        if ($this->product_type === 'both' && request()->selected_format === 'pdf')
            return true;

        return $this->stock_quantity >= $quantity;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
