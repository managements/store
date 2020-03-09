@extends('layouts.app')

@section('content')
    <div class="container px-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-xs-12 col-md-12">
                <div class="card">
                    <div class="card-header">Mettre à jour Le Client</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('client.update',compact('client')) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="name">Raison Social</label>
                                        <input type="text" name="name" id="name"
                                               value="{{ (old('name')) ?? $client->name }}" placeholder="Raison Social"
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
                                               value="{{ (old('speaker')) ?? $client->speaker }}"
                                               placeholder="Nom :" class="form-control" required>
                                        @if($errors->has('speaker'))
                                            <span class="text-danger">{{ $errors->first('speaker') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="rc">Rc :</label>
                                        <input type="text" name="rc" id="rc" value="{{ (old('rc')) ?? $client->rc }}"
                                               placeholder="Rc :"
                                               class="form-control" required>
                                        @if($errors->has('rc'))
                                            <span class="text-danger">{{ $errors->first('rc') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="patent">N° de patente</label>
                                        <input type="text" name="patent" id="patent"
                                               value="{{ (old('patent')) ?? $client->patent }}"
                                               placeholder="N° de patente" class="form-control" required>
                                        @if($errors->has('patent'))
                                            <span class="text-danger">{{ $errors->first('patent') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="ice">ICE :</label>
                                        <input type="text" name="ice" id="ice"
                                               value="{{ (old('ice')) ?? $client->ice }}"
                                               placeholder="ICE :" class="form-control" required>
                                        @if($errors->has('ice'))
                                            <span class="text-danger">{{ $errors->first('ice') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group">
                                        <label for="address">Adresse :</label>
                                        <input type="text" name="address" id="address"
                                               value="{{ (old('address')) ?? $client->address->address }}"
                                               placeholder="Adresse :" class="form-control" required>
                                        @if($errors->has('address'))
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">Ville</label>
                                        <select name="city" id="city" class="form-control">
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}"
                                                        @if(old('city') === $city->city)
                                                        selected
                                                        @elseif($client->address->city_id === $city->id)
                                                        selected
                                                        @endif
                                                >
                                                    {{ $city->city }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('city'))
                                            <span class="text-danger">{{ $errors->first('city') }}</span>
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
        <div class="row justify-content-center mt-5">
            <div class="col-xs-12 col-md-12">
                <div class="card">
                    <div class="card-header">Mettre à jour Les Remises</div>

                    <div class="card-body">
                        <form action="{{ route('remise.store',compact('client')) }}" method="POST">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="provider">Fournisseur</label>
                                    <select name="provider" id="provider" class="form-control">
                                        @foreach($providers as $provider)
                                            <option value="{{ $provider->id }}" data-content="#{{ $provider->name }}"
                                                    {{ (old('provider') === $provider->id) ? 'selected' : '' }}>
                                                {{ $provider->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('provider'))
                                        <span class="text-danger">{{ $errors->first('provider') }}</span>
                                    @endif
                                </div>
                            </div>
                            @foreach($products as $provider => $type)
                                <div class="col-md-10 offset-md-1 tables" id="{{ $provider }}">
                                    <table  style="background-color: #53696b">
                                        <thead>
                                        <tr>
                                            <th style="border: 1px solid white">size</th>
                                            <th style="border: 1px solid white">Gaz</th>
                                            <th style="border: 1px solid white">Consign</th>
                                            <th style="border: 1px solid white">defective</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($type['gaz'] as $size => $product)
                                            <tr>
                                                <td>{{ $size }}</td>
                                                <td>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="price"></label>
                                                            <input type="number" step="0.01" name='remise[{{ $type["gaz"][$size]["remise_id"] }}]'
                                                                   value='{{ $type["gaz"][$size]["remise"] }}'
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="price"></label>
                                                            <input type="number" step="0.01" name='remise[{{ $type['consign'][$size]['remise_id'] }}]'
                                                                   value='{{ $type['consign'][$size]['remise'] }}'
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="price"></label>
                                                            <input type="number" step="0.01" name='remise[{{ $type['defective'][$size]['remise_id'] }}]'
                                                                   value='{{ $type['defective'][$size]['remise'] }}'
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                            <div class="col-md-12 px-5">
                                <input type="submit" name="Créer" id="Create" value="Mettre à jour"
                                       class="btn btn-primary float-right">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    (function () {
        $('body').on('change', '#provider',function (e) {
            $('.tables').hide()
            $table = $('#provider :selected').attr('data-content');
            alert($table)
            $($table).show()
        })
    })(jQuery)
</script>
@endpush