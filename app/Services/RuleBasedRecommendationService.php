<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\RecommendationRule;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RuleBasedRecommendationService
{
    public function formOptions(): array
    {
        $bundles = Bundle::query()->where('is_active', true)->get();
        $rules = RecommendationRule::query()->where('is_active', true)->get();
        $items = Item::with('category')->where('is_active', true)->get();

        return [
            'jenisAcaraOptions' => $this->cleanOptions(
                collect(['Pernikahan', 'Lamaran', 'Wisuda', 'Photoshoot', 'Acara Adat'])
                    ->merge($bundles->pluck('jenis_acara'))
                    ->merge($rules->pluck('jenis_acara'))
            ),
            'kategoriItemOptions' => $this->cleanOptions(
                $items->map(fn ($item) => $item->category->cat_name ?? null)
            ),
            'kategoriAdatOptions' => $this->cleanOptions(
                $bundles->pluck('kategori_adat')
                    ->merge($rules->pluck('kategori_adat'))
                    ->merge($items->pluck('adat_category'))
            ),
            'genderOptions' => $this->cleanOptions(
                collect(['Laki-laki', 'Perempuan', 'Unisex', 'Pasangan'])
                    ->merge($bundles->pluck('gender'))
                    ->merge($rules->pluck('gender'))
                    ->merge($items->pluck('gender'))
            ),
            'budgetOptions' => $this->cleanOptions(
                collect(['Rendah', 'Sedang', 'Tinggi'])
                    ->merge($bundles->pluck('budget_category'))
                    ->merge($rules->pluck('budget'))
            ),
        ];
    }

    public function variantOptions(): Collection
    {
        return ItemVariant::with('item.category')
            ->where('is_active', true)
            ->where('available_stock', '>', 0)
            ->get()
            ->map(function (ItemVariant $variant) {
                $item = $variant->item;

                return [
                    'id' => $variant->id,
                    'item_id' => $item->id ?? $variant->item_id,
                    'item_name' => $item->name ?? '-',
                    'kategori_item' => $item->category->cat_name ?? '-',
                    'item_type' => $item->item_type ?? '-',
                    'kategori_adat' => $item->adat_category ?? '-',
                    'gender' => $item->gender ?? '-',
                    'sku_code' => $variant->sku_code ?? '-',
                    'size' => $variant->size ?? '-',
                    'color' => $variant->color ?? '-',
                    'stock' => $variant->stock ?? 0,
                    'available_stock' => $variant->available_stock ?? 0,
                    'daily_price' => (float) ($variant->daily_price ?? $item->price ?? 0),
                    'is_rias' => $this->isRiasVariant($variant),
                ];
            })
            ->values();
    }

    public function findRecommendation(array $criteria): array
    {
        $criteria['budget'] = $criteria['budget_category'] ?? $criteria['budget'] ?? null;

        $selectedRule = $this->findMatchingRule($criteria);
        $bundle = $selectedRule?->bundle;

        if (!$bundle) {
            $bundle = $this->findMatchingBundle($criteria);
        }

        $alternativeBundles = $this->alternativeBundles($criteria, $bundle?->id);

        return [
            'bundle' => $bundle,
            'selectedRule' => $selectedRule,
            'alternativeBundles' => $alternativeBundles,
        ];
    }

    public function findMatchingRule(array $criteria): ?RecommendationRule
    {
        return RecommendationRule::with(['bundle.bundleItems.item.category', 'bundle.bundleItems.itemVariant'])
            ->where('is_active', true)
            ->whereHas('bundle', fn ($q) => $q->where('is_active', true))
            ->orderBy('priority')
            ->get()
            ->first(fn (RecommendationRule $rule) => $this->ruleMatches($rule, $criteria));
    }

    public function findMatchingBundle(array $criteria): ?Bundle
    {
        return Bundle::with(['bundleItems.item.category', 'bundleItems.itemVariant'])
            ->where('is_active', true)
            ->where(function ($q) use ($criteria) {
                $q->where('jenis_acara', $criteria['jenis_acara'])->orWhereNull('jenis_acara');
            })
            ->where(function ($q) use ($criteria) {
                $q->where('kategori_adat', $criteria['kategori_adat'])->orWhereNull('kategori_adat');
            })
            ->where(function ($q) use ($criteria) {
                $q->where('gender', $criteria['gender'])->orWhere('gender', 'Unisex')->orWhereNull('gender');
            })
            ->where(function ($q) use ($criteria) {
                $q->where('butuh_rias', (bool) $criteria['butuh_rias'])->orWhereNull('butuh_rias');
            })
            ->where(function ($q) use ($criteria) {
                $q->where('budget_category', $criteria['budget'])->orWhereNull('budget_category');
            })
            ->whereHas('bundleItems.item.category', function ($query) use ($criteria) {
                $query->where('cat_name', $criteria['kategori_item']);
            })
            ->orderBy('price')
            ->first();
    }

    public function alternativeBundles(array $criteria, ?int $excludeBundleId = null): Collection
    {
        return Bundle::with(['bundleItems.item.category'])
            ->where('is_active', true)
            ->when($excludeBundleId, fn ($q) => $q->where('id', '!=', $excludeBundleId))
            ->where(function ($q) use ($criteria) {
                $q->where('jenis_acara', $criteria['jenis_acara'] ?? null)
                    ->orWhere('kategori_adat', $criteria['kategori_adat'] ?? null)
                    ->orWhere('gender', $criteria['gender'] ?? null)
                    ->orWhere('budget_category', $criteria['budget'] ?? null);
            })
            ->whereHas('bundleItems.item.category', function ($query) use ($criteria) {
                $query->where('cat_name', $criteria['kategori_item'] ?? null);
            })
            ->latest()
            ->take(4)
            ->get();
    }

    public function ruleMatches(RecommendationRule $rule, array $criteria): bool
    {
        if (!$this->fieldMatches($rule->jenis_acara, $criteria['jenis_acara'] ?? null)) {
            return false;
        }

        if (!$this->fieldMatches($rule->kategori_item, $criteria['kategori_item'] ?? null)) {
            return false;
        }

        if (!$this->fieldMatches($rule->kategori_adat, $criteria['kategori_adat'] ?? null)) {
            return false;
        }

        if (!$this->genderMatches($rule->gender, $criteria['gender'] ?? null)) {
            return false;
        }

        if (!is_null($rule->butuh_rias) && (bool) $rule->butuh_rias !== (bool) ($criteria['butuh_rias'] ?? false)) {
            return false;
        }

        if (!$this->fieldMatches($rule->budget, $criteria['budget'] ?? null)) {
            return false;
        }

        if (!$rule->bundle) {
            return false;
        }

        return $this->bundleHasCategory($rule->bundle, $criteria['kategori_item'] ?? null);
    }

    public function bundleHasCategory(Bundle $bundle, ?string $category): bool
    {
        if (!filled($category)) {
            return true;
        }

        return $bundle->bundleItems->contains(function ($bundleItem) use ($category) {
            return ($bundleItem->item->category->cat_name ?? null) === $category;
        });
    }

    public function buildRuleCode(array $data, ?int $ignoreId = null): string
    {
        $base = collect([
            'RULE',
            $data['jenis_acara'] ?? null,
            $data['kategori_item'] ?? null,
            $data['kategori_adat'] ?? null,
            $data['gender'] ?? null,
            $data['budget'] ?? null,
            $data['priority'] ?? null,
        ])->filter(fn ($value) => filled($value))
            ->map(fn ($value) => Str::upper(Str::slug((string) $value, '-')))
            ->implode('-');

        $base = $base ?: 'RULE';
        $code = $base;
        $counter = 1;

        while (RecommendationRule::where('rule_code', $code)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $code = $base . '-' . $counter;
            $counter++;
        }

        return $code;
    }

    private function fieldMatches($ruleValue, $inputValue): bool
    {
        if (!filled($ruleValue)) {
            return true;
        }

        return (string) $ruleValue === (string) $inputValue;
    }

    private function genderMatches($ruleGender, $inputGender): bool
    {
        if (!filled($ruleGender)) {
            return true;
        }

        if ((string) $ruleGender === 'Unisex') {
            return true;
        }

        if ((string) $inputGender === 'Pasangan') {
            return in_array($ruleGender, ['Pasangan', 'Unisex'], true);
        }

        return (string) $ruleGender === (string) $inputGender;
    }

    private function cleanOptions(Collection $values): Collection
    {
        return $values
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => trim((string) $value))
            ->unique()
            ->sort()
            ->values();
    }

    private function isRiasVariant(ItemVariant $variant): bool
    {
        $item = $variant->item;
        $category = Str::lower($item->category->cat_name ?? '');
        $type = Str::lower($item->item_type ?? '');
        $name = Str::lower($item->name ?? '');

        return str_contains($category, 'rias') || str_contains($type, 'rias') || str_contains($name, 'rias');
    }
}
