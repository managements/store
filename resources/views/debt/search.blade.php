@extends('layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <form action="#" id="search_provider">
                @csrf
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="client">Fournisseurs :</label>
                            <select name="client" id="client" class="form-control">
                                @foreach($clients as $client)
                                    <option value="{{ route('debt.create',compact('client')) }}"
                                            {{ (old('client') === $client->id) ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('client'))
                                <span class="text-danger">{{ $errors->first('client') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 mt-4">
                        <button type="submit">Validé</button>
                    </div>
                </div>

                <div class="col-sm-12">
                    <form action="#" method="POST">
                        @csrf
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
            </form>
        </div>
    </div>
@stop
@push('scripts')
<script>
    (function (e) {
        $('body').on('submit','#search_provider',function (e) {
            e.preventDefault();
            window.location.href = $('#client').val()
        })

    })(jQuery)
</script>
@endpush