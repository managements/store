<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Partner;
use App\Price;
use App\Storage\ProductStorage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(ProductStorage $storage)
    {
        $providers = Partner::where('provider',1)->get();
        $products = $storage->getPriceProducts($providers);
        return view('price.create',compact('providers','products'));
    }

    public function store(Request $request)
    {
        foreach ($request->product_buy as $product_id => $price) {
            Price::create([
                'buy'           => $price,
                'sale'          => $request->product_sale[$product_id],
                'product_id'    => $product_id
            ]);
        }
        return redirect()->route('price.create');
    }
}
