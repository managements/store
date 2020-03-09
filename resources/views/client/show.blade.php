@extends('layouts.app')

@section('content')
    <div class="container page-cleint-historique pt-3 px-5">
        <div class="row px-3">
            <div class="col-md-4">
                <table>
                    <tr class="mb-4">
                        <td style="padding: 10px 0;text-align:left">Compte Fournisseur </td>
                        <td><span class="span_designed" style="padding: 6px 50px;"> <b> {{ $client->account }}</b></span></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;text-align:left">Raison Sociale</td>
                        <td><span class="span_designed" style="padding: 6px 50px;"> <b> {{ $client->name }}</b></span></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;text-align:left">Chiffre d'affaire</td>
                        <td><span class="span_designed" style="padding: 6px 50px;"> <b> {{ $client->turnover }} MAD</b></span></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-5">
                <form action="{{ route('remise.store',compact('client')) }}" method="POST">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="provider">Fournisseur</label>
                            <select name="provider" id="provider" class="form-control">
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}" data-content="#{{ $provider->name }}"
                                            {{ (old('provider') === $provider->id) ? 'selected' : '' }}>
                                        {{ $provider->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('provider'))
                                <span class="text-danger">{{ $errors->first('provider') }}</span>
                            @endif
                        </div>
                    </div>
                    @foreach($products as $provider => $type)
                        <div class="col-md-12 tables" id="{{ $provider }}">
                            <table  style="background-color: #53696b">
                                <thead>
                                <tr>
                                    <th style="border: 1px solid white">size</th>
                                    <th style="border: 1px solid white">Gaz</th>
                                    <th style="border: 1px solid white">Consign</th>
                                    <th style="border: 1px solid white">defective</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($type['gaz'] as $size => $product)
                                    <tr>
                                        <td>{{ $size }}</td>
                                        <td>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="price"></label>
                                                    <input type="number" step="0.01" name='remise[{{ $type["gaz"][$size]["remise_id"] }}]'
                                                           value='{{ $type["gaz"][$size]["remise"] }}'
                                                           class="form-control">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="price"></label>
                                                    <input type="number" step="0.01" name='remise[{{ $type['consign'][$size]['remise_id'] }}]'
                                                           value='{{ $type['consign'][$size]['remise'] }}'
                                                           class="form-control">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="price"></label>
                                                    <input type="number" step="0.01" name='remise[{{ $type['defective'][$size]['remise_id'] }}]'
                                                           value='{{ $type['defective'][$size]['remise'] }}'
                                                           class="form-control">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><button type="submit">Modifier</button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
        <div class="pt-1 px-3">
            <table id="example" class="table display dataTables_wrapper " style="width:100%">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Libellé</th>
                    <th>Détails</th>
                    <th>QN Entre</th>
                    <th>QN Sortie</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($details[0]))
                    @foreach($details as $detail)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d / m / y') }}</td>
                            <td>{{ $detail->label }}</td>
                            <td>{{ $detail->detail }}</td>
                            <td>{{ $detail->qt_out }}</td>
                            <td>{{ $detail->qt_enter }}</td>
                            <td>{{ $detail->db }}</td>
                            <td>{{ $detail->cr }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="8">Pas de transaction Pour le moment</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

