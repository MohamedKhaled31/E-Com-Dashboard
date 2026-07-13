<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Models\Category;
use App\Models\Product;
use Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('user_id', auth()->id())->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProductRequest $request)
    {
        $validated = $request->validated();
        $path = $request->file('image')->store('images', 'public');
        Product::create([
            'name' => $validated['name'],
            'image' => $path,
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            return redirect()->route('products.index')->with('error', 'You are not authorized to edit this product');
        }
        $categories = Category::where('user_id', auth()->id())->get();
        return view('products.edit', compact('categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditProductRequest $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            return redirect()->route('products.index')->with('error', 'You are not authorized to edit this product');
        }
        $validated = $request->validated();
        $path = $product->image;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            Storage::disk('public')->delete($product->image);
        }
        $product->update(
            [
                'name' => $validated['name'],
                'image' => $path,
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'user_id' => auth()->id(),
            ]
        );
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            return redirect()->route('products.index')->with('error', 'You are not authorized to edit this product');
        }
        Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
