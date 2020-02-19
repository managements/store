@extends('layouts.app')

@section('content')
    <div class="container px-5 pt-4">
        <div class="row ">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Mettre à jour votre mot de passe</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('psw.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12 px-5">
                                    <div class="form-group">
                                        <label for="old_password">Mot de passe actuel </label>
                                        <input type="password" name="old_password" id="old_password" class="form-control" required>
                                        @if($errors->has('old_password'))
                                            <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 px-5">
                                    <div class="form-group">
                                        <label for="password">Nouveau mot de passe </label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        @if($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 px-5">
                                    <div class="form-group">
                                        <label for="password_confirmation">Retapez le nouveau mot de passe </label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                          class="form-control" required>
                                        @if($errors->has('password_confirmation'))
                                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 px-5">
                                    <input type="submit" name="Créer" id="Create" value="Mettre à jour"
                                           class="btn btn-primary float-right">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
