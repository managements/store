@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page_links fullwid">
            <div class="row">
                <div class="col-md-4">
                    <a href="#" class="brclr-green" > <i class="fas fa-file-medical-alt"></i> Tableau des prix  </a>
                </div>
                <div class="col-md-4">
                    <a class="brclr-orange" href="{{ route('charge.create') }}" > <i class="fas fa-file-medical-alt"></i> Charge Transport</a>
                </div>
            </div>
        </div>
    </div>
@endsection
