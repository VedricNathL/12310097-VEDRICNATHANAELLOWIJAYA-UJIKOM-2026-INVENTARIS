<?php

namespace App\Models;
use App\Models\ItemCategories;
use Illuminate\Database\Eloquent\Model;

class ItemStock extends Model
{
    protected $table = 'item_stocks';

    protected $fillable = [
        'category_id',
        'item_name',
        'total_stock',
        'total_repaired',
        'total_borrowed'
    ];


public function category()
{
    return $this->belongsTo(ItemCategories::class, 'category_id');
}
}