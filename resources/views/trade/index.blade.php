@extends('layouts.app')
@section('content')
    <div class="content px-5 pt-3 container-fluid">
        <div class="row">
            <table class="table">
                <thead>
                <tr>
                    <th>date</th>
                    <th>Prix</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-y') }}</td>
                        <td>{{ $transaction->total_price }}</td>
                        <td><a href="{{ route('transaction.edit',compact('transaction')) }}">Edit</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop