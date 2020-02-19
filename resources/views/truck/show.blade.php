@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6 offset-6 text-right">
                    <a href="{{ route('truck.edit',compact('truck')) }}" class="btn btn-primary">edit</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                Chauffeur : {{ ($driver) ?? "Pas de Chauffeur" }} <br>
                assistant : {{ ($assistant) ?? "Pas de d'assistant" }}<br>
                Type: {{ ($truck->transporter) ? 'Transporteur' : 'Distributeur' }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                liste des charges :
            </div>
        </div>
    </div>
@stop