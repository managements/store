@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="page_links">
            <div class="row">
                <div class="col-md-4 offset-2">
                    <a class="brclr-green" href="#" > <i class="fas fa-truck-moving"></i> Bon de Commande</a>
                </div>
                <div class="col-md-4">
                    <a class="brclr-blue" href="#" > <i class="fas fa-truck-moving"></i> Bon de Livraison</a>
                </div>
            </div>
            <div class="row">
                    <div class="col-md-4 offset-2">
                        <a class="brclr-red" href="{{ route('provider.index') }}" ><i class="fas fa-list-ul"></i>  Liste des Fournisseurs  </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a  class="brclr-orange"  href="{{ route('intermediate.index') }}" ><i class="fas fa-list-ul"></i>  Liste des Intermédiaires  </a>
                    </div>
            </div>
            </div>
        </div>
    </div>
@endsection
