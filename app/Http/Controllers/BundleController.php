<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::with([
            'bundleItems.item.category',
            'recommendationRules',
        ])
            ->latest()
            ->get();

        return view('admin.bundle.index', compact('bundles'));
    }

    public function create()
    {
        $items = $this->getSelectableItems();

        return view('admin.bundle.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateBundle($request);

        DB::transaction(function () use ($validated) {
            $bundle = Bundle::create($this->bundlePayload($validated));

            $this->syncBundleItems(
                $bundle,
                $validated['bundle_items'],
                $validated['item_quantities'] ?? [],
                $validated['item_required'] ?? []
            );
        });

        return redirect()
            ->route('bundles.index')
            ->with('success', 'Bundle berhasil ditambahkan.');
    }

    public function edit(Bundle $bundle)
    {
        $bundle->load('bundleItems.item.category');

        $items = $this->getSelectableItems();

        return view('admin.bundle.edit', compact('bundle', 'items'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $validated = $this->validateBundle($request, $bundle);

        DB::transaction(function () use ($bundle, $validated) {
            $bundle->update($this->bundlePayload($validated));

            $this->syncBundleItems(
                $bundle,
                $validated['bundle_items'],
                $validated['item_quantities'] ?? [],
                $validated['item_required'] ?? []
            );
        });

        return redirect()
            ->route('bundles.index')
            ->with('success', 'Bundle berhasil diperbarui.');
    }

    public function destroy(Bundle $bundle)
    {
        $bundle->delete();

        return redirect()
            ->route('bundles.index')
            ->with('success', 'Bundle berhasil dihapus.');
    }

    private function validateBundle(Request $request, ?Bundle $bundle = null): array
    {
        return $request->validate([
            'bundle_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bundles', 'bundle_code')->ignore($bundle?->id),
            ],
            'bundle_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Unisex',
            'butuh_rias' => 'required|boolean',
            'budget_category' => 'nullable|in:Rendah,Sedang,Tinggi',
            'price' => 'required|numeric|min:0',
            'is_custom' => 'required|boolean',
            'is_active' => 'required|boolean',

            'bundle_items' => 'required|array|min:1',
            'bundle_items.*' => 'integer|exists:items,id',

            'item_quantities' => 'nullable|array',
            'item_quantities.*' => 'nullable|integer|min:1',

            'item_required' => 'nullable|array',
            'item_required.*' => 'nullable|boolean',
        ], [
            'bundle_items.required' => 'Pilih minimal satu item untuk isi bundle.',
            'bundle_items.min' => 'Pilih minimal satu item untuk isi bundle.',
        ]);
    }

    private function bundlePayload(array $validated): array
    {
        return [
            'bundle_code' => $validated['bundle_code'],
            'bundle_name' => $validated['bundle_name'],
            'description' => $validated['description'] ?? null,
            'jenis_acara' => $validated['jenis_acara'] ?? null,
            'kategori_adat' => $validated['kategori_adat'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'butuh_rias' => (bool) $validated['butuh_rias'],
            'budget_category' => $validated['budget_category'] ?? null,
            'price' => $validated['price'],
            'is_custom' => (bool) $validated['is_custom'],
            'is_active' => (bool) $validated['is_active'],
        ];
    }

    private function syncBundleItems(Bundle $bundle, array $selectedItemIds, array $quantities = [], array $requiredStatuses = []): void
    {
        $selectedItemIds = collect($selectedItemIds)
            ->filter()
            ->map(fn ($itemId) => (int) $itemId)
            ->unique()
            ->values();

        $bundle->bundleItems()->delete();

        foreach ($selectedItemIds as $itemId) {
            $quantity = max(1, (int) ($quantities[$itemId] ?? 1));
            $isRequired = (bool) ($requiredStatuses[$itemId] ?? false);

            $bundle->bundleItems()->create([
                'item_id' => $itemId,
                'quantity' => $quantity,
                'is_required' => $isRequired,
            ]);
        }
    }

    private function getSelectableItems()
    {
        return Item::with(['category', 'itemVariants'])
            ->where('is_active', true)
            ->orderBy('item_type')
            ->orderBy('name')
            ->get();
    }
}
