@extends('layouts.app')
@section('content')
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>{{ __('Procesos > Visitas') }}</h6>
        </div>
    </div>
</div>
<div class="fila">
    <div class="columna columna-1">
        <div class="tab">
            <button type="button" class="tablinks active" onclick="openTab(event,'porc')">Por concretar</button>
            <button type="button" class="tablinks" onclick="openTab(event,'conc')">Concretadas</button>
        </div>
        <!-- Tab content -->
        <div>
            @include('visits/tabs/porc')    
            @include('visits/tabs/conc')  
        </div>
    </div>
</div>
<div class="fila">
    <div class="space2"></div>
    <div class="columna columna-1">
        <center>
        <a href="{{ route('visits.create') }}" class="btn-effie"><i class="fa fa-star"></i>&nbsp;{{ __('Nuevo') }}</a>
        <a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-reply"></i>&nbsp;{{ __('Regresar') }}</a>
        </center>
    </div>
</div>
@endsection