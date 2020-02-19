@extends('layouts.app')

@section('content')
    <div class="container px-5">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="float-right">
                    <a href="{{ route('staff.create') }}"
                       class="btn btn-success  text-right btn-cst" >
                        <i class="fas fa-user-plus"></i> Ajouter Un Utilisateur</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <b style="text-transform:uppercase"> liste des utilisateurs</b>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nom et Prénom</th>
                        <th>Fonction</th>
                        <th>Telephone</th>
                        <th>N° CINE</th>
                        <th class="text-center" colspan="2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($staffs as $staff)
                        @if(!$staff->trashed())
                            <tr>
                                <td class="text-left" >{{ $staff->full_name }}</td>
                                <td class="text-left">{{ $staff->category->category }}</td>
                                <td class="text-left">{{ $staff->mobile }}</td>
                                <td class="text-left">{{ $staff->cin }}</td>
                            
                                @if($staff->category_id !== 1)
                                @if(!$staff->trashed())
                                        <td style="width: 165px;text-align:center">
                                            <a href="{{ route('staff.edit',compact('staff')) }}"
                                            class="btn btn-success"><i class="fas fa-edit"></i></a>
                                            <form onsubmit="delete_form_conf(event)" id="delete-user-{{ $staff->id }}" action="{{ route('staff.destroy',compact('staff')) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger" > <i class="fas fa-trash"></i>  </button>
                                            </form>
                                        </td>
                                    @endif
                                @else
                                <td colspan="2"></td>
                                @endif
                            </tr>
                        @endif
                    @endforeach

                    </tbody>
                </table>
                <br><br>
                    <b style="text-transform:uppercase">utilisateurs désactivés</b>
                 <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nom et Prénom</th>
                        <th>Category</th>
                        <th>Mobile</th>
                        <th>CIN</th>
                        <th class="text-center" colspan="2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($staffs as $staff)
                        @if($staff->trashed())
                        <tr>
                            <td class="text-left">{{ $staff->full_name }}</td>
                            <td>{{ $staff->category->category }}</td>
                            <td>{{ $staff->mobile }}</td>
                            <td>{{ $staff->cin }}</td>
                        
                            @if($staff->category_id !== 1)
                                @if($staff->trashed())
                                    <td colspan="2" style="text-align:center">
                                        <form onsubmit="restore_form_conf(event)" id="restore-user-{{ $staff->id }}"
                                              action="{{ route('staff.restore',compact('staff')) }}"
                                              method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-info" > <i class="fas fa-undo-alt"></i>  </button>
                                        </form>
                                    </td>
                                @endif
                            @else
                            <td colspan="2"></td>
                            @endif
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

