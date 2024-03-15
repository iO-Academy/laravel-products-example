<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // The fields listed in the hidden property on the model are automatically hidden from all query results
    // If you have an edge case where you do want to display one of these fields you can call the makeVisible()
    // to bring the back
    public $hidden = ['created_at', 'updated_at'];
}
