<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::latest()->get();
        return view('admin.bundle.index', compact('bundles'));
    }

    public function create()
    {
        return view('admin.bundle.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bundle_code' => 'required|string|max:255|unique:bundles,bundle_code',
            'bundle_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'butuh_rias' => 'required|boolean',
            'budget_category' => 'nullable|in:Rendah,Sedang,Tinggi',
            'price' => 'required|numeric|min:0',
            'is_custom' => 'required|boolean',
            'is_active' => 'required|boolean',
        ]);

        Bundle::create($validated);

        return redirect()->route('bundles.index')->with('success', 'Bundle berhasil ditambahkan.');
    }

    public function edit(Bundle $bundle)
    {
        return view('admin.bundle.edit', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $validated = $request->validate([
            'bundle_code' => 'required|string|max:255|unique:bundles,bundle_code,' . $bundle->id,
            'bundle_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'jenis_acara' => 'nullable|string|max:255',
            'kategori_adat' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'butuh_rias' => 'required|boolean',
            'budget_category' => 'nullable|in:Rendah,Sedang,Tinggi',
            'price' => 'required|numeric|min:0',
            'is_custom' => 'required|boolean',
            'is_active' => 'required|boolean',
        ]);

        $bundle->update($validated);

        return redirect()->route('bundles.index')->with('success', 'Bundle berhasil diperbarui.');
    }

    public function destroy(Bundle $bundle)
    {
        $bundle->delete();

        return redirect()->route('bundles.index')->with('success', 'Bundle berhasil dihapus.');
    }
}
