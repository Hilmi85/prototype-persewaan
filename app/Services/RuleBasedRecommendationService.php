<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\RecommendationRule;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RuleBasedRecommendationService
{
    public function formOptions(): array
    {
        $bundles = Bundle::query()->where('is_active', true)->get();
        $rules = RecommendationRule::query()->where('is_active', true)->get();

        return [
            'jenisAcaraOptions' => $this->cleanOptions(
                collect(['Pernikahan', 'Lamaran', 'Wisuda', 'Photoshoot', 'Acara Adat'])
                    ->merge($bundles->pluck('jenis_acara'))
                    ->merge($rules->pluck('jenis_acara'))
            ),
            'kategoriAdatOptions' => $this->cleanOptions(
                collect(['Jawa', 'Sunda', 'Bali', 'Minang', 'Modern'])
                    ->merge($bundles->pluck('kategori_adat'))
                    ->merge($rules->pluck('kategori_adat'))
            ),
            'genderOptions' => collect(['Laki-laki', 'Perempuan', 'Unisex']),
            'budgetOptions' => collect(['Rendah', 'Sedang', 'Tinggi']),
        ];
    }

    public function findRecommendation(array $criteria): array
    {
        $criteria['budget'] = $criteria['budget_category'] ?? $criteria['budget'] ?? null;
        $criteria['butuh_rias'] = filter_var($criteria['butuh_rias'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $selectedRule = $this->findMatchingRule($criteria);
        $bundle = $selectedRule?->bundle;

        if (!$bundle) {
            $bundle = $this->findMatchingBundle($criteria);
        }

        if ($bundle) {
            $bundle->loadMissing(['bundleItems.item.category', 'bundleItems.item.itemVariants']);
        }

        $customBundle = Bundle::with(['bundleItems.item.category', 'bundleItems.item.itemVariants'])
            ->where('is_active', true)
            ->where('is_custom', true)
            ->orderBy('price')
            ->first();

        return [
            'bundle' => $bundle,
            'selectedRule' => $selectedRule,
            'customBundle' => $customBundle,
            'alternativeBundles' => $this->alternativeBundles($criteria, $bundle?->id),
            'availabilitySummary' => $bundle ? $this->availabilitySummary($bundle) : null,
        ];
    }

    public function findMatchingRule(array $criteria): ?RecommendationRule
    {
        return RecommendationRule::with(['bundle.bundleItems.item.category', 'bundle.bundleItems.item.itemVariants'])
            ->where('is_active', true)
            ->whereHas('bundle', fn ($query) => $query->where('is_active', true))
            ->orderBy('priority')
            ->orderBy('id')
            ->get()
            ->first(fn (RecommendationRule $rule) => $this->ruleMatches($rule, $criteria));
    }

    public function findMatchingBundle(array $criteria): ?Bundle
    {
        return Bundle::with(['bundleItems.item.category', 'bundleItems.item.itemVariants'])
            ->where('is_active', true)
            ->where('is_custom', false)
            ->where(function ($query) use ($criteria) {
                $query->where('jenis_acara', $criteria['jenis_acara'])->orWhereNull('jenis_acara');
            })
            ->where(function ($query) use ($criteria) {
                $query->where('kategori_adat', $criteria['kategori_adat'])->orWhereNull('kategori_adat');
            })
            ->where(function ($query) use ($criteria) {
                $query->where('gender', $criteria['gender'])->orWhere('gender', 'Unisex')->orWhereNull('gender');
            })
            ->where(function ($query) use ($criteria) {
                $query->where('butuh_rias', (bool) $criteria['butuh_rias'])->orWhereNull('butuh_rias');
            })
            ->where(function ($query) use ($criteria) {
                $query->where('budget_category', $criteria['budget'])->orWhereNull('budget_category');
            })
            ->orderBy('price')
            ->first();
    }

    public function alternativeBundles(array $criteria, ?int $excludeBundleId = null): Collection
    {
        return Bundle::with(['bundleItems.item.category', 'bundleItems.item.itemVariants'])
            ->where('is_active', true)
            ->when($excludeBundleId, fn ($query) => $query->where('id', '!=', $excludeBundleId))
            ->where(function ($query) use ($criteria) {
                $query->where('jenis_acara', $criteria['jenis_acara'] ?? null)
                    ->orWhere('kategori_adat', $criteria['kategori_adat'] ?? null)
                    ->orWhere('gender', $criteria['gender'] ?? null)
                    ->orWhere('budget_category', $criteria['budget'] ?? null)
                    ->orWhere('is_custom', true);
            })
            ->orderBy('is_custom')
            ->orderBy('price')
            ->take(4)
            ->get();
    }

    public function ruleMatches(RecommendationRule $rule, array $criteria): bool
    {
        if (!$this->fieldMatches($rule->jenis_acara, $criteria['jenis_acara'] ?? null)) {
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

        return (bool) $rule->bundle;
    }

    public function availabilitySummary(Bundle $bundle): array
    {
        $bundle->loadMissing(['bundleItems.item.itemVariants']);

        $items = $bundle->bundleItems->map(function ($bundleItem) {
            $item = $bundleItem->item;
            $availableVariants = $item
                ? $item->itemVariants
                    ->where('is_active', true)
                    ->where('available_stock', '>', 0)
                    ->values()
                : collect();

            return [
                'bundle_item' => $bundleItem,
                'item' => $item,
                'available_variants' => $availableVariants,
                'is_available' => $availableVariants->isNotEmpty(),
            ];
        });

        return [
            'total_items' => $items->count(),
            'available_items' => $items->where('is_available', true)->count(),
            'unavailable_items' => $items->where('is_available', false)->count(),
            'items' => $items,
        ];
    }

    public function buildRuleCode(array $data, ?int $ignoreId = null): string
    {
        $base = collect([
            'RULE',
            $data['jenis_acara'] ?? null,
            $data['kategori_adat'] ?? null,
            $data['gender'] ?? null,
            array_key_exists('butuh_rias', $data) && !is_null($data['butuh_rias'])
                ? ((bool) $data['butuh_rias'] ? 'RIAS' : 'TANPA-RIAS')
                : null,
            $data['budget'] ?? null,
            $data['priority'] ?? null,
        ])->filter(fn ($value) => filled($value))
            ->map(fn ($value) => Str::upper(Str::slug((string) $value, '-')))
            ->implode('-');

        $base = $base ?: 'RULE';
        $code = $base;
        $counter = 1;

        while (RecommendationRule::where('rule_code', $code)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $code = $base . '-' . $counter;
            $counter++;
        }

        return $code;
    }

    private function fieldMatches(mixed $ruleValue, mixed $inputValue): bool
    {
        if (!filled($ruleValue)) {
            return true;
        }

        return Str::lower(trim((string) $ruleValue)) === Str::lower(trim((string) $inputValue));
    }

    private function genderMatches(mixed $ruleGender, mixed $inputGender): bool
    {
        if (!filled($ruleGender)) {
            return true;
        }

        if ((string) $ruleGender === 'Unisex') {
            return true;
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
}
