<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PswRequest;
use Illuminate\Support\Facades\Hash;

class PswController extends Controller
{
    public function edit()
    {
        return view('user.psw');
    }

    public function update(PswRequest $request)
    {
        if (Hash::check($request->old_password, auth()->user()->getAuthPassword())) {
            auth()->user()->update([
                'password'  => bcrypt($request->password),
            ]);
            session()->flash('success', 'Votre a bien été Ajouté');
            return redirect()->route('home');
        }
        return back()->withErrors([
            'old_password'  => "Votre mot de passe Actuel est erroné"
        ])->withInput(['old_password']);
    }
}
