@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="page_links">
            <div class="row">
                <div class="col-md-4">
                    <a class="brclr-red" href="{{ route('client.index') }}" ><i class="fas fa-list-ul"></i>  Liste des Clients  </a>
                </div>

                <div class="col-md-4">
                    <a  class="brclr-orange"  href="{{ route('qr-code.create') }}" ><i class="fas fa-list-ul"></i>  Ajouté des QR Code  </a>
                </div>
                <div class="col-md-4">
                    <a  class="brclr-orange"  href="{{ route('qr-code.index') }}" ><i class="fas fa-list-ul"></i>  Télécharger les QR Code Non ataché  </a>
                </div>
            </div>
        </div>
    </div>
@endsection
