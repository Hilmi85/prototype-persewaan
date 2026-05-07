<?php

namespace App\Http\Controllers;

use App\Models\Item;

class RiasController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'itemVariants'])
            ->where('is_active', true)
            ->where('item_type', 'jasa_rias')
            ->orderBy('name')
            ->get();

        return view('customer.rias', compact('items'));
    }
}
