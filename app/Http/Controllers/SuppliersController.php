<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSupplierRequest;
use App\Http\Requests\EditSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::Where('user_id', auth()->id())->get();
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddSupplierRequest $request)
    {
        $validated = $request->validated();
        Supplier::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
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
    public function edit(Supplier $supplier)
    {
        if ($supplier->user_id !== auth()->id()) {
            return redirect()->route('suppliers.index')->with('error', 'You are not authorized to edit this supplier');
        }
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditSupplierRequest $request, Supplier $supplier)
    {
        if ($supplier->user_id !==auth()->id()){
            return redirect()->route('suppliers.index')->with('error', 'You are not authorized to update this supplier');
        }
        $validated = $request->validated();
        $supplier->update([
            'name' => $validated['name'],
        ]);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->user_id !== auth()->id()) {
            return redirect()->route('suppliers.index')->with('error', 'You are not authorized to delete this supplier');
        }
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
