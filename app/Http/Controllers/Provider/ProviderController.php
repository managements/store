<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\ProviderRequest;
use App\Partner;
use App\Storage\ProviderStorage;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Partner::where('provider',1)->get();
        return view('provider.index',compact('providers'));
    }

    public function create()
    {
        return view('provider.create');
    }

    public function store(ProviderRequest $request,ProviderStorage $storage)
    {
        $storage->add($request->all());
        session()->flash('success','success');
        return redirect()->route('provider.index');
    }

    public function show(Partner $provider,ProviderStorage $storage)
    {
        $retaineds = $storage->retained($provider);
        $details = $storage->account($provider);
        $sold = $storage->sold($provider);
        return view('provider.show',compact('provider','details','retaineds','sold'));
    }

    public function edit(Partner $provider)
    {
        return view('provider.edit',compact('provider'));
    }

    public function update(Request $request, Partner $provider,ProviderStorage $storage)
    {
        $storage->update($provider,$request->all());
        session()->flash('success','success');
        return redirect()->route('provider.index');
    }
}
