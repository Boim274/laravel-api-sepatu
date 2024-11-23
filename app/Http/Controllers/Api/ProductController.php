<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // Paginate the products with the category relationship
        $products = Product::with('category')->latest()->paginate(10);
        
        // Instead of using $products->items(), pass the entire $products paginator instance
        return new ProductResource('success', 'Products fetched successfully', $products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validation for product input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'brand' => 'required|string|max:100',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check if a product with the same name already exists
        $existingProduct = Product::where('name', $request->name)->first();
        if ($existingProduct) {
            return response()->json([
                'status' => 'error',
                'message' => 'A product with this name already exists.',
            ], 400); // 400 Bad Request
        }

        // Handle the image upload if present
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('public/products', $imageName);
        }

        // Create the new product record
        $product = Product::create(array_merge($validator->validated(), [
            'image' => $imageName,
        ]));

        // Return the ProductResource with status and message
        return new ProductResource('success', 'Product created successfully', $product);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        // Find product with related category
        $product = Product::with(['category'])->findOrFail($id);

        // Return the ProductResource with status and message
        return new ProductResource('success', 'Product fetched successfully', $product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation for product input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'brand' => 'required|string|max:100',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the product
        $product = Product::findOrFail($id);

        // Handle image upload and deletion of old image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('public/products', $imageName);

            // Delete old image if exists
            if ($product->image) {
                Storage::delete('public/products/' . $product->image);
            }

            // Update the product with new image
            $product->update(array_merge($validator->validated(), [
                'image' => $imageName,
            ]));
        } else {
            // Update product without changing the image
            $product->update($validator->validated());
        }

        // Return the updated product with status and message
        return new ProductResource('success', 'Product updated successfully', $product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        // Find product
        $product = Product::findOrFail($id);

        // Delete the image if it exists
        if ($product->image) {
            Storage::delete('public/products/' . $product->image);
        }

        // Delete the product
        $product->delete();

        // Return success message
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.',
        ], 200);
    }
}
