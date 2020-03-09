@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="bon-command-fournisseur">
            <form action="{{ route('unloading.store') }}" method="POST">
            @csrf
            <!-- nbr -->
                <h3 class="text-center">
                    Bon de Déchargement
                </h3>
                <!-- provider -->
                <div style="max-width:595px;margin: 0 auto 20px;" class="text-left">
                    <b>Fournisseur :</b>
                    <select name="provider" title="Provider" id="provider" class="btn-spanen" required>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}">
                                {{ $provider->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Driver -->
                <div style="max-width:595px;margin: 0 auto 20px;" class="text-left">
                    <b>Chauffeur :</b>
                    <select name="driver" title="driver" class="btn-spanen" required>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->staff->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- products -->
                <div class="row">
                    <!-- gaz -->
                    @foreach($products as $key => $brands)
                        @foreach($brands as $brand => $sizes)
                            @if($brand == 'gaz')
                                <div class="col-md-6">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th class="text-center">Remplie</th>
                                            <th class="text-center">Vide</th>
                                            <th class="text-center">étrangère</th>
                                            <th class="text-right">PRIX</th>
                                        </tr>
                                        </thead>
                                        <tbody style="background: #7cb3b9;">
                                        @foreach($sizes as $size => $product)
                                            <tr>
                                                <td>{{ $size }}</td>
                                                <td>
                                                    <input title="products" type="number"
                                                           name="products[{{ $product['product'] }}]"
                                                           class="prices"
                                                           data-price="{{ $product['price'] }}"
                                                           data-target="#price_{{ $loop->index }}">
                                                </td>
                                                <td>
                                                    <input title="products" type="number"
                                                           name="products[{{ $brands['consign'][$size]['product'] }}]"
                                                           class="prices"
                                                           data-price="{{ $brands['consign'][$size]['price'] }}"
                                                           data-target="#price_{{ $loop->index }}">
                                                </td>
                                                <td>
                                                    <input title="products" type="number"
                                                           name="products[{{ $brands['foreign'][$size]['product'] }}]"
                                                           class="prices"
                                                           data-price="{{ $brands['foreign'][$size]['price'] }}"
                                                           data-target="#price_{{ $loop->index }}">
                                                </td>
                                                <td><input type="number" step="0.01"
                                                           id="price_{{ $loop->index }}"
                                                           class="total_ht" disabled></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @if($errors->has('gaz'))
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <span class="text-danger">{{ $errors->first('gaz') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
                <!-- payments and validate -->
                <div class="row mt-5">
                    <div class="col-md-6 text-left">
                        <div class="col-md-6 text-left">
                            <div class="text-left">
                                <h5 class="mode_paiement_title">Mode de Paiement </h5>
                                <table>
                                    <tr>
                                        <td><h5>Chéque </h5></td>
                                        <td>
                                            <input type="text" name="payments[0][price]"
                                                   value="{{ old('payments.0.price') }}"
                                                   placeholder="montant" class="btn-spanen">
                                            @if($errors->has('payments.0.price'))
                                                <br><span
                                                        class="text-danger">{{ $errors->first('payments.0.price') }}</span>
                                            @endif
                                            <input type="text" name="payments[0][operation]"
                                                   value="{{ old('payments.0.operation') }}"
                                                   placeholder="numéro de chéque"
                                                   class="btn-spanen">
                                            <input type="hidden" name="payments[0][mode_id]" value="2">
                                            @if($errors->has('payments.0.operation'))
                                                <br><span
                                                        class="text-danger">{{ $errors->first('payments.0.operation') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h5>Caisse </h5></td>
                                        <td>
                                            <input type="text" name="payments[2][price]"
                                                   value="@if(old('payments.2.price')){{ old('payments.2.price') }}@elseif(isset($payments[2])){{ $payments[2]['price'] }}@endif"
                                                   placeholder="Caisse" class="btn-spanen">
                                            <input type="hidden" name="payments[2][mode_id]" value="1">
                                            @if($errors->has('payments.2.price'))
                                                <br>
                                                <span class="text-danger">{{ $errors->first('payments.2.price') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <button class="btn-imprimer"><i class="fas fa-file-download"></i> Validé</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@push('scripts')
<script>
    (function () {
        function prices() {
            $('.prices').each(function () {
                var $price = $(this).attr('data-price');
                var $qt = $(this).val()
                if ($qt.length > 0) {
                    var $ht = parseInt($price * $qt)
                    var $target = $(this).attr('data-target');
                    if ($($target).val().length === 0) {
                        $($target).val($ht);
                    }
                    else {
                        var $val = parseInt($($target).val()) + $ht;
                        $($target).val($val);
                    }
                }
            })
        }

        prices()
        var $body = $('body');
        $body.on('change', ".prices", function () {
            $('.total_ht').each(function () {
                $(this).val('')
            })
            prices()
        })
    })(jQuery)
</script>
@endpush