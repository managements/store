@extends('layouts.app')

@section('content')
    <div class="container page-cleint-historique">
        <div class="row">
            <div class="col-md-4">
                <table>
                    <tr class="mb-4">
                        <td style="padding: 10px 0;">Compte Fournisseur</td>
                        <td><span class="span_designed"><b>{{ $provider->account }}</b></span></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;">Raison Sociale</td>
                        <td><span class="span_designed"><b>{{ $provider->name }}</b></span></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;">Gérant</td>
                        <td><span class="span_designed"><b>{{ $provider->speaker }}</b></span></td>
                    </tr>
                    <tr>
                        <td colspan="2"><br><br><br></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;">Chiffre d'affaire</td>
                        <td><span class="span_designed"><b>{{ $provider->turnover }} MAD</b></span></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table table-no-border table-date-filter">
                    <tr>
                        <td>De</td>
                        <td><input type="date"></td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>
                            <input type="date">
                            <button class="btn-search-filterr" type="button"><i class="fas fa-search"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">Solde <span>{!! $sold !!}</span></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <div class="float-right">
                    <table class="table table-bordered" style="max-width: 250px;">
                        <thead>
                        <tr>
                            <th>QT</th>
                            <th>Retenu</th>
                            <th style="min-width: 150px">Prix</th>
                        </tr>
                        </thead>
                        @foreach($retaineds as $key => $retained)
                            @if($key != 'total')
                                <tr>
                                    <td><b>{{ $key }}</b></td>
                                    <td><span class="span_designed">{{ $retained['qt'] }}</span></td>
                                    <td><span class="span_designed">{{ $retained['price'] }} MAD</span></td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2"><b>TOTAL</b></td>
                            <td><span class="span_designed">{{ $retaineds['total'] }} MAD</span></td>
                        </tr>
                    </table>

                </div>

            </div>
        </div>

        <br>
        <table id="example" class="display dataTables_wrapper" style="width:100%">
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
                        <td></td>
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
@endsection

