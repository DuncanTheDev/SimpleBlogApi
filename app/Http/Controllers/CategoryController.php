<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categoies,name',
        ]);

        $slug = Str::slug($request->name);

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function getCategory()
    {
        $category = Category::all();

        return response()->json([
            'message' => 'Categories retrieved successfully',
            'data' => $category
        ], 200);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:categoies,name',
        ]);

        $category = Category::update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete;

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
