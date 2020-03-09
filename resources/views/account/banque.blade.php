@extends('layouts.app')
@section('content')
    <div class="container page-cleint-historique pt-3 px-5">
        <h1>{{ $account->account }}</h1>
        <div class="pt-1 px-3">
            <table id="example" class="table display dataTables_wrapper " style="width:100%">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Libellé</th>
                    <th>Détails</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($account->details[0]))
                    @foreach($account->details as $detail)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d / m / y') }}</td>
                            <td>{{ $detail->label }}</td>
                            <td>{{ $detail->detail }}</td>
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
@stop