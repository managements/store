<?php

namespace App\Storage;


use App\Account;
use App\Claim;
use App\Partner;
use App\Payment;
use App\Trade;

class DebtStorage
{
    private $cheque_id;
    private $transfer_id;
    private $details = [];
    private $cash_id;

    public function add(array $data, Partner $provider,?Claim $claim = null)
    {
        // create Claim
        if(is_null($claim)) {
            $claim = $provider->claims()->create([
                'debt'          => 1,
                'creator_id'    => auth()->id()
            ]);
        }
        // create Claim details and sub Term and update invoice nbr in Trade
        $this->addClaimDetails($data,$claim);
        // create payment
        $this->addPayments($data);
        // update Claim
        $claim->update([
            'cheque_id'     => $this->cheque_id,
            'transfer_id'   => $this->transfer_id,
            'cash_id'       => $this->cash_id
        ]);
        $this->addAccount($claim);
    }

    private function addClaimDetails(array $data, Claim $claim)
    {
        foreach ($data['price'] as $trade_id => $price) {
            if (!is_null($price) && $price > 0) {
                $trade = Trade::find($trade_id);
                $detail = $claim->claim_details()->create([
                    'trade_id'      => $trade->id,
                    'inv'           => $trade->inv,
                    'term'          => $price
                ]);
                $this->details[] = $detail;
                $this->subTerm($trade,$price);
                // todo:: accounts
            }
        }
    }

    private function addAccount(Claim $claim)
    {
        $mode = '';
        if(isset($this->details['cheque_nbr'])) {
            $mode .= ' Chèque N° ' . $this->details['cheque_nbr'];
        }
        if(isset($this->details['transfer_nbr'])) {
            $mode .= ' Virement N° ' . $this->details['transfer_nbr'];
        }
        foreach ($this->details as $key => $claim_detail) {
            // provider
            if (is_int($key)) {
                $label = 'FAC-' . $claim_detail->inv;
                // provider
                $claim_detail->account_details()->create([
                    'label'             => $label,
                    'detail'            => $mode,
                    'cr'                => $claim_detail->term,
                    'account_id'        => $claim->partner->compte->id
                ]);
                // a term emis
                $claim_detail->account_details()->create([
                    'label'             => $label,
                    'detail'            => $mode,
                    'db'                => $claim_detail->term,
                    'account_id'        => Account::where('account','term_cashed')->first()->id
                ]);
            }
        }
        // cheque emis
        if (isset($this->details['cheque_price'])) {
            $claim->cheque->account_details()->create([
                'label'             => 'Encaissement ' . $claim->partner->name,
                'detail'            => $this->details['cheque_nbr'],
                'db'                => $this->details['cheque_price'],
                'account_id'        => Account::where('account','cheque_cashed')->first()->id
            ]);
        }
        // transfer emis
        if (isset($this->details['transfer_price'])) {
            $claim->transfer->account_details()->create([
                'label'             => 'Encaissement ' . $claim->partner->name,
                'detail'            => $this->details['transfer_nbr'],
                'db'                => $this->details['transfer_price'],
                'account_id'        => Account::where('account','transfer_cashed')->first()->id
            ]);
        }
        if (isset($this->details['cash_price'])) {
            $claim->cash->account_details()->create([
                'label'             => 'Encaissement ' . $claim->partner->name,
                'detail'            => '',
                'db'                => $this->details['cash_price'],
                'account_id'        => Account::where('account','Caisse Dépôt')->first()->id
            ]);
        }
    }

    private function addPayments(array $data)
    {
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                foreach ($data['price'] as $trade_id => $price) {
                    $payment->trades()->attach($trade_id);
                }
                if($payment['mode_id'] == 2) {
                    $this->cheque_id = $payment->id;
                    $this->details['cheque_nbr'] = $payment->operation;
                    $this->details['cheque_price'] = $payment->price;
                }
                if($payment['mode_id'] == 3) {
                    $this->transfer_id = $payment->id;
                    $this->details['transfer_nbr'] = $payment->operation;
                    $this->details['transfer_price'] = $payment->price;
                }
                if($payment['mode_id'] == 1) {
                    $this->cash_id = $payment->id;
                    $this->details['cash_price'] = $payment->price;
                }
            }
        }
    }

    private function subTerm(Trade $trade,int $price)
    {
        $term = $trade->payments()->where('mode_id',4)->first();
        $less = $term->price - $price;
        if($less > 0) {
            $term->update([
                'price'     => $less
            ]);
        }
        else{
            $term->delete();
        }
    }

    public function sub(Claim $claim)
    {
        // sub details
        $this->subClaimDetails($claim);
        // delete payments
        $this->subPayments($claim);
    }

    private function subPayments(Claim $claim)
    {
        if($claim->cheque_id) {
            $claim->cheque->delete();
            $claim->update([
                'cheque_id' => null
            ]);
        }
        if($claim->transfer_id) {
            $claim->transfer->delete();
            $claim->update([
                'transfer_id' => null
            ]);
        }
    }

    private function subClaimDetails(Claim $claim)
    {
        foreach ($claim->claim_details as $detail) {
            $term = $detail->trade->payments()->where('mode_id',4)->first();
            if ($term) {
                $term->update([
                    'price'     => $term->price + $detail->term
                ]);
            }
            else{
                $payment = $detail->trade->payments()->create([
                    'price'     => $detail->term,
                    'mode_id'   => 4
                ]);
                $detail->trade->payments()->attach($payment->id);
            }
            // sub account
            foreach ($detail->account_details as $account_detail) {
                $account_detail->delete();
            }
            // cheque
            if ($claim->cheque) {
                $claim->cheque->account_details[0]->delete();
            }
            // transfer
            if ($claim->transfer) {
                $claim->transfer->account_details[0]->delete();
            }
            // cash
            if ($claim->cash) {
                $claim->cash->account_details[0]->delete();
            }
            $detail->delete();
        }
    }
}