@extends('layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h2>{{ $provider->name }}</h2>
            </div>
            <div class="col-sm-12">
                <form action="{{ route('claim.update',compact('provider','claim')) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <table>
                        <thead>
                        <tr>
                            <th>N° BL</th>
                            <th>A terme</th>
                            <th>N° facture</th>
                            <th>Prix</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trades as $key => $trade)
                            @if($trade->bl)
                                <tr>
                                    <td>{{ $trade->bl->nbr }}</td>
                                    <td>{{ $trade->term_total }}</td>
                                    <td>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="number" name="invoice[{{ $trade->id }}]"
                                                       id="invoice[{{ $trade->id }}]"
                                                       value="{{ $trade->inv }}"
                                                       placeholder="Facture :" class="form-control">
                                                @if($errors->has('invoice.' . $trade->id))
                                                    <span class="text-danger">{{ $errors->first('invoice.' . $trade->id) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="number" name="price[{{$trade->id}}]"
                                                       id=price[{{$trade->id}}]"
                                                       value="{{ $trade->term_value }}"
                                                       max="{{ $trade->term_total }}"
                                                       placeholder="Facture :" class="form-control">
                                                @if($errors->has('price.'. $trade->id))
                                                    <span class="text-danger">{{ $errors->first('price.'. $trade->id) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <!-- payments and validate -->
                    <div class="row mt-5">
                        <div class="col-md-6 text-left">
                            <div class="col-md-6 text-left">
                                <div class="text-left">
                                    <h5 class="mode_paiement_title">Mode de Paiement </h5>
                                    <table>
                                        <tr>
                                            <td><h5>Chéque </h5></td>
                                            <td><input type="text" name="payments[0][price]"
                                                       value="@if(old('payments.0.price')){{ old('payments.0.price') }}@elseif($claim->cheque){{ $claim->cheque->price }}@endif"
                                                       placeholder="montant" class="btn-spanen">
                                                <input type="text" name="payments[0][operation]"
                                                       value="@if(old('payments.0.operation')){{ old('payments.0.operation') }}@elseif($claim->cheque){{ $claim->cheque->operation }}@endif"
                                                       placeholder="numéro de chéque"
                                                       class="btn-spanen">
                                                <input type="hidden" name="payments[0][mode_id]" value="2">
                                            </td>
                                        </tr>
                                        @if($errors->has('payments.0.price'))
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-danger">{{ $errors->first('payments.0.price') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($errors->has('payments.0.operation'))
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-danger">{{ $errors->first('payments.0.operation') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td><h5>Virement </h5></td>
                                            <td>
                                                <input type="text" name="payments[1][price]"
                                                       value="@if(old('payments.1.price')){{ old('payments.1.price') }}@elseif($claim->transfer){{ $claim->transfer->price }}@endif"
                                                       placeholder="virement" class="btn-spanen">
                                                <input type="text" name="payments[1][operation]"
                                                       value="@if(old('payments.1.operation')){{ old('payments.1.operation') }}@elseif($claim->transfer){{ $claim->transfer->operation }}@endif"
                                                       placeholder="virement"
                                                       class="btn-spanen">
                                                <input type="hidden" name="payments[1][mode_id]" value="3">
                                            </td>
                                        </tr>
                                        @if($errors->has('payments.1.price'))
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-danger">{{ $errors->first('payments.1.price') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($errors->has('payments.1.operation'))
                                            <tr>
                                                <td colspan="2">
                                                    <span class="text-danger">{{ $errors->first('payments.1.operation') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            @if($errors->has('payment'))
                                <div class="row">
                                    <div class="col-xs-12">
                                        <span class="text-danger">{{ $errors->first('payment') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div>
                                <table class="table-no-border" style="margin:0 0 0 auto;">
                                    <tr>
                                        <td style="padding: 10px 0;"><b>TOTAL TTC</b></td>

                                        <td><input type="text" name="ttc" id="ttc"
                                                   placeholder="TTC" value="{{ ' 0 MAD' }}"
                                                   class="btn-spanen" disabled></td>
                                    </tr>
                                </table>

                                <button class="btn-imprimer"><i class="fas fa-file-download"></i> Validé</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop