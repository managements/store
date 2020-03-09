@extends('layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <form action="#" id="search_provider">
                @csrf
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="provider">Fournisseurs :</label>
                        <select name="provider" id="provider" class="form-control">
                            @foreach($providers as $provider)
                                <option value="{{ route('claim.create',compact('provider')) }}"
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
                <div class="col-sm-12">
                    <button type="submit">Validé</button>
                </div>
            </form>
        </div>
    </div>
@stop
@push('scripts')
<script>
    (function (e) {
        $('body').on('submit','#search_provider',function (e) {
            e.preventDefault();
            window.location.href = $('#provider').val()
        })

    })(jQuery)
</script>
@endpush