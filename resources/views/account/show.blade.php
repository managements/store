@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="page_links">
            <div class="row">
                @foreach($accounts as $account)
                    <div class="col-md-4">
                        <a class="brclr-red" href="{{ route('account.show',compact('account')) }}">{{ $account->account }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
