@extends('layouts.app')

@section('content')
    <div class="container px-5">
        <div class="row">
            <div class="col-sm-12">
                <form action="{{ route('charge_store.destroy',compact('charge_store')) }}" method="POST" class="float-right ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-cst"> <i class="fas fa-trash"></i> DELETE</button>
                </form>
            </div>
        </div>
        <form action="{{ route('charge_store.update',compact('charge_store')) }}" method="POST">
            @csrf
            @method('PUT')
            <table class="table table-bordered text-center table-dynamic-rows table-date-filter mt-0 ">
                <thead>
                <tr>
                    <th colspan="3">Saisie Charge :</th>
                </tr>

                </thead>
                <tbody style="background: #7cb3b9;">
                @foreach($charge_store->charge_store_details as $key => $detail)
                    <tr>
                        <td>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="details[{{ $key }}][charge]" title="charge" id="charge" class="form-control">
                                        @foreach($charges as $charge)
                                            <option value="{{ $charge->id }}"
                                                    {{ ($detail->charge_id === $charge->id) ? 'selected' : '' }}>
                                                {{ $charge->charge }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="details[{{ $key }}][label]" class="btn-spanen col-md-12"
                                   value="{{ $detail->label }}"
                                   placeholder="label"
                                   required>
                        </td>
                        <td>
                            <input type="text" name="details[{{ $key }}][price]" class="btn-spanen" value="{{ $detail->price }}"
                                   placeholder="montant"
                                   required>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!-- payments and validate -->
            <div class="row mt-5">
                <div class="col-md-6 text-left">
                    <div class="col-md-6 text-left">
                        <div class="col-md-6 text-left">
                            <div class="text-left">
                                <h5 class="mode_paiement_title">Mode de Paiement </h5>
                                <table>
                                    <tr>
                                        <td><h5>Chéque </h5></td>
                                        <td><input type="text" name="payments[1][price]"
                                                   value="@if(old('payments.1.price')){{ old('payments.1.price') }}@elseif(isset($payments[1])){{ $payments[1]['price'] }}@endif"
                                                   placeholder="montant" class="btn-spanen">
                                            <input type="text" name="payments[1][operation]"
                                                   value="@if(old('payments.1.operation')){{ old('payments.1.operation') }}@elseif(isset($payments[1])){{ $payments[1]['operation'] }}@endif"
                                                   placeholder="numéro de chéque"
                                                   class="btn-spanen">
                                            <input type="hidden" name="payments[1][mode_id]" value="2">
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
                                    <tr>
                                        <td><h5>Virement </h5></td>
                                        <td>
                                            <input type="text" name="payments[2][price]"
                                                   value="@if(old('payments.2.price')){{ old('payments.2.price') }}@elseif(isset($payments[2])){{ $payments[2]['price'] }}@endif"
                                                   placeholder="virement" class="btn-spanen">
                                            <input type="text" name="payments[2][operation]"
                                                   value="@if(old('payments.2.operation')){{ old('payments.2.operation') }}@elseif(isset($payments[2])){{ $payments[2]['operation'] }}@endif"
                                                   placeholder="virement"
                                                   class="btn-spanen">
                                            <input type="hidden" name="payments[2][mode_id]" value="3">
                                        </td>
                                    </tr>
                                    @if($errors->has('payments.2.price'))
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-danger">{{ $errors->first('payments.2.price') }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($errors->has('payments.2.operation'))
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-danger">{{ $errors->first('payments.2.operation') }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><h5>Caisse </h5></td>
                                        <td>
                                            <input type="text" name="payments[0][price]"
                                                   value="@if(old('payments.0.price')){{ old('payments.0.price') }}@elseif(isset($payments[0])){{ $payments[0]['price'] }}@endif"
                                                   placeholder="Caisse" class="btn-spanen">
                                            <input type="hidden" name="payments[0][mode_id]" value="1">
                                        </td>
                                    </tr>
                                    @if($errors->has('payments.0.price'))
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-danger">{{ $errors->first('payments.0.price') }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><h5>a term </h5></td>
                                        <td>
                                            <input type="text" name="payments[3][price]"
                                                   value="@if(old('payments.3.price')){{ old('payments.3.price') }}@elseif(isset($payments[3])){{ $payments[3]['price'] }}@endif"
                                                   placeholder="a term" class="btn-spanen">
                                            <input type="hidden" name="payments[3][mode_id]" value="4">
                                        </td>
                                    </tr>
                                    @if($errors->has('payments.3.price'))
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-danger">{{ $errors->first('payments.3.price') }}</span>
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
@endsection