@extends('layouts.app')

@section('content')
    <div class="container px-5">
        <div>
            <table id="example" class="table" style="width:100%;">
                <thead>
                <tr>
                    <th class="text-left">Compte</th>
                    <th class="text-left">Client</th>
                    <th>Chiffre d'affaire</th>
                    <th class="actions">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td class="text-left">{{ $client->account }}</td>
                        <td class="text-left">{{ $client->name }}</td>
                        <td>{{ $client->turnover }} MAD</td>
                        <td class="actions">
                            <a href="{{ route('client.show',compact('client')) }}"
                               class="btn btn-m-blue btn-sm"> <i class="fas fa-bars"></i> Historique</a>
                            <!-- if not particular client -->
                            @if($client->account != 1000)
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('client.edit',compact('client')) }}"
                                       class="btn btn-success btn-sm"> <i class="fas fa-edit"></i> </a>
                                @endif
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
