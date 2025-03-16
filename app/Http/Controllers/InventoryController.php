<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all categories for the dropdown
        $categories = Category::all();

        // Get the selected category ID from the request (if any)
        $selectedCategoryId = $request->input('category');

        // Fetch items for the selected category (or empty if no category is selected)
        $items = $selectedCategoryId
            ? Item::where('category_id', $selectedCategoryId)
                ->select('id', 'name', 'count as quantity', 'image')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'quantity' => $item->quantity,
                        'image_url' => $item->image ? asset('storage/' . $item->image) : asset('images/default-image.jpg'),
                    ];
                })
            : collect([]); // Empty collection if no category is selected

        return view('inventory', compact('categories', 'items', 'selectedCategoryId'));
    }

    public function updateInventory(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        // Update the items' quantities
        foreach ($request->items as $itemData) {
            $item = Item::find($itemData['id']);
            $item->count = $itemData['quantity'];
            $item->save();
        }

        return redirect()->back()->with('success', 'Inventory updated successfully!');
    }
}
