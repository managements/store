@extends('layouts.app')

@section('content')
    <div class="container px-5 pt-3">
        <div class="bon-command-fournisseur">
            <form action="{{ route('transaction.store') }}" method="POST">
                @csrf
                <div class="mb-4 text-left">
                    <p>Compte <b class="span_designed" style="display:inline-block">Particulier</b></p>
                    <p>Facture <b class="span_designed" style="display:inline-block">1000</b></p>
                
                <!-- provider -->
                    Fournisseur &nbsp;
                    <select name="provider" title="Provider" id="provider" class="btn-spanen" style="padding: 5px 18px 5px;" required>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}">
                                {{ $provider->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- products -->
                <div class="row">
                    <!-- gaz -->
                    @foreach($products as $brand => $operations)
                        @foreach($operations as $operation => $types)
                            @foreach($types as $type => $sizes)
                                <div class="col-md-6">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th colspan="3" class="text-center" style="background: #53696b;">{{ $operation . ' ' . $type }}</th>
                                        </tr>
                                        </thead>
                                        <thead>
                                        <tr>
                                            <th class="text-center" >Size</th>
                                            <th class="text-center" >Quantité</th>
                                            <th class="text-center" >PRIX</th>
                                        </tr>
                                        </thead>
                                        <tbody style="background: #7cb3b9;">
                                        @foreach($sizes as $size => $product)
                                            <tr>
                                                <td>{{ $size }}</td>
                                                <td>
                                                    <input title="products" class="btn-spanen prices" type="number" name="products[{{ $operation }}][{{ $type }}][{{ $size }}][{{ $product['product'] }}]"
                                                           data-price="{{ $product['price'] }}"
                                                           data-target="#price_{{ $size }}_{{ $brand }}_{{ $operation }}_{{ $product['product'] }}"
                                                           data-tva="{{ $product['tva'] }}">
                                                </td>
                                                <td><input type="number" step="0.01" id="price_{{ $size }}_{{ $brand }}_{{ $operation }}_{{ $product['product'] }}"
                                                           class="total_ttc btn-spanen" disabled></td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                    <br><br>
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
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
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
                                        <td><h5>Caisse </h5></td>
                                        <td>
                                            <input type="text" name="payments[2][price]" value="{{ old('payments.2.price') }}"
                                                   placeholder="Caisse" class="btn-spanen">
                                            <input type="hidden" name="payments[2][mode_id]" value="1">
                                        </td>
                                    </tr>
                                    @if($errors->has('payments.2.operation'))
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-danger">{{ $errors->first('payments.2.operation') }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><h5>a terme </h5></td>
                                        <td>
                                            <input type="text" name="payments[3][price]" value="{{ old('payments.3.price') }}"
                                                   placeholder="a terme" class="btn-spanen">
                                            <input type="hidden" name="payments[3][mode_id]" value="4">
                                        </td>
                                    </tr>
                                    @if($errors->has('payments.3.operation'))
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-danger">{{ $errors->first('payments.3.operation') }}</span>
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
                                    <td style="padding: 10px 0;"><b>TOTAL TTC</b> &nbsp;&nbsp; </td>

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
$(function () {
    // operations
    $('#check1').change(function () {
        if ($('#tablecheckboxs tr .check1').css('display') == 'none'){
            $('#tablecheckboxs tr .check1').fadeIn(0);
        }
        else{
            if (!this.checked) {
             $('#tablecheckboxs tr .check1').fadeOut(0);
            }
        }
    });

    $('#check2').change(function () {
        if ($('#tablecheckboxs tr .check2').css('display') == 'none'){
            $('#tablecheckboxs tr .check2').fadeIn(0);
        }
        else{
            if (!this.checked) {
             $('#tablecheckboxs tr .check2').fadeOut(0);
            }
        }
    });

    $('#check3').change(function () {
        if ($('#tablecheckboxs tr .check3').css('display') == 'none'){
            $('#tablecheckboxs tr .check3').fadeIn(0);
        }
        else{
            if (!this.checked) {
             $('#tablecheckboxs tr .check3').fadeOut(0);
            }
        }
    });

    //sizes 
    $('#check4').change(function () {
        if ($('#tablecheckboxs tr.check4').css('display') == 'none'){
            $('#tablecheckboxs tr.check4').fadeIn(0);
        }
        else{
            if (!this.checked) {
             $('#tablecheckboxs tr.check4').fadeOut(0);
            }
        }
    });
    $('#check5').change(function () {
        if ($('#tablecheckboxs tr.check5').css('display') == 'none'){
            $('#tablecheckboxs tr.check5').fadeIn(0);
        }
        else{
            if (!this.checked) {
             $('#tablecheckboxs tr.check5').fadeOut(0);
            }
        }
    });
    $('#check6').change(function () {
        if ($('#tablecheckboxs tr.check6').css('display') == 'none'){
            $('#tablecheckboxs tr.check6').fadeIn(0);
        }
        else{
            if (!this.checked) {
             $('#tablecheckboxs tr.check6').fadeOut(0);
            }
        }
    });
    $('#check7').change(function () {
        if ($('#tablecheckboxs tr.check7').css('display') == 'none'){
            $('#tablecheckboxs tr.check7').fadeIn(0);
        }
        else{
            if (!this.checked) {
             $('#tablecheckboxs tr.check7').fadeOut(0);
            }
        }
    });
});
</script>
@endpush