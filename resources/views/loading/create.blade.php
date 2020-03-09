@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="bon-command-fournisseur">
            <form action="{{ route('loading.store') }}" method="POST">
            @csrf
            <!-- nbr -->
                <h3 class="text-center">
                    Bon de Chargement
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
                    <select name="driver" title="Provider" class="btn-spanen" required>
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
                            <div class="col-md-6">
                                <table class="table table-bordered text-center">
                                    <thead>
                                    <tr>
                                        <th colspan="3" class="text-center">{{ $brand }}</th>
                                    </tr>
                                    </thead>
                                    <thead>

                                    <tr>
                                        <th>Size</th>
                                        <th>Quantité</th>
                                        <th>PRIX</th>
                                    </tr>
                                    </thead>
                                    <tbody style="background: #7cb3b9;">
                                    @foreach($sizes as $size => $product)
                                        <tr>
                                            <td>{{ $size }}</td>
                                            <td>
                                                <input title="products" type="number" name="products[{{ $product['product'] }}]"
                                                       class="prices"
                                                       data-price="{{ $product['price'] }}"
                                                       data-target="#price_{{ $product['product'] }}"
                                                       data-tva="{{ $product['tva'] }}">
                                            </td>
                                            <td><input type="number" step="0.01" id="price_{{ $product['product'] }}"
                                                       class="total_ttc" disabled></td>
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
                                        <td><h5>Caisse </h5></td>
                                        <td>
                                            <input type="text" name="payments[0][price]"
                                                   value="@if(old('payments.0.price')){{ old('payments.0.price') }}@elseif(isset($payments[0])){{ $payments[0]['price'] }}@endif"
                                                   placeholder="Caisse" class="btn-spanen">
                                            <input type="hidden" name="payments[0][mode_id]" value="1">
                                            @if($errors->has('payments.0.price'))
                                                <br>
                                                <span class="text-danger">{{ $errors->first('payments.0.price') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h5>Cheque </h5></td>
                                        <td>
                                            <input type="text" name="payments[1][price]"
                                                   value="@if(old('payments.1.price')){{ old('payments.1.price') }}@elseif(isset($payments[1])){{ $payments[1]['price'] }}@endif"
                                                   placeholder="Cheque" class="btn-spanen">
                                            <input type="hidden" name="payments[1][mode_id]" value="2">
                                            @if($errors->has('payments.1.price'))
                                                <br>
                                                <span class="text-danger">{{ $errors->first('payments.1.price') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="text" name="payments[1][operation]"
                                                   value="@if(old('payments.1.operation')){{ old('payments.1.operation') }}@elseif(isset($payments[1])){{ $payments[1]['operation'] }}@endif"
                                                   placeholder="N-Opération" class="btn-spanen">
                                            <input type="hidden" name="payments[1][mode_id]" value="2">
                                            @if($errors->has('payments.1.operation'))
                                                <br>
                                                <span class="text-danger">{{ $errors->first('payments.1.operation') }}</span>
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
        function ttc() {
            var $total_ttc = 0;
            $('.total_ttc').each(function () {
                if ($(this).val().length > 0) {
                    $total_ttc = parseInt($(this).val()) + parseInt($total_ttc)
                }
            })
            $('#ttc').val($total_ttc.toFixed(2) + ' MAD')
        }

        function consign() {
            $('.consign').each(function () {
                $(this).hide();
            });
            $('.qt_consign').each(function () {
                $(this).val('');
            });
            var $provider = $('#provider').val();
            $('#consign_' + $provider).show(950);
            $('#def_' + $provider).show(950);
        }

        function prices() {
            $('.prices').each(function () {
                var $price = $(this).attr('data-price');
                var $tva = $(this).attr('data-tva');
                var $qt = $(this).val()
                var $ht = parseInt($price * $qt)
                var $ttc = ($ht * $tva / 100) + $ht
                var $target = $(this).attr('data-target');
                $($target).val($ttc);
            })
        }
        consign()
        prices()
        ttc()
        var $body = $('body');
        $body.on('change', '#provider', function () {
            consign()
            ttc()
        })

        $body.on('change', ".prices", function () {
            prices()
            ttc()
        })
    })(jQuery)
</script>
@endpush