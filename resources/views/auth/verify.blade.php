@extends('layouts.auth')
@section('content')
<div class="caja-login">
    <h6>{{ __('Revise su bandeja de correo') }}</h6>

    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            <label>{{ __('Hemos enviado un enlace de verificación a su correo electrónico.') }}</label>
        </div>
    @endif
    
    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <label>{{ __('Antes de proceder, revise el enlace que le hemos enviado.') }}</label>
        <br>
        <button type="submit" class="btn-effie-inv">{{ __('No tengo enlace.') }}</button>.
    </form>
</div>
<div class="form-group row mb-0">
    <div class="col-md-8 offset-md-4">
        <p><a class="btn btn-link" href="{{ route('login') }}">
            {{ __('Ir a Inicio de sesión') }}
        </a></p>
    </div>
</div>
@endsection
