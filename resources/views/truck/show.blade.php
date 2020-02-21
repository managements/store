@extends('layouts.app')
@section('content')
    <div class="container-fluid  px-5">
        <div class="row">
            <div class="col-md-12">
                <div class="float-right ">
                    <a href="{{ route('truck.edit',compact('truck')) }}" class="btn btn-primary btn-cst px-4"><i class="fas fa-edit"></i> Éditer</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                
                <div class="card">
                    <div class="card-header">
                      <a href="{{ route('truck.index') }}"><i class="fas fa-arrow-left"></i></a> &nbsp; Transporteur détails
                    </div>
                   
                    <div class="card-body row">
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>N° d'imatrucle</label>
                                <input type="text"  value="{{ (old('registered')) ?? $truck->registered }}"  class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Chauffeur</label>
                                <input type="text" value="{{ ($driver) ?? "Pas de Chauffeur" }}"  class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Assistant</label>
                                <input type="text" value="{{ ($truck->assurance) ?? "Pas de d'assistant" }}"  class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Date d'expiration Assurance :</label>
                                <input type="text" value="{{ ($truck->visit_technique) ?? "Pas de d'Assurance" }}"  class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Categorie</label>
                                <input type="text" value="{{ ($truck->transporter) ? 'Transporteur' : 'Distributeur' }}"  class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 px-5">
                            <div class="form-group">
                                <label>Viste technique</label>
                                <input type="text" value="Pas de Viste technique"  class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@stop