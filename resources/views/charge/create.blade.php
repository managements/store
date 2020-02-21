@extends('layouts.app')

@section('content')
    <div class="container px-5">
        <form action="{{ route('charge.store') }}" method="POST">
            @csrf
            <div class="col-md-4 px-0" >
                <div class="form-group">
                    <label for="truck">Camion</label>
                    <select name="truck" id="truck" class="form-control">
                        @foreach($trucks as $truck)
                            <option value="{{ $truck->id }}"
                                    {{ (old('truck') === $truck->id) ? 'selected' : '' }}>
                                {{ $truck->registered }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('truck'))
                        <span class="text-danger">{{ $errors->first('truck') }}</span>
                    @endif
                </div>
            </div>
            <table class="table ">
                <thead>
                <tr>
                    <th colspan="3">Saisie Charge :</th>
                </tr>

                </thead>
                <tbody style="background: #7cb3b9;">
                    <tr>
                        <td>
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <select name="details[0][charge]" title="charge" id="charge" class="form-control">
                                        @foreach($charges as $charge)
                                            <option value="{{ $charge->id }}">
                                                {{ $charge->charge }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="details[0][label]" class="btn-spanen col-md-12" placeholder="label" required>
                        </td>
                        <td>
                            <input type="text" name="details[0][price]" class="btn-spanen" placeholder="montant" required>
                        </td>
                    </tr>
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
                                <td><input type="text" name="payments[0][price]" value="{{ old('payments.0.price') }}"
                                           placeholder="montant" class="btn-spanen">
                                    <input type="text" name="payments[0][operation]" value="{{ old('payments.0.price') }}"
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
                                    <input type="text" name="payments[1][price]" value="{{ old('payments.1.price') }}"
                                           placeholder="virement" class="btn-spanen">
                                    <input type="text" name="payments[1][operation]" value="{{ old('payments.1.operation') }}"
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