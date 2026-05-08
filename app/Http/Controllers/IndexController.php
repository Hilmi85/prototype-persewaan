<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\ContactSetting;
use App\Models\Item;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $featuredItems = Item::with(['category', 'itemVariants'])
            ->where('is_active', true)
            ->where('item_type', 'baju_adat')
            ->latest()
            ->take(8)
            ->get();

        $featuredBundles = Bundle::with(['bundleItems.item.category', 'bundleItems.item.itemVariants'])
            ->where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        $contact = ContactSetting::where('is_active', true)->first();

        return view('customer.index', compact('featuredItems', 'featuredBundles', 'contact'));
    }

    public function catalog(Request $request)
    {
        $categories = Category::whereHas('items', function ($query) {
                $query->where('is_active', true)
                    ->where('item_type', 'baju_adat');
            })
            ->orderBy('cat_name')
            ->get();

        $items = Item::with(['category', 'itemVariants'])
            ->where('is_active', true)
            ->where('item_type', 'baju_adat')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('adat_category', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->filled('gender'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('gender', $request->gender)
                        ->orWhere('gender', 'Unisex')
                        ->orWhereNull('gender');
                });
            })
            ->latest()
            ->get();

        $catalogGroups = $items
            ->groupBy(function ($item) {
                return $item->category->cat_name
                    ?? ($item->adat_category ? 'Baju Adat ' . $item->adat_category : 'Baju Adat Lainnya');
            })
            ->map(function ($groupItems, $groupName) {
                return [
                    'label' => $groupName,
                    'description' => 'Pilihan koleksi ' . strtolower($groupName) . ' yang tersedia di Quin Salon.',
                    'items' => $groupItems->values(),
                ];
            })
            ->values();

        $totalItems = $items->count();

        return view('customer.catalog', compact('categories', 'catalogGroups', 'totalItems'));
    }

    public function accessories(Request $request)
    {
        $categories = Category::whereHas('items', function ($query) {
                $query->where('is_active', true)
                    ->where('item_type', 'aksesoris');
            })
            ->orderBy('cat_name')
            ->get();

        $items = Item::with(['category', 'itemVariants'])
            ->where('is_active', true)
            ->where('item_type', 'aksesoris')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('adat_category', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->latest()
            ->get();

        return view('customer.aksesoris', compact('items', 'categories'));
    }

    public function showItem(Item $item)
    {
        abort_if(!$item->is_active, 404);

        $item->load([
            'category',
            'itemVariants' => fn ($query) => $query->orderBy('size')->orderBy('color'),
        ]);

        return view('customer.item-detail', compact('item'));
    }

    public function showBundle(Bundle $bundle)
    {
        abort_if(!$bundle->is_active, 404);

        $bundle->load([
            'bundleItems.item.category',
            'bundleItems.item.itemVariants',
            'recommendationRules',
        ]);

        return view('customer.bundle-detail', compact('bundle'));
    }
}
