@extends('layouts.app')

@section('content')
    <div class="container px-5">
        <div class="row mb-5">
            @if(auth()->user()->is_admin)
                <div class="col-md-12">
                    <div class="float-right">
                        <a href="{{ route('provider.create') }}" class="btn btn-success text-right btn-cst">
                            <i class="fas fa-plus"></i> &nbsp; Ajouter Une nouveau Fournisseur
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div >
            <table id="example" class="table" style="width:100%;">
                <thead>
                <tr>
                    <th class="text-left">Compte</th>
                    <th class="text-left">Fournisseur</th>
                    <th class="text-left">Gérant</th>
                    <th>Chiffre d'affaire</th>
                    <th class="actions">Action</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($providers[0]))
                    @foreach($providers as $provider)
                        <tr>
                            <td class="text-left">{{ $provider->account }}</td>
                            <td class="text-left">{{ $provider->name }}</td>
                            <td class="text-left">{{ $provider->speaker }}</td>
                            <td>{{ $provider->turnover }} MAD</td>
                            <td  class="actions">
                                <a href="{{ route('provider.show',compact('provider')) }}"
                                   class="btn btn-success btn-sm"> <i class="fas fa-eye"></i> Compte de Fournisseur</a>
                            </td>
                            <td>
                                @if($provider->provider === 1)
                                    <a href="#"
                                       class="btn btn-success btn-sm"> <i class="fas fa-eye"></i> Liste des Paiements
                                        Créances</a>
                                @endif
                            </td>
                            <td>
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('provider.edit',compact('provider')) }}"
                                       class="btn btn-success btn-sm"> <i class="fas fa-edit"></i> Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">Pas de Fournisseur pour Le moment Veuillez Ajouté un
                            nouveau
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
