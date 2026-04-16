<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request){
        $query = Product::with('vendor');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('vendor')) {
            $query->where('vendor_id', $request->vendor);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->latest()->paginate(9)->withQueryString();
        $vendors = Vendor::orderBy('store_name')->get();

        return view('marketplace.index', compact('products', 'vendors'));
    }
}
