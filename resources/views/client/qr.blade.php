@extends('layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <form action="{{ route('qr-code.store') }}" method="POST">
                    @csrf
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="number">Le Nombre des QR</label>
                            <input type="number" name="number" id="number" value="{{ old('number') }}" placeholder="Le Nombre des QR" class="form-control">
                        @if($errors->has('number'))
                            <span class="text-danger">{{ $errors->first('number') }}</span>
                        @endif
                        </div>
                    </div>
                    <button type="submit"> créer et télécharger</button>
                </form>
            </div>
        </div>
    </div>
@stop