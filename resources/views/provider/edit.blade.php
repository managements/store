@extends('layouts.app')

@section('content')
    <div class="container px-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-xs-12 col-md-12">
                <div class="card">
                    <div class="card-header">Mettre à jour Le Fournisseur</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('provider.update',compact('provider')) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="name">Raison Social</label>
                                        <input type="text" name="name" id="name"
                                               value="{{ (old('name')) ?? $provider->name }}" placeholder="Raison Social"
                                               class="form-control" required>
                                        @if($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="speaker">Nom du Gérant :</label>
                                        <input type="text" name="speaker" id="speaker"
                                               value="{{ (old('speaker')) ?? $provider->speaker }}"
                                               placeholder="Nom :" class="form-control" required>
                                        @if($errors->has('speaker'))
                                            <span class="text-danger">{{ $errors->first('speaker') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="rc">Rc :</label>
                                        <input type="text" name="rc" id="rc" value="{{ (old('rc')) ?? $provider->rc }}" placeholder="Rc :"
                                               class="form-control" required>
                                        @if($errors->has('rc'))
                                            <span class="text-danger">{{ $errors->first('rc') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="patent">N° de patente</label>
                                        <input type="text" name="patent" id="patent" value="{{ (old('patent')) ?? $provider->patent }}"
                                               placeholder="N° de patente" class="form-control" required>
                                        @if($errors->has('patent'))
                                            <span class="text-danger">{{ $errors->first('patent') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="ice">ICE :</label>
                                        <input type="text" name="ice" id="ice" value="{{ (old('ice')) ?? $provider->ice }}"
                                               placeholder="ICE :" class="form-control" required>
                                        @if($errors->has('ice'))
                                            <span class="text-danger">{{ $errors->first('ice') }}</span>
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
