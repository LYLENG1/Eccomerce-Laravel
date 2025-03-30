<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with(['category', 'brand'])->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = $request->file('image') ? $request->file('image')->store('upload', 'public') : null;

        $product = Product::create([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        $request->validate([
            'category_id' => 'exists:categories,id',
            'brand_id' => 'exists:brands,id',
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'quantity' => 'integer|min:0', // Changed min to 0 to allow stock depletion
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        $data = $request->only([
            'category_id', 
            'brand_id', 
            'name', 
            'description', 
            'price', 
            'quantity'
        ]);
    
        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::delete($product->image);
            }
            
            $path = $request->file('image')->store('upload', 'public');
            $data['image'] = $path;
        }
    
        $product->update($data);
    
        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product->fresh(['category', 'brand'])
        ], 200);
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
