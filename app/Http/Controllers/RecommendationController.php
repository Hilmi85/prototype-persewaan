<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Item;
use App\Models\RecommendationRule;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {
        /*
            Sumber data form customer:
            - Bundle: data rule bundle dari admin
            - Item: data kategori item, adat, dan gender dari Data Item
            - RecommendationRule: rule tambahan kalau masih digunakan
        */

        $bundles = Bundle::where('is_active', true)->get();

        $rules = RecommendationRule::where('is_active', true)->get();

        $items = Item::with('category')
            ->where('is_active', true)
            ->get();

        $jenisAcaraOptions = collect()
            ->merge($bundles->pluck('jenis_acara'))
            ->merge($rules->pluck('jenis_acara'))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => trim($value))
            ->unique()
            ->values();

        $kategoriItemOptions = collect()
            ->merge($bundles->pluck('kategori_item'))
            ->merge($items->map(function ($item) {
                return $item->category->cat_name ?? null;
            }))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => trim($value))
            ->unique()
            ->values();

        $kategoriAdatOptions = collect()
            ->merge($bundles->pluck('kategori_adat'))
            ->merge($rules->pluck('kategori_adat'))
            ->merge($items->pluck('adat_category'))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => trim($value))
            ->unique()
            ->values();

        $genderOptions = collect()
            ->merge($bundles->pluck('gender'))
            ->merge($rules->pluck('gender'))
            ->merge($items->pluck('gender'))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => trim($value))
            ->unique()
            ->values();

        $budgetOptions = collect()
            ->merge($bundles->pluck('budget_category'))
            ->merge($rules->pluck('budget'))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => trim($value))
            ->unique()
            ->values();

        return view('customer.recommendation', compact(
            'jenisAcaraOptions',
            'kategoriItemOptions',
            'kategoriAdatOptions',
            'genderOptions',
            'budgetOptions'
        ));
    }

    public function recommend(Request $request)
    {
        $validated = $request->validate([
            'jenis_acara' => 'required|string|max:255',
            'kategori_item' => 'required|string|max:255',
            'kategori_adat' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'butuh_rias' => 'required|boolean',

            /*
                budget_category dipakai karena field Bundle sebelumnya memakai budget_category.
                budget tetap diterima supaya kompatibel dengan form/controller lama.
            */
            'budget_category' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:255',
        ]);

        $budget = $validated['budget_category'] ?? $validated['budget'] ?? null;

        $validated['budget_category'] = $budget;
        $validated['budget'] = $budget;

        /*
            RULE UTAMA:

            IF jenis_acara
            AND kategori_item
            AND kategori_adat
            AND gender
            AND butuh_rias
            AND budget_category
            THEN tampilkan bundle yang sesuai
            ELSE tampilkan Paket Custom
        */

        $bundle = Bundle::with(['bundleItems.item'])
            ->where('is_active', true)
            ->where('jenis_acara', $validated['jenis_acara'])
            ->where('kategori_item', $validated['kategori_item'])
            ->where('kategori_adat', $validated['kategori_adat'])
            ->where('gender', $validated['gender'])
            ->where('butuh_rias', $validated['butuh_rias'])
            ->where('budget_category', $budget)
            ->orderBy('price', 'asc')
            ->first();

        /*
            RecommendationRule tetap dicek, tapi bukan sumber utama form.
            Sumber utama output tetap Bundle karena Bundle adalah data admin yang sudah dibuat.
        */

        $selectedRule = RecommendationRule::with(['bundle.bundleItems.item'])
            ->where('is_active', true)
            ->where(function ($q) use ($validated) {
                $q->where('jenis_acara', $validated['jenis_acara'])
                  ->orWhereNull('jenis_acara');
            })
            ->where(function ($q) use ($validated) {
                $q->where('kategori_adat', $validated['kategori_adat'])
                  ->orWhereNull('kategori_adat');
            })
            ->where(function ($q) use ($validated) {
                $q->where('gender', $validated['gender'])
                  ->orWhereNull('gender');
            })
            ->where(function ($q) use ($validated) {
                $q->where('butuh_rias', $validated['butuh_rias'])
                  ->orWhereNull('butuh_rias');
            })
            ->where(function ($q) use ($budget) {
                $q->where('budget', $budget)
                  ->orWhereNull('budget');
            })
            ->orderBy('priority')
            ->first();

        /*
            Kalau tidak ada bundle yang cocok dari tabel Bundle,
            tapi RecommendationRule punya bundle yang cocok, gunakan bundle dari rule.
        */

        if (!$bundle && $selectedRule && $selectedRule->bundle) {
            $ruleBundle = $selectedRule->bundle;

            $isRuleBundleMatch =
                ($ruleBundle->jenis_acara === $validated['jenis_acara']) &&
                ($ruleBundle->kategori_adat === $validated['kategori_adat']) &&
                ($ruleBundle->gender === $validated['gender']) &&
                ((string) $ruleBundle->butuh_rias === (string) $validated['butuh_rias']) &&
                ($ruleBundle->budget_category === $budget);

            if (
                property_exists($ruleBundle, 'kategori_item') ||
                isset($ruleBundle->kategori_item)
            ) {
                $isRuleBundleMatch = $isRuleBundleMatch &&
                    ($ruleBundle->kategori_item === $validated['kategori_item']);
            }

            if ($isRuleBundleMatch) {
                $bundle = $ruleBundle;
            }
        }

        /*
            Alternatif bundle:
            tetap diambil dari data admin yang aktif dan mendekati input user.
        */

        $alternativeBundles = Bundle::where('is_active', true)
            ->when($bundle, function ($q) use ($bundle) {
                $q->where('id', '!=', $bundle->id);
            })
            ->where(function ($q) use ($validated, $budget) {
                $q->where('jenis_acara', $validated['jenis_acara'])
                  ->orWhere('kategori_item', $validated['kategori_item'])
                  ->orWhere('kategori_adat', $validated['kategori_adat'])
                  ->orWhere('gender', $validated['gender'])
                  ->orWhere('budget_category', $budget);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('customer.recommendation-result', compact(
            'validated',
            'selectedRule',
            'bundle',
            'alternativeBundles'
        ));
    }
}
