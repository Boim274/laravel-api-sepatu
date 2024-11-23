<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return new CategoryResource(
            true, // Status
            'Categories retrieved successfully', // Message
            $categories // Resource (data)
        );
    }
    
    public function store(Request $request)
    {
        // Validate that the name is required and is a string
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if the category name already exists
        $existingCategory = Category::where('name', $request->name)->first();

        if ($existingCategory) {
            return response()->json([
                'status' => false,
                'message' => 'Category with this name already exists.'
            ], 400); // 400 Bad Request
        }

        // If not existing, create the category
        $category = Category::create([
            'name' => $request->name,
        ]);

        return new CategoryResource(
            true, // Status
            'Category created successfully', // Message
            $category // Resource (data)
        );
    }
    
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return new CategoryResource(
            true, // Status
            'Category retrieved successfully', // Message
            $category // Resource (data)
        );
    }
    
    public function update(Request $request, $id)
    {
        // Validate that the name is required and is a string
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if the category name already exists (except for the current category)
        $existingCategory = Category::where('name', $request->name)
                                     ->where('id', '!=', $id)
                                     ->first();

        if ($existingCategory) {
            return response()->json([
                'status' => false,
                'message' => 'Category with this name already exists.'
            ], 400); // 400 Bad Request
        }

        // Find the category and update it
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return new CategoryResource(
            true, // Status
            'Category updated successfully', // Message
            $category // Resource (data)
        );
    }
    
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return new CategoryResource(
            true, // Status
            'Category deleted successfully', // Message
            null // Resource (data)
        );
    }
}
