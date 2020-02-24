@extends('layouts.app')

@section('content')
    <div class="container page-cleint-historique pt-3 px-5">
        <div class="row px-3">
            <div class="col-md-4">
                <table>
                    <tr class="mb-4">
                        <td style="padding: 10px 0;text-align:left">Compte Fournisseur </td>
                        <td><span class="span_designed" style="padding: 6px 50px;"> <b> {{ $provider->account }}</b></span></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;text-align:left">Raison Sociale</td>
                        <td><span class="span_designed" style="padding: 6px 50px;"> <b> {{ $provider->name }}</b></span></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;text-align:left">Chiffre d'affaire</td>
                        <td><span class="span_designed" style="padding: 6px 50px;"> <b> {{ $provider->turnover }} MAD</b></span></td>
                    </tr>
                </table>
                <table class="table table-no-border table-date-filter" style="background: transparent;box-shadow: none;">
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
                   
                </table>
            </div>
            <div class="col-md-4">
                
            </div>
            <div class="col-md-4">
                <div class="float-right">
                    <table class="table table-bordered" style="max-width: 250px;">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center" style="background: #53696b;"><b>LES RETENUES</b></th>
                            </tr>
                        <tr class="text-center">
                        @foreach($retaineds as $key => $retained)
                            @if($key != 'total')
                                    <th class="text-center"><b>{{ $key }}</b></th>
                            @endif
                        @endforeach
                        </tr>
                        </thead>
                        <tr>
                        @foreach($retaineds as $key => $retained)
                            @if($key != 'total')
                                    <td><span class="span_designed">{{ $retained['qt'] }}</span></td>
                            @endif
                        @endforeach
                    </tr>

                    </table>
                    <div class="text-right mt-5"><br>
                        <span class="font-weight-bold">Solde</span> <span class="span_designed d-inline-block text-center" style="    padding: 6px 0;
                        margin: 0 14px;color: #000;font-size: 17px;font-weight: bold;width: 230px;max-width: 100%;">{!! $sold !!} </span>
                    </div>
                </div>
                
        
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

