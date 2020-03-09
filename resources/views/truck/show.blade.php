@extends('layouts.app')
@section('content')
    <div class="container-fluid px-5">
        <div class="row">
            <div class="col-md-12">
                <div class="float-right ">
                    <a href="{{ route('truck.edit',compact('truck')) }}" class="btn btn-primary btn-cst px-4"><i
                                class="fas fa-edit"></i> Éditer</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('truck.index') }}"><i class="fas fa-arrow-left"></i> Transporteur détails</a>
                    </div>

                    <div class="card-body row">
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>N° d'imatrucle</label>
                                <input type="text" value="{{ $truck->registered }}" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Chauffeur</label>
                                <input type="text" value="{{ ($truck->driver) ?? "Pas de Chauffeur" }}"
                                       class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Assistant</label>
                                <input type="text" value="{{ ($truck->assistant) ?? "Pas de d'assistant" }}"
                                       class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Date d'éxpiration Assurance :</label>
                                <input type="text" value="{{ ($truck->visit_technique) ?? "Pas de d'Assurance" }}"
                                       class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Catégorie</label>
                                <input type="text" value="{{ ($truck->transporter) ? 'Transporteur' : 'Distributeur' }}"
                                       class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Visite technique</label>
                                <input type="text" value="Pas de Visite technique" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="pt-1 px-3">
                <table id="example" class="table display dataTables_wrapper " style="width:100%">
                    <thead>
                    <tr>
                        <th colspan="6" class="text-center">Caisse</th>
                    </tr>
                    </thead>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Libellé</th>
                        <th>Détails</th>
                        <th>QT</th>
                        <th>Débit</th>
                        <th>Crédit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($account_caisse->details[0]))
                        @foreach($account_caisse->details as $detail)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d / m / y') }}</td>
                                <td>{{ $detail->label }}</td>
                                <td>{{ $detail->detail }}</td>
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
        <div class="col-sm-6">
            <div class="pt-1 px-3">
                <table id="example" class="table display dataTables_wrapper " style="width:100%">
                    <thead>
                    <tr>
                        <th colspan="6" class="text-center">Charges</th>
                    </tr>
                    </thead>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Libellé</th>
                        <th>Détails</th>
                        <th>QT</th>
                        <th>Débit</th>
                        <th>Crédit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($account_charge->details[0]))
                        @foreach($account_charge->details as $detail)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d / m / y') }}</td>
                                <td>{{ $detail->label }}</td>
                                <td>{{ $detail->detail }}</td>
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
        <div class="col-sm-6">
            <div class="pt-1 px-3">
                <table id="example" class="table display dataTables_wrapper " style="width:100%">
                    <thead>
                    <tr>
                        <th colspan="6" class="text-center">Compte</th>
                    </tr>
                    </thead>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Libellé</th>
                        <th>Détails</th>
                        <th>QT</th>
                        <th>Débit</th>
                        <th>Crédit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($truck->account_stock->details[0]))
                        @foreach($truck->account_stock->details as $detail)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d / m / y') }}</td>
                                <td>{{ $detail->label }}</td>
                                <td>{{ $detail->detail }}</td>
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
    </div>
@stop