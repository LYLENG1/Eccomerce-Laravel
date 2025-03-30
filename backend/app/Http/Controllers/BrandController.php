<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return response()->json(Brand::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'status' => 'required|in:available,not-available'
        ]);

        $logoPath = $request->file('logo') ? $request->file('logo')->store('upload', 'public') : null;

        $brand = Brand::create([
            'name' => $request->name,
            'logo' => $logoPath,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Brand created successfully!',
            'brand' => $brand
        ], 201);
    }


    public function show(Brand $brand)
    {
        return response()->json($brand, 200);
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|string',
            'status' => 'sometimes|required|in:available,not-available'
        ]);

        $brand->update($request->all());
        return response()->json($brand, 200);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(null, 204);
    }
}
