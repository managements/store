@extends('layouts.app')
@section('content')
    <div class="content container-fluid px-5">
        <div class="row">
            <div class="col-md-12 pb-3">
                <div class="float-right">
                    <a href="{{ route('truck.create') }}" class="btn btn-primary btn-cst"> <i class="fas fa-plus"></i> Créer un nouveau transport</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <b style="text-transform:uppercase"> liste des transporteurs</b>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Chauffeur</th>
                            <th class="actions">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trucks as $truck)
                        <tr>
                            <td>{{ $truck->registered }} </td>
                            <td>{{ $truck->registered }} </td>
                            <td class="actions"> <a class="btn btn-success" href="{{ route('truck.show',compact('truck')) }}"> <i class="fas fa-eye"></i> </a> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              
            </div>
        </div>
    </div>
@stop