<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ItemVariantController extends Controller
{
    public function index()
    {
        $variants = ItemVariant::with('item.category')
            ->latest()
            ->get();

        return view('admin.item-variant.index', compact('variants'));
    }

    public function create()
    {
        $items = $this->getSelectableItems();

        return view('admin.item-variant.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateVariant($request);

        if ($this->variantExists(
            (int) $validated['item_id'],
            $validated['size'],
            $validated['color'] ?? null
        )) {
            throw ValidationException::withMessages([
                'size' => 'Varian dengan item, ukuran, dan warna tersebut sudah tersedia.',
            ]);
        }

        $validated['sku_code'] = $this->generateSkuCode(
            (int) $validated['item_id'],
            $validated['size'],
            $validated['color'] ?? null
        );

        $validated['available_stock'] = $validated['stock'];

        ItemVariant::create($validated);

        return redirect()
            ->route('item-variants.index')
            ->with('success', 'Varian item berhasil ditambahkan.');
    }

    public function edit(ItemVariant $itemVariant)
    {
        $items = $this->getSelectableItems($itemVariant->item_id);

        return view('admin.item-variant.edit', compact('itemVariant', 'items'));
    }

    public function update(Request $request, ItemVariant $itemVariant)
    {
        $validated = $this->validateVariant($request);

        if ($this->variantExists(
            (int) $validated['item_id'],
            $validated['size'],
            $validated['color'] ?? null,
            $itemVariant->id
        )) {
            throw ValidationException::withMessages([
                'size' => 'Varian dengan item, ukuran, dan warna tersebut sudah tersedia.',
            ]);
        }

        $newStock = (int) $validated['stock'];
        $oldStock = (int) $itemVariant->stock;
        $oldAvailableStock = (int) $itemVariant->available_stock;
        $stockDifference = $newStock - $oldStock;

        $validated['available_stock'] = max(
            0,
            min($newStock, $oldAvailableStock + $stockDifference)
        );

        $validated['sku_code'] = $this->generateSkuCode(
            (int) $validated['item_id'],
            $validated['size'],
            $validated['color'] ?? null,
            $itemVariant->id
        );

        $itemVariant->update($validated);

        return redirect()
            ->route('item-variants.index')
            ->with('success', 'Varian item berhasil diperbarui.');
    }

    public function destroy(ItemVariant $itemVariant)
    {
        if ($itemVariant->orderItemVariants()->exists()) {
            $itemVariant->update(['is_active' => false]);

            return redirect()
                ->route('item-variants.index')
                ->with('success', 'Varian sudah pernah dipakai pada transaksi, sehingga tidak dihapus dan hanya dinonaktifkan.');
        }

        $itemVariant->delete();

        return redirect()
            ->route('item-variants.index')
            ->with('success', 'Varian item berhasil dihapus.');
    }

    private function validateVariant(Request $request): array
    {
        return $request->validate([
            'item_id' => 'required|exists:items,id',
            'size' => 'required|string|max:50',
            'color' => 'nullable|string|max:50',
            'stock' => 'required|integer|min:0',
            'daily_price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);
    }

    private function getSelectableItems(?int $currentItemId = null)
    {
        return Item::with('category')
            ->where(function ($query) use ($currentItemId) {
                $query->where('is_active', true);

                if ($currentItemId) {
                    $query->orWhere('id', $currentItemId);
                }
            })
            ->orderBy('name')
            ->get();
    }

    private function variantExists(int $itemId, string $size, ?string $color = null, ?int $ignoreId = null): bool
    {
        return ItemVariant::query()
            ->where('item_id', $itemId)
            ->whereRaw('LOWER(size) = ?', [Str::lower(trim($size))])
            ->whereRaw('LOWER(COALESCE(color, "")) = ?', [Str::lower(trim((string) $color))])
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function generateSkuCode(int $itemId, string $size, ?string $color = null, ?int $ignoreId = null): string
    {
        $item = Item::find($itemId);

        $base = collect([
            'VAR',
            $item?->name,
            $size,
            $color,
        ])
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => Str::upper(Str::slug((string) $value, '-')))
            ->implode('-');

        $base = Str::limit($base ?: 'VARIANT', 70, '');
        $sku = $base;
        $counter = 1;

        while (
            ItemVariant::where('sku_code', $sku)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $sku = $base . '-' . $counter;
            $counter++;
        }

        return $sku;
    }
}
