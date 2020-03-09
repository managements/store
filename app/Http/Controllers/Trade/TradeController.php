<?php

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trade\TransactionRequest;
use App\Partner;
use App\Storage\ProductStorage;
use App\Storage\TradeStorage;
use App\Trade;
use App\Transaction;
use Illuminate\Http\Request;

class TradeController extends Controller
{

    public function index()
    {
        $transactions = Transaction::limit(50)->orderby('id','desc')->get();

        return view('trade.index',compact('transactions'));
    }

    public function create(ProductStorage $storage)
    {
        $providers = Partner::where('provider',1)->get();
        $products = $storage->getTradeProduct($providers);
        return view('trade.create',compact('products','providers'));
    }

    public function store(TransactionRequest $request,TradeStorage $storage)
    {
        $request->request->add(['partner' => 1]);
        $storage->add($request->all());
        return redirect()->route('transaction.index');
    }

    public function edit(Transaction $transaction,ProductStorage $storage)
    {
        $providers = Partner::where('provider',1)->get();
        $products = $storage->getTradeOrderProduct($providers,$transaction);
        if($trade = $transaction->sale) {
            $payments = $transaction->sale->payments;
        }
        else{
            $payments = $transaction->buy->payments;
        }
        return view('trade.edit',compact('products','providers','payments','trade','transaction'));
    }

    public function update(TransactionRequest $request,Transaction $transaction,TradeStorage $storage)
    {
        $request->request->add(['partner' => 1]);
        $transaction = $storage->updated($request->all(),$transaction);
        return redirect()->route('transaction.edit',compact('transaction'));
    }
}
