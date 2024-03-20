<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\JsonResponseService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Here we are passing our service from the outside
    // We call this dependency injection
    // When a class depends on another class in order to do it's job, we pass the dependency in from outside using
    // a constructor
    private JsonResponseService $responseService;

    public function __construct(JsonResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function all(Request $request)
    {
        $search = $request->search;

        $hidden = ['description', 'qty', 'free_shipping'];

        if ($search) {
            return response()->json($this->responseService->getFormat(
                'Products returned',
                Product::where('title', 'LIKE', "%$search%")->get()->makeHidden($hidden)
            ));
        }

        return response()->json($this->responseService->getFormat(
            'Products returned',
            Product::all()->makeHidden($hidden)
        ));
    }

    public function find(int $id)
    {
        return response()->json($this->responseService->getFormat(
            'Product returned',
            // We can add the with method onto any model that has relationships to JOIN the relating table into our results
            // If you want to, you can comma sep fields from the relation that you want to retrieve
            // With one to many relationships (reviews), make sure you always select the foreign_id
                // Otherwise laravel will not be able to join the tables
            // You can then hide the product_id after the fact by adding the hidden property to the Review model
            // You can still use makeHidden() but only to hide fields on the main table, not the relationships
            Product::with(['reviews:id,name,review,product_id', 'categories:id,name'])->find($id)
        ));
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100|string',
            'description' => 'max:500|string',
            'price' => 'required|min:0|decimal:2',
            'qty' => 'required|min:0|integer',
            'free_shipping' => 'required|boolean',
            // Because products can have multiple categories, we make sure category_ids is an array
            'category_ids' => 'required|array',
            // We make sure that category_ids contains only valid category ids
            'category_ids.*' => 'integer|exists:categories,id'
        ]);

        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->qty = $request->qty;
        $product->free_shipping = $request->free_shipping;

        // We must save the product before we can attach relationships
        $saved = $product->save();

        // We call the relationship method on the model and then attach an array of ids
        // This automatically manages the link table for you, adding in rows to represent the relations
        $product->categories()->attach($request->category_ids);

        if (! $saved) {
            return response()->json($this->responseService->getFormat('Could not create product'), 500);
        }

        return response()->json($this->responseService->getFormat('Product created'), 418);
    }

    public function update(int $id, Request $request)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Error invalid product id',
            ], 400);
        }

        // Update the products fields based on the request data
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->qty = $request->qty;
        $product->free_shipping = $request->free_shipping;

        // Save the changes to the DB
        if (! $product->save()) {
            return response()->json([
                'message' => 'Error, product did not save',
            ], 500);
        }

        return response()->json([
            'message' => 'Product updated',
        ]);
    }

    public function delete(int $id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Error invalid product ID',
            ]);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted',
        ]);
    }

}
