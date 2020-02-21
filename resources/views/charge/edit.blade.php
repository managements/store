@extends('layouts.app')

@section('content')
    <div class="container px-5">
        <div class="row">
            <div class="col-sm-12">
                <form action="{{ route('charge.destroy',compact('charge')) }}" method="POST" class="float-right ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-cst"> <i class="fas fa-trash"></i> DELETE</button>
                </form>
                <a href="{{ route('charge.create') }}" class="btn btn-cst float-right"> <i class="fas fa-plus"></i> Ajouté une nouvelle Charge</a>
            </div>
        </div>
        <form action="{{ route('charge.update',compact('charge')) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="col-md-4 px-0">
                <div class="form-group">
                    <label for="truck">Camion</label>
                    <select name="truck" id="truck" class="form-control">
                        @foreach($trucks as $truck)
                            <option value="{{ $truck->id }}"
                                    {{ ($charge->truck_id === $truck->id) ? 'selected' : '' }}>
                                {{ $truck->registered }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('truck'))
                        <span class="text-danger">{{ $errors->first('truck') }}</span>
                    @endif
                </div>
            </div>
            <table class="table table-bordered text-center table-dynamic-rows table-date-filter mt-0 ">
                <thead>
                <tr>
                    <th colspan="3">Saisie Charge :</th>
                </tr>

                </thead>
                <tbody style="background: #7cb3b9;">
                @foreach($charge->charge_truck_details as $key => $detail)
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
            <br>
            <div class="text-left">
                <div class="col-md-6 text-left">
                    <div class="text-left">
                        <h5 class="mode_paiement_title">Mode de Paiement </h5>
                        <table>
                            <tr>
                                <td><h5>Chéque </h5></td>
                                <td><input type="text" name="payments[0][price]" value="{{ $payments[0]['price'] }}"
                                           placeholder="montant" class="btn-spanen">
                                    <input type="text" name="payments[0][operation]"
                                           value="{{ $payments[0]['operation'] }}"
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
                                    <input type="text" name="payments[1][price]" value="{{ $payments[1]['price'] }}"
                                           placeholder="virement" class="btn-spanen">
                                    <input type="text" name="payments[1][operation]"
                                           value="{{ $payments[1]['operation'] }}"
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
            <button class="btn-imprimer"><i class="fas fa-file-download"></i> Validé</button>
        </form>
    </div>
@endsection