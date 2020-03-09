@extends('layouts.app')
@section('content')
    <div class="container page-cleint-historique pt-3 px-5">
        <div class="row">
            <div class="col-md-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <select name="Provider" title="provider" id="provider" class="form-control">
                            @foreach($providers as $provide)
                                <option value="{{ route('store.stock',['provider' => $provide]) }}"
                                {{ ($provider->id === $provide->id) ? 'selected' : null }}>
                                    {{ $provide->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-3">
            <div class="col-md-4">
                <h1>Stock {{ $provider->name }}</h1>
            </div>
            <div class="col-md-4">
                <table class="table table-no-border table-date-filter"
                       style="background: transparent;box-shadow: none;">
                    <tr>
                        <td>De</td>
                        <td><input type="date"></td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>
                            <input type="date">
                            <button class="btn-search-filterr" type="button"><i class="fas fa-search"></i></button>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
        <div class="pt-1 px-3">
            <table id="example" class="table display dataTables_wrapper " style="width:100%">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Libellé</th>
                    <th>Détails</th>
                    <th>QT Entrer</th>
                    <th>QT Sortie</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($details[0]))
                    @foreach($details as $detail)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d / m / y') }}</td>
                            <td>{{ $detail->label }}</td>
                            <td>{{ $detail->detail }}</td>
                            <td>{{ $detail->qt_enter}}</td>
                            <td>{{ $detail->qt_out }}</td>
                            <td>{{ $detail->db }}</td>
                            <td>{{ $detail->cr }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="8">Pas de transaction Pour le moment</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@stop
@push('scripts')
<script>
    (function () {
        $('body').on('change', '#provider', function (e) {
            e.preventDefault();
            window.location = $('#provider').val()
        })
    })(jQuery)
</script>
@endpush
