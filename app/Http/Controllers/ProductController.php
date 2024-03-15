<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $search = $request->search;

        $hidden = ['description', 'qty', 'free_shipping'];

        if ($search) {
            // WHERE `title` LIKE '%shed%'
            // The % in SQL is a wildcard - it can represent any or no characters
            return response()->json([
                'message' => 'Products returned',
                'data' => Product::where('title', 'LIKE', "%$search%")->get()->makeHidden($hidden)
            ]);
        }

        // We can hide fields from the result by chaining makeHidden() onto the end of an eloquent query
        // The first method after your model name has ::, all methods after that use ->
        return response()->json([
            'message' => 'Products returned',
            'data' => Product::all()->makeHidden($hidden)
        ]);
    }

    public function find(int $id)
    {
        return response()->json([
            'message' => 'Product returned',
            'data' => Product::find($id)
        ]);
    }

    // If you have a route that needs to access data from the request (POST data, or GET data)
    // We need to add the Request object as a param of our action
    public function create(Request $request)
    {
        // We do need to validate the data, but we'll do that later
        $request->validate([
            'title' => 'required|max:100|string',
            'description' => 'max:500|string',
            'price' => 'required|min:0|decimal:2',
            'qty' => 'required|min:0|integer',
            'free_shipping' => 'required|boolean',
        ]);

        // Create a new product and add the POST data
        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->qty = $request->qty;
        $product->free_shipping = $request->free_shipping;

        if (! $product->save()) {
            return response()->json([
                'message' => 'Could not create product'
            ], 500);
        }

        return response()->json([
            'message' => 'Product created'
        ], 418);
    }

    // When an action needs both a dynamic URL placeholder and the request object
    // Put the dynamic param first, followed by the request object
    public function update(int $id, Request $request)
    {
        // Find the product being updated
        $product = Product::find($id);

        // Returning an error response if someone tries to update a product that does not exist
        if (! $product) {
            return response()->json([
                'message' => 'Error invalid product id'
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
                'message' => 'Error, product did not save'
            ], 500);
        }
        // Send a response to the user

        return response()->json([
            'message' => 'Product updated'
        ]);
    }

    public function delete(int $id)
    {
        // Find the product being deleted
        $product = Product::find($id);

        // Returning an error response if someone tries to update a product that does not exist
        if (! $product) {
            return response()->json([
                'message' => 'Error invalid product ID'
            ]);
        }

        // delete it
        $product->delete();

        // send a response
        return response()->json([
            'message' => 'Product deleted'
        ]);
    }
}
