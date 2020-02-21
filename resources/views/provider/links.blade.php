@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="page_links">
            <div class="row">
                <div class="col-md-4">
                    <a class="brclr-green" href="#" > <i class="fas fa-truck-moving"></i> Bon de Commande</a>
                </div>
                <div class="col-md-4">
                    <a class="brclr-blue" href="#" > <i class="fas fa-truck-moving"></i> Bon de Livraison</a>
                </div>
                <div class="col-md-4">
                    <a class="brclr-red" href="{{ route('provider.index') }}" ><i class="fas fa-list-ul"></i>  List des Fournisseurs  </a>
                </div>
                <div class="col-md-4">
                    <a class="brclr-orange" href="#" ><i class="fas fa-plus"></i>  Ajouté un nouveau Fournisseurs  </a>
                </div>
                <div class="col-md-4">
                    <a class="brclr-perble" href="{{ route('intermediate.create') }}" ><i class="fas fa-plus"></i>  Ajouté un intermédiaire  </a>
                </div>
                <div class="col-md-4">
                    <a  class="brclr-bluegrad"  href="{{ route('intermediate.index') }}" ><i class="fas fa-list-ul"></i>  List of Intermediates  </a>
                </div>
            </div>
        </div>
    </div>
@endsection
