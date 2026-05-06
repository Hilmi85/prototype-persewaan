<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\RecommendationRule;
use Illuminate\Http\Request;

class RecommendationRuleController extends Controller
{
    public function index()
    {
        $rules = RecommendationRule::with('bundle')->orderBy('priority')->get();
        return view('admin.recommendation-rule.index', compact('rules'));
    }

    public function create()
    {
        $bundles = Bundle::where('is_active', true)->orderBy('bundle_name')->get();
        return view('admin.recommendation-rule.create', compact('bundles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rule_code' => 'nullable|string|max:255|unique:recommendation_rules,rule_code',
            'rule_name' => 'nullable|string|max:255',
            'bundle_id' => 'nullable|exists:bundles,id',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'butuh_rias' => 'nullable|boolean',
            'budget' => 'nullable|in:Rendah,Sedang,Tinggi',
            'size' => 'nullable|string|max:50',
            'priority' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        RecommendationRule::create($validated);

        return redirect()->route('recommendation-rules.index')->with('success', 'Rule berhasil ditambahkan.');
    }

    public function edit(RecommendationRule $recommendationRule)
    {
        $bundles = Bundle::where('is_active', true)->orderBy('bundle_name')->get();
        return view('admin.recommendation-rule.edit', compact('recommendationRule', 'bundles'));
    }

    public function update(Request $request, RecommendationRule $recommendationRule)
    {
        $validated = $request->validate([
            'rule_code' => 'nullable|string|max:255|unique:recommendation_rules,rule_code,' . $recommendationRule->id,
            'rule_name' => 'nullable|string|max:255',
            'bundle_id' => 'nullable|exists:bundles,id',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'butuh_rias' => 'nullable|boolean',
            'budget' => 'nullable|in:Rendah,Sedang,Tinggi',
            'size' => 'nullable|string|max:50',
            'priority' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $recommendationRule->update($validated);

        return redirect()->route('recommendation-rules.index')->with('success', 'Rule berhasil diperbarui.');
    }

    public function destroy(RecommendationRule $recommendationRule)
    {
        $recommendationRule->delete();

        return redirect()->route('recommendation-rules.index')->with('success', 'Rule berhasil dihapus.');
    }
}
