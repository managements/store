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
                    @if(isset($debts[0]))
                        @foreach($debts as $debt)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($debt->created_at)->format('d/m/y') }}</td>
                                <td>{{ $debt->creator->staff->full_name }}</td>
                                <td>{{ $debt->partner->name }}</td>
                                <td>{{ $debt->total }}</td>
                                <td><a href="{{ route('debt.edit',compact('debt','client')) }}">Edit</a></td>
                                <td>
                                    <form action="{{ route('debt.destroy',compact('debt','client')) }}"
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