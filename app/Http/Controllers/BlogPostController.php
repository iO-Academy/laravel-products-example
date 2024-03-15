<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogPostController extends Controller
{
    public function getAll()
    {
        $posts = BlogPost::all();

        return $posts;
    }
}
