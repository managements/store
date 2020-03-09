<?php

namespace App\Http\Controllers\Account;

use App\AccountType;
use App\Http\Controllers\Controller;

class AccountTypeController extends Controller
{

    public function links()
    {
        $types = AccountType::where([
            ['type', '!=', 'stock'],
            ['type', '!=', 'charges'],
            ['type', '!=', 'caisses'],
        ])->get();
        return view('account.links',compact('types'));
    }

    public function show(AccountType $type)
    {
        if ($type->type === 'tva' || $type->type == 'gain_loss') {
            $account = $type->accounts()->first();
            return redirect()->route('account.show',compact('account'));
        }
        if ($type->type === "caisse") {
            return abort(404);
        }
        $accounts = $type->accounts;
        return view('account.show',compact('accounts'));
    }
}
