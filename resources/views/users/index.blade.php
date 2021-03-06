@extends('layouts.app')
@section('content')
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>{{ __('Entidades > Colaboradores') }}</h6>
        </div>
    </div>
</div>
<div class="fila">
    <div class="columna columna-1">
        <div class="tab">
            <button type="button" class="tablinks active" onclick="openTab(event,'act')">Activos</button>
            <button type="button" class="tablinks" onclick="openTab(event,'ces')">Cesados</button>
        </div>
        <!-- Tab content -->
        <div>
            @include('users/tabs/act')    
            @include('users/tabs/ces')  
        </div>
    </div>
</div>
<div class="fila">
    <div class="space2"></div>
    <center>
    <div class="columna columna-1">
        <a href="{{ route('users.create') }}" class="btn-effie"><i class="fa fa-star"></i>&nbsp;{{ __('Nuevo') }}</a>
        <a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-reply"></i>&nbsp;{{ __('Regresar') }}</a>
    </div>
    </center>
</div>
@endsection