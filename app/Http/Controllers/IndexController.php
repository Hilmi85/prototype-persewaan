<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\ContactSetting;
use App\Models\Item;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $featuredItems = Item::where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        $featuredBundles = Bundle::where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        $contact = ContactSetting::where('is_active', true)->first();

        return view('customer.index', compact('featuredItems', 'featuredBundles', 'contact'));
    }

    public function catalog(Request $request)
    {
        $query = Item::with('category')
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('cat_name', $request->category);
            });
        }

        if ($request->filled('item_type')) {
            $query->where('item_type', $request->item_type);
        }

        if ($request->filled('gender')) {
            $query->where(function ($q) use ($request) {
                $q->where('gender', $request->gender)
                  ->orWhere('gender', 'Unisex')
                  ->orWhereNull('gender');
            });
        }

        $items = $query->latest()->paginate(12);

        return view('customer.catalog', compact('items'));
    }

    public function showItem(Item $item)
    {
        $item->load('category', 'itemVariants');

        return view('customer.item-detail', compact('item'));
    }

    public function showBundle(Bundle $bundle)
    {
        $bundle->load('bundleItems.item');

        return view('customer.bundle-detail', compact('bundle'));
    }
}
