@extends('layouts.app')

@section('content')
    <div class="container px-5 pt-3">
        <div class="row justify-content-center">
            <div class="col-xs-12 col-md-12 px-5">
                <div class="card">
                    <div class="card-header text-uppercase">Créer Un Utilisateur</div>

                    <div class="card-body">
                        <form method="POST" class="suivantjjs" action="{{ route('staff.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="last_name">Nom </label>
                                        <input type="text" name="last_name" id="last_name"
                                               value="{{ old('last_name') }}"  class="form-control"
                                               required autocomplete="nope">
                                        @if($errors->has('last_name'))
                                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="first_name">Prénom</label>
                                        <input type="text" name="first_name" id="first_name"
                                               value="{{ old('first_name') }}" 
                                               class="form-control" required autocomplete="nope">
                                        @if($errors->has('first_name'))
                                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="mobile">N° DE LA CINE</label>
                                        <input type="text" name="cin" id="cin" value="{{ old('mobile') }}"
                                             class="form-control"  autocomplete="nope" >
                                        @if($errors->has('cin'))
                                            <span class="text-danger">{{ $errors->first('cin') }}</span>
                                        @endif
                                    </div>
                                   
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="name">Nom d'utilisateur</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                             class="form-control"  autocomplete="nope">
                                        @if($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                   
                                </div>
                                <div class="col-md-6 px-5">
                                    <div class="form-group px-5">
                                        <label for="category">Catégorie </label>
                                        <select name="category" id="category" class="form-control" required>
                                            <option value="" style="background:#eee" disabled selected>Fonction</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                        {{ (old('category') == $category->id) ? 'selected' : '' }}>
                                                    {{ $category->category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('category'))
                                            <span class="text-danger">{{ $errors->first('category') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5 account">
                                    <div class="form-group px-5">
                                        <label for="password">Mot de passe </label>
                                        <input type="password" name="password" id="password" 
                                               class="form-control" >
                                        @if($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 px-5 account" id="account">
                                    <div class="form-group px-5">
                                        <label for="mobile">Portable </label>
                                        <input type="tel" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                                class="form-control" autocomplete="nope">
                                        @if($errors->has('mobile'))
                                            <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                        @endif
                                    </div>
                                  
                                </div>
                                <div class="col-md-6 px-5 account">
                                    <div class="form-group px-5">
                                        <label for="password_confirmation">confirmation Mot de passe </label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                              class="form-control">
                                        @if($errors->has('password_confirmation'))
                                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 px-5 ">
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

@push('scripts')
<script>
    (function () {
        function selected() {
            var category = parseInt($('#category').val())
            if (category !== 4) {
                $('.account').show()
            } else {
                $('.account').hide()
            }
        }

        selected()
        $('body').on('change', '#category', function () {
            selected()
        })
    })(jQuery)
</script>
@endpush
