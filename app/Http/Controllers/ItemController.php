<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'supplier'])->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::all();
        return view('items.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'must_have' => 'boolean',
            'unit' => 'required|string|max:50',
            'note' => 'nullable|string',
            'count' => 'required|integer',
            'last_count_date' => 'nullable|date',
        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::all();
        return view('items.edit', compact('item', 'categories', 'suppliers'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'must_have' => 'boolean',
            'unit' => 'required|string|max:50',
            'note' => 'nullable|string',
            'count' => 'required|integer',
            'last_count_date' => 'nullable|date',
        ]);

        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
