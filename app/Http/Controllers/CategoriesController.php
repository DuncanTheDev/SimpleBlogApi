<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categoies,name',
        ]);

        $slug = Str::slug($request->name);

        $category = Categories::create([
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
        $category = Categories::all();

        return response()->json([
            'message' => 'Categories retrieved successfully',
            'data' => $category
        ], 200);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Categories::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:categoies,name',
        ]);

        $category = Categories::update([
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
        $category = Categories::findOrFail($id);
        $category->delete;

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
