@extends('layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Creator</th>
                        <th>Fournisseur</th>
                        <th>Price</th>
                        <th colspan="2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($claims[0]))
                        @foreach($claims as $claim)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($claim->created_at)->format('d/m/y') }}</td>
                                <td>{{ $claim->creator->staff->full_name }}</td>
                                <td>{{ $claim->partner->name }}</td>
                                <td>{{ $claim->total }}</td>
                                <td><a href="{{ route('claim.edit',compact('claim','provider')) }}">Edit</a></td>
                                <td>
                                    <form action="{{ route('claim.destroy',compact('claim','provider')) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" value="Supprimé">
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop