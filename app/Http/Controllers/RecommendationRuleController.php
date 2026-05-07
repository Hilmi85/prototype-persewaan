<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\RecommendationRule;
use App\Services\RuleBasedRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecommendationRuleController extends Controller
{
    public function __construct(private readonly RuleBasedRecommendationService $recommendationService)
    {
    }

    public function index()
    {
        $rules = RecommendationRule::with('bundle')
            ->orderBy('priority')
            ->orderBy('id')
            ->get();

        return view('admin.recommendation-rule.index', compact('rules'));
    }

    public function create()
    {
        $bundles = Bundle::where('is_active', true)
            ->orderBy('bundle_name')
            ->get();

        return view('admin.recommendation-rule.create', compact('bundles'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRule($request);
        $validated['rule_code'] = $validated['rule_code'] ?: $this->recommendationService->buildRuleCode($validated);

        RecommendationRule::create($validated);

        return redirect()
            ->route('recommendation-rules.index')
            ->with('success', 'Rule rekomendasi berhasil ditambahkan.');
    }

    public function edit(RecommendationRule $recommendationRule)
    {
        $bundles = Bundle::where('is_active', true)
            ->orderBy('bundle_name')
            ->get();

        return view('admin.recommendation-rule.edit', compact('recommendationRule', 'bundles'));
    }

    public function update(Request $request, RecommendationRule $recommendationRule)
    {
        $validated = $this->validateRule($request, $recommendationRule);
        $validated['rule_code'] = $validated['rule_code'] ?: $this->recommendationService->buildRuleCode($validated, $recommendationRule->id);

        $recommendationRule->update($validated);

        return redirect()
            ->route('recommendation-rules.index')
            ->with('success', 'Rule rekomendasi berhasil diperbarui.');
    }

    public function destroy(RecommendationRule $recommendationRule)
    {
        $recommendationRule->delete();

        return redirect()
            ->route('recommendation-rules.index')
            ->with('success', 'Rule rekomendasi berhasil dihapus.');
    }

    private function validateRule(Request $request, ?RecommendationRule $recommendationRule = null): array
    {
        return $request->validate([
            'rule_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('recommendation_rules', 'rule_code')->ignore($recommendationRule?->id),
            ],
            'rule_name' => 'required|string|max:255',
            'bundle_id' => 'required|exists:bundles,id',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Unisex',
            'butuh_rias' => 'nullable|boolean',
            'budget' => 'nullable|in:Rendah,Sedang,Tinggi',
            'priority' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);
    }
}
