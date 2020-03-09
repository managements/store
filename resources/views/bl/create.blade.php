@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="bon-command-fournisseur">
            <form action="{{ route('bl.store') }}" method="POST">
            @csrf
            <!-- nbr -->
                <h3 class="text-center">
                    BON DE LIVRAISON :
                    <input style="max-width: 201px;" class="btn-spanen" title="nbr" value="{{ old('nbr') }}" name="nbr"
                           type="number"
                           required>
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
                <!-- intermediate -->
                <div style="max-width:595px;margin: 0 auto 20px;" class="text-left">
                    <b>Intermédiaire :</b>
                    <select name="intermediate" title="Intermédiaire" id="intermediate" class="btn-spanen" required>
                        @foreach($intermediates as $intermediate)
                            <option value="{{ $intermediate->id }}"
                                    {{ ((int) old('intermediate') === $intermediate->id ) ? 'selected' :'' }}>
                                {{ $intermediate->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- transporter -->
                <div style="max-width:595px;margin: 0 auto 20px;" class="text-left">
                    <b>Transporteur :</b>
                    <select name="transporter" title="Provider" class="btn-spanen" required>
                        @foreach($transporters as $transporter)
                            <option value="{{ $transporter->id }}">
                                {{ $transporter->registered }}
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
                                        <td><h5>Chéque </h5></td>
                                        <td><input type="text" name="payments[0][price]" value="{{ old('payments.0.price') }}"
                                                   placeholder="montant" class="btn-spanen">
                                            <input type="text" name="payments[0][operation]" value="{{ old('payments.0.operation') }}"
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
                                    <tr>
                                        <td><h5>a terme </h5></td>
                                        <td>
                                            <input type="text" name="payments[2][price]" value="{{ old('payments.2.price') }}"
                                                   placeholder="a terme" class="btn-spanen">
                                            <input type="hidden" name="payments[2][mode_id]" value="4">
                                        </td>
                                    </tr>
                                    @if($errors->has('payments.1.price'))
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-danger">{{ $errors->first('payments.1.price') }}</span>
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