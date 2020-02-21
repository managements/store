@extends('layouts.app')

@section('content')
    <div class="container px-5">
        <div class="row justify-content-center">
            <div class="col-xs-12 col-md-12 px-5 pt-4">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('staff.index') }}"><i class="fas fa-arrow-left"></i></a> &nbsp;&nbsp;  Mettre à jour les Informations d'utilisateur
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.update',compact('staff')) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="last_name">Nom </label>
                                        <input type="text" name="last_name" id="last_name"
                                               value="{{ (old('last_name')) ?? $staff->last_name }}" class="form-control"
                                               required>
                                        @if($errors->has('last_name'))
                                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="first_name">Prénom</label>
                                        <input type="text" name="first_name" id="first_name"
                                               value="{{ (old('first_name')) ?? $staff->first_name }}" 
                                               class="form-control"  required>
                                        @if($errors->has('first_name'))
                                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="mobile">Portable</label>
                                        <input type="tel" name="mobile" id="mobile"
                                               value="{{ (old('mobile')) ?? $staff->mobile }}" class="form-control"  required>
                                            @if($errors->has('mobile'))
                                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="mobile">N° de la Carte CIN</label>
                                        <input type="text" name="cin" id="cin"
                                               value="{{ (old('cin')) ?? $staff->cin }}"
                                               class="form-control">
                                            @if($errors->has('cin'))
                                                <span class="text-danger">{{ $errors->first('cin') }}</span>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-md-12 px-5">
                                    <div class="px-5">
                                        <button type="submit" class="btn  float-right btn-cst-min px-4"><i class="fas fa-file-download"></i> Enregistrer </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
