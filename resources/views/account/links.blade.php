@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="page_links">
            <div class="row">
                @foreach($types as $type)
                    <div class="col-md-4">
                        <a class="brclr-red" href="{{ route('account.type.show',compact('type')) }}">{{ $type->type }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
