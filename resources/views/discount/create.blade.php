@extends('layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="row">
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
                    <div class="col-md-12 tables" id="{{ $provider }}">
                        <table  style="background-color: #53696b">
                            <thead>
                            <tr>
                                <th style="border-top: 1px solid white">size</th>
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
                                                <input type="number" name='remise[{{ $type["gaz"][$size]["remise_id"] }}]'
                                                       value='{{ $type["gaz"][$size]["remise"] }}'
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="price"></label>
                                                <input type="number" name='remise[{{ $type['consign'][$size]['remise_id'] }}]'
                                                       value='{{ $type['consign'][$size]['remise'] }}'
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="price"></label>
                                                <input type="number" name='remise[{{ $type['defective'][$size]['remise_id'] }}]'
                                                       value='{{ $type['defective'][$size]['remise'] }}'
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </td>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
                <div class="col-sm-12 mt-5 text-right">
                    <button type="submit">Modifier</button>
                </div>
            </form>
        </div>
    </div>
@stop
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