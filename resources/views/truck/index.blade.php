@extends('layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6 offset-6 text-right">
                    <a href="{{ route('truck.create') }}" class="btn btn-primary">Créer un nouveau transport</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul>
                    @foreach($trucks as $truck)
                        <li>{{ $truck->registered }} <a href="{{ route('truck.show',compact('truck')) }}">Show</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@stop