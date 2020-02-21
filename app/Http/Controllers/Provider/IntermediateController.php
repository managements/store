<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\IntermediateRequest;
use App\Intermediate;
use App\Storage\IntermediateStorage;
use Illuminate\Http\Request;

class IntermediateController extends Controller
{
    public function index()
    {
        $intermediates = Intermediate::all();
        return view('intermediate.index',compact('intermediates'));
    }
    public function create()
    {
        return view('intermediate.create');
    }

    public function store(IntermediateRequest $request,IntermediateStorage $storage)
    {
        $storage->add($request->all(['name']));
        session()->flash('success', 'success');
        return redirect()->route('intermediate.index');
    }

    public function edit(Intermediate $intermediate)
    {
        return view('intermediate.edit',compact('intermediate'));
    }

    public function update(IntermediateRequest $request, Intermediate $intermediate,IntermediateStorage $storage)
    {
        $storage->update($request->all(['name']),$intermediate);
        session()->flash('success', 'success');
        return redirect()->route('intermediate.index');
    }

}
