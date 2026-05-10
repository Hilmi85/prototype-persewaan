<?php

namespace App\Http\Controllers;

use App\Models\ItemVariant;
use App\Services\RentalAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function variant(Request $request, RentalAvailabilityService $availabilityService)
    {
        $validated = $request->validate([
            'item_variant_id' => 'required|exists:item_variants,id',
            'rental_start' => 'required|date|after_or_equal:today',
            'rental_end' => 'required|date|after_or_equal:rental_start',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $variant = ItemVariant::with('item')
            ->where('is_active', true)
            ->findOrFail($validated['item_variant_id']);

        $quantity = max(1, (int) ($validated['quantity'] ?? 1));

        $availability = $availabilityService->availability(
            $variant,
            $validated['rental_start'],
            $validated['rental_end']
        );

        $isAvailable = $availability['available_stock'] >= $quantity;

        $label = trim(
            ($variant->item->name ?? 'Item') .
            ' - ' .
            ($variant->size ?? '') .
            ($variant->color ? ' / ' . $variant->color : '')
        );

        return response()->json([
            'available' => $isAvailable,
            'variant_label' => $label,
            'requested_quantity' => $quantity,
            'base_stock' => $availability['base_stock'],
            'reserved_stock' => $availability['reserved_stock'],
            'available_stock' => $availability['available_stock'],
            'rental_start_label' => Carbon::parse($validated['rental_start'])->format('d-m-Y'),
            'rental_end_label' => Carbon::parse($validated['rental_end'])->format('d-m-Y'),
            'message' => $isAvailable
                ? 'Tersedia untuk tanggal tersebut. Sisa stok pada rentang tanggal ini: ' . $availability['available_stock'] . '.'
                : 'Tidak tersedia / stok kurang untuk tanggal tersebut. Sisa stok pada rentang tanggal ini: ' . $availability['available_stock'] . '.',
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
