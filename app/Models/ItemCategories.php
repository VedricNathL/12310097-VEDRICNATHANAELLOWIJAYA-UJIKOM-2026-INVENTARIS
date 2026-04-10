<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ItemStock;

class ItemCategories extends Model
{
    protected $table = 'item_categories';

    protected $fillable = [
        'name',
        'division'
    ];

    public function stocks()
    {
        return $this->hasMany(ItemStock::class, 'category_id');
    }
}