<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:suppliers',
            'password' => 'required|string|min:6',
            'note' => 'nullable|string',
        ]);

        Supplier::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password), // Hash the password
            'note' => $request->note,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:suppliers,username,' . $supplier->id,
            'password' => 'nullable|string|min:6',
            'note' => 'nullable|string',
        ]);

        $supplier->update([
            'name' => $request->name,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $supplier->password,
            'note' => $request->note,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
