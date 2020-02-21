@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="bon-command-fournisseur">
            <form action="{{ route('bc.update',compact('bc')) }}" method="POST">
            @csrf
                @method('PUT')
            <!-- nbr -->
                <h3 class="text-center">
                    BON DE COMMANDE NUMERO :
                    <input style="max-width: 201px;" class="btn-spanen" title="nbr" value="{{ (old('nbr')) ?? $bc->nbr }}" name="nbr"
                           type="number"
                           required>
                </h3>
                <!-- provider -->
                <div style="max-width:595px;margin: 0 auto 20px;" class="text-left">
                    <b>Fournisseur :</b>
                    <select name="provider" title="Provider" id="provider" class="btn-spanen" required>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}"
                            {{ ($provider->id === $partner->id) ? 'selected' : '' }}>
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
                                    {{ ($inter->id === $intermediate->id ) ? 'selected' :'' }}>
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
                            <option value="{{ $transporter->id }}"
                            {{ ($truck->id === $transporter->id) ? 'selected' : '' }}>
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
                                                       value="{{ $product['qt'] }}"
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
                    <div class="col-md-6 offset-md-6">
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