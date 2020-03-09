@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="page_links">
            <div class="row">
                <div class="col-md-4 offset-2">
                    <a class="brclr-green" href="{{ route('loading.create') }}" > <i class="fas fa-truck-moving"></i> Bon de Chargement</a>
                </div>
                <div class="col-md-4">
                    <a class="brclr-blue" href="{{ route('unloading.create') }}" > <i class="fas fa-truck-moving"></i> Bon de déchargement</a>
                </div>
            </div>
        </div>
    </div>
@endsection
