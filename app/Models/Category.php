<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    // Add this line to any models that have a many-to-many to hide
    // the useless pivot data from your JSON responses
    public $hidden = ['pivot'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
