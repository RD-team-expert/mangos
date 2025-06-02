<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Update the items' quantities and log to inventory history
        foreach ($request->items as $itemData) {
            $item = Item::find($itemData['id']);
            $item->count = $itemData['quantity'];
            $item->last_count_date = now();
            $item->save();

            // Log the inventory update
            InventoryHistory::create([
                'item_id' => $item->id,
                'user_id' => Auth::id(),
                'count' => $itemData['quantity'],
                'count_date' => now(),
                'note' => null, // Optional: Add a form field for notes if needed
            ]);
        }

        return redirect()->back()->with('success', 'Inventory updated successfully!');
    }

    public function history(Request $request)
    {
        // Restrict access to managers
        if (Auth::user()->role !== 'manager') {
            abort(403, 'Unauthorized action.');
        }

        // Fetch the last 10 inventory history records
        $histories = InventoryHistory::with(['item', 'user'])
            ->orderBy('count_date', 'desc')
            ->take(10)
            ->get()
            ->map(function ($history) {
                return [
                    'item_name' => $history->item->name,
                    'count' => $history->count,
                    'count_date' => $history->count_date->format('Y-m-d H:i:s'),
                    'user_name' => $history->user->name,
                    'note' => $history->note,
                ];
            });

        return view('inventory-history', compact('histories'));
    }
}