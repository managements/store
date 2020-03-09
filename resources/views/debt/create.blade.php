@extends('layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h2>{{ $client->name }}</h2>
            </div>
            <div class="col-sm-12">
                <form action="{{ route('debt.store',compact('client')) }}" method="POST">
                    @csrf
                    <table>
                        <thead>
                        <tr>
                            <th>Facture N°</th>
                            <th>A terme</th>
                            <th>Prix</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trades as $key => $trade)
                            @if($trade->inv)
                                @if($trade->term > 0)
                                    <tr>
                                        <td>#FAC-{{ $trade->inv }}</td>
                                        <td>{{ $trade->term }}</td>
                                        <td>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input type="number" name="price[{{$trade->id}}]"
                                                           id=price[{{$trade->id}}]"
                                                           value="{{ old('price.'. $trade->id) }}"
                                                           max="{{ $trade->term }}"
                                                           placeholder="Prix :" class="form-control">
                                                    @if($errors->has('price.'. $trade->id))
                                                        <span class="text-danger">{{ $errors->first('price.'. $trade->id) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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
                                                       value="{{ old('payments.0.price') }}"
                                                       placeholder="montant" class="btn-spanen">
                                                <input type="text" name="payments[0][operation]"
                                                       value="{{ old('payments.0.operation') }}"
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
                                                       value="{{ old('payments.1.price') }}"
                                                       placeholder="virement" class="btn-spanen">
                                                <input type="text" name="payments[1][operation]"
                                                       value="{{ old('payments.1.operation') }}"
                                                       placeholder="virement"
                                                       class="btn-spanen">
                                                <input type="hidden" name="payments[1][mode_id]" value="3">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><h5>cash </h5></td>
                                            <td>
                                                <input type="text" name="payments[2][price]"
                                                       value="{{ old('payments.2.price') }}"
                                                       placeholder="cash" class="btn-spanen">
                                                <input type="hidden" name="payments[2][mode_id]" value="1">
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