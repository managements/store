<?php

namespace App\Http\Controllers\Account;

use App\Account;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function show(Account $account)
    {
        if (
            $account->account === 'cheque_emitted'
            || $account->account === 'cheque_cashed'
            || $account->account === 'transfer_emitted'
            || $account->account === 'transfer_cashed'
            || $account->account === 'term_emitted'
            || $account->account === 'term_cashed'
        ) {
            $title = $account->account;
            return view('account.banque',compact('title','account'));
        }
        elseif ($account->account == 'tva' || $account->account == 'gain_loss') {
            return view('account.tva', compact('account'));
        }
        return abort(404);
    }
}
