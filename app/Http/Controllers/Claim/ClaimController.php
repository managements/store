<?php

namespace App\Http\Controllers\Claim;

use App\Claim;
use App\Http\Controllers\Controller;
use App\Http\Requests\Claim\ClaimRequest;
use App\Partner;
use App\Storage\ClaimStorage;

class ClaimController extends Controller
{
    public function search()
    {
        $providers = Partner::where('provider', 1)->get();
        return view('claim.search',compact('providers'));
    }

    public function index()
    {
        $claims = Claim::where('debt',0)->get();
        foreach ($claims as $claim) {
            $total = 0;
            if($claim->cheque) {
                $total = $total + $claim->cheque->price;
            }
            if($claim->transfer) {
                $total = $total + $claim->transfer->price;
            }
            $claim->total = $total;
        }
        $provider = null;
        if (isset($claims[0])) {
            $provider = $claims[0]->partner;
        }

        return view('claim.index',compact('claims','provider'));
    }

    public function create(Partner $provider)
    {
        $trades = $provider->trades;
        return view('claim.create',compact('provider','trades'));
    }

    public function store(ClaimRequest $request,Partner $provider,ClaimStorage $storage)
    {
        $storage->add($request->all(),$provider);
        session()->flash('success', 'Success');
        return redirect()->route('claim.index');
    }

    public function edit(Partner $provider,Claim $claim)
    {
        $details = $claim->claim_details;
        $trades = $provider->trades;
        foreach ($trades as $trade) {
            if ($trade->bl) {
                $trade->term_total = $trade->term;
                $trade->term_value = null;
                $trade->term_payed = null;
                foreach ($details as $detail) {
                    if ($detail->trade_id == $trade->id) {
                        $trade->term_value = $detail->term;
                        $trade->term_total = $detail->term + $trade->term;
                        $trade->term_payed = $detail->term;
                        $trade->inv = $detail->inv;
                    }
                }
            }
        }
        return view('claim.edit',compact('claim','provider','trades'));
    }

    public function update(ClaimRequest $request,Partner $provider, Claim $claim,ClaimStorage $storage)
    {
        $storage->sub($claim);
        $claimStorage = new ClaimStorage();
        $claimStorage->add($request->all(),$provider,$claim);
        return redirect()->route('claim.index');
    }

    public function destroy(Partner $provider, Claim $claim, ClaimStorage $storage)
    {
        $storage->sub($claim);
        $claim->delete();
        return redirect()->route('claim.index');
    }

}
