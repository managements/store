<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Partner;
use App\Remise;
use App\Storage\ProductStorage;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function create(Partner $client,ProductStorage $storage)
    {
        $providers = Partner::where('provider',1)->get();
        $products = $storage->getDiscountProducts($providers,$client);
        return view('discount.create',compact('providers','products','client'));
    }

    public function store(Request $request, Partner $client)
    {
        foreach ($request->remise as $remise_id => $remise) {
            $discount = Remise::find($remise_id);
            $discount->update([
                'remise'    => $remise
            ]);
        }
        return redirect()->route('client.show',compact('client'));
    }
}
