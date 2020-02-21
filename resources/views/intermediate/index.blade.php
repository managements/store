@extends('layouts.app')
@section('content')
    <div class="container-fluid px-5">
        <div class="row">
            <div class="col-md-12">
                <div class="float-right">
                    <a href="{{ route('intermediate.create') }}" class="btn btn-cst"> <i class="fas fa-plus"></i> Ajouté</a>
                </div>
            </div>
        </div>
       <b>Liste des Intermédiaire :</b>
            <table class="table">
                <thead>
                <tr>
                    <th>intermediate</th>
                    <th class="actions">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($intermediates as $intermediate)
                    <tr>
                        <td>{{ $intermediate->name }}</td>
                        <td class="actions"><a class="btn btn-success" href="{{ route('intermediate.edit',compact('intermediate')) }}"><i class="fas fa-edit"></i> </a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
@stop