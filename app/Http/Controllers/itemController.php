<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->orderBy('name')->get();
        return view('admin.item.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::orderBy('cat_name')->get();
        return view('admin.item.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'item_type' => 'required|in:baju_adat,aksesoris,jasa_rias',
            'adat_category' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Unisex',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);
            $validated['img'] = $imageName;
        }

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('cat_name')->get();
        return view('admin.item.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'item_type' => 'required|in:baju_adat,aksesoris,jasa_rias',
            'adat_category' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Unisex',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);
            $validated['img'] = $imageName;
        }

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }

    public function updateStatus(Item $item)
    {
        $item->update([
            'is_active' => !$item->is_active,
        ]);

        return redirect()->route('items.index')->with('success', 'Status item berhasil diperbarui.');
    }
}
