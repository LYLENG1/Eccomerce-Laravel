<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:available,not-available'
        ]);

        $category = Category::create($request->all());
        return response()->json([
            'message' => 'Category created successfully!',
            'category' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category, 200);
    }

    public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'status' => 'sometimes|required|in:available,not-available'
    ]);

    $category->update($request->only(['name', 'status']));

    return response()->json([
        'message' => 'Category updated successfully!',
        'category' => $category
    ], 200);
}


    public function destroy($id)
    {
        Category::destroy($id);
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}

