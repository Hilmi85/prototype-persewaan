<?php

namespace App\Http\Controllers;

use App\Services\RuleBasedRecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(private readonly RuleBasedRecommendationService $recommendationService)
    {
    }

    public function index()
    {
        return view('customer.recommendation', $this->recommendationService->formOptions());
    }

    public function recommend(Request $request)
    {
        $validated = $request->validate([
            'jenis_acara' => 'required|string|max:255',
            'kategori_adat' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan,Unisex',
            'butuh_rias' => 'required|boolean',
            'budget' => 'required|in:Rendah,Sedang,Tinggi',
        ]);

        $result = $this->recommendationService->findRecommendation($validated);

        return view('customer.recommendation-result', array_merge($result, [
            'criteria' => $validated,
        ]));
    }
}
