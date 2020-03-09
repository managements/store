@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="page_links">
            <div class="row">
                    <div class="col-md-4">
                        <a class="brclr-red" href="{{ route('store.stock') }}">
                            <i class="fas fa-list-ul"></i> Stock Dépôt
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="brclr-red" href="{{ route('store.caisse') }}">
                            <i class="fas fa-list-ul"></i> Caisse Dépôt
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="brclr-red" href="{{ route('store.charge') }}">
                            <i class="fas fa-list-ul"></i> Charge Dépôt
                        </a>
                    </div>
            </div>
        </div>
    </div>
@endsection
