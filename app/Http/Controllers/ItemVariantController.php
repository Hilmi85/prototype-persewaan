<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVariant;
use Illuminate\Http\Request;

class ItemVariantController extends Controller
{
    public function index()
    {
        $variants = ItemVariant::with('item')->latest()->get();
        return view('admin.item-variant.index', compact('variants'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();
        return view('admin.item-variant.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'sku_code' => 'required|string|max:255|unique:item_variants,sku_code',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'stock' => 'required|integer|min:0',
            'available_stock' => 'required|integer|min:0',
            'daily_price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        ItemVariant::create($validated);

        return redirect()->route('item-variants.index')->with('success', 'Varian item berhasil ditambahkan.');
    }

    public function edit(ItemVariant $itemVariant)
    {
        $items = Item::orderBy('name')->get();
        return view('admin.item-variant.edit', compact('itemVariant', 'items'));
    }

    public function update(Request $request, ItemVariant $itemVariant)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'sku_code' => 'required|string|max:255|unique:item_variants,sku_code,' . $itemVariant->id,
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'stock' => 'required|integer|min:0',
            'available_stock' => 'required|integer|min:0',
            'daily_price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $itemVariant->update($validated);

        return redirect()->route('item-variants.index')->with('success', 'Varian item berhasil diperbarui.');
    }

    public function destroy(ItemVariant $itemVariant)
    {
        $itemVariant->delete();

        return redirect()->route('item-variants.index')->with('success', 'Varian item berhasil dihapus.');
    }
}
