@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="page_links">
            <div class="row">
                <div class="col-md-4">
                    <a class="brclr-green" href="{{ route('transaction.index') }}" > <i class="fas fa-truck-moving"></i> Liste des Achats Ventes</a>
                </div>
                <div class="col-md-4">
                    <a class="brclr-blue" href="{{ route('transaction.create') }}" > <i class="fas fa-truck-moving"></i> Ajouté Achat Vente</a>
                </div>
            </div>
        </div>
    </div>
@endsection
