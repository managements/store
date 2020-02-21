@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-3">

                <form action="{{ route('intermediate.store') }}" method="POST">
                    @csrf
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="name">Nom de L'intermédiaire :</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-12">
                            <button type="submit" class="btn  float-right btn-cst-min px-4"><i class="fas fa-file-download"></i> Enregistrer </button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
@stop