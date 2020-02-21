@extends('layouts.app')
@section('content')
    <div class="content container-fluid  px-5">
        <div class="row">
            <div class="col-sm-12">
               <div class="float-right">
                    <a href="{{ route('charge.create') }}" class="btn btn-cst" > <i class="fas fa-plus"></i> Ajouté une nouvelle Charge</a>
               </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>camion</th>
                        <th>prix</th>
                        <th class="actions">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($chargeTrucks as $charge)
                        <tr>
                            <td>{{ $charge->truck->registered }}</td>
                            <td>{{ $charge->payments()->sum('price') }}</td>
                            <td class="actions"><a class="btn btn-success" href="{{ route('charge.edit',compact('charge')) }}"> <i class="fas fa-edit"></i>  </a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@stop