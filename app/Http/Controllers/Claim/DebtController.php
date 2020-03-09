<?php

namespace App\Http\Controllers\Claim;

use App\Claim;
use App\Http\Controllers\Controller;
use App\Http\Requests\Claim\ClaimRequest;
use App\Partner;
use App\Storage\DebtStorage;

class DebtController extends Controller
{

    public function search()
    {
        $partners = Partner::where('provider', 0)->get();
        $clients = [];
        foreach ($partners as $partner){
            if ($partner->trades()->where('inv','!=',null)->first()){
                $clients[] = $partner;
            }
        }
        return view('debt.search',compact('clients'));
    }

    public function index()
    {
        $debts =  Claim::where('debt',1)->get();
        foreach ($debts as $claim) {
            $total = 0;
            if($claim->cheque) {
                $total = $total + $claim->cheque->price;
            }
            if($claim->transfer) {
                $total = $total + $claim->transfer->price;
            }
            $claim->total = $total;
        }
        $client = null;
        if (isset($debts[0])) {
            $client = $debts[0]->partner;
        }

        return view('debt.index',compact('debts','client'));
    }

    public function create(Partner $client)
    {
        $trades = $client->trades;
        return view('debt.create',compact('client','trades'));
    }

    public function store(ClaimRequest $request,Partner $client,DebtStorage $storage)
    {
        $storage->add($request->all(),$client);
        session()->flash('success', 'Success');
        return redirect()->route('debt.index');
    }

    public function edit(Partner $client,Claim $debt)
    {
        $details = $debt->claim_details;
        $trades = $client->trades;
        foreach ($trades as $trade) {
            if ($trade->inv) {
                $trade->term_total = $trade->term;
                $trade->term_value = null;
                $trade->term_payed = null;
                foreach ($details as $detail) {
                    if ($detail->trade_id == $trade->id) {
                        $trade->term_value = $detail->term;
                        $trade->term_total = $detail->term + $trade->term;
                        $trade->term_payed = $detail->term;
                    }
                }
            }
        }
        return view('debt.edit',compact('debt','client','trades'));
    }

    public function update(ClaimRequest $request,Partner $client, Claim $debt,DebtStorage $storage)
    {
        $storage->sub($debt);
        $claimStorage = new DebtStorage();
        $claimStorage->add($request->all(),$client,$debt);
        return redirect()->route('debt.index');
    }

    public function destroy(Partner $client, Claim $debt, DebtStorage $storage)
    {
        $storage->sub($debt);
        $debt->delete();
        return redirect()->route('debt.index');
    }
}
