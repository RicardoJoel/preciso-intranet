@extends('layouts.app')
@section('content')
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>{{ __('Entidades > Independientes') }}</h6>
        </div>
    </div>
</div>
<div class="fila">
    <div class="columna columna-1">
        <table class="tablealumno index">
            <thead>
                <th width="10%">{{ __('Código') }}</th>
                <th width="25%">{{ __('Nombre completo') }}</th>
                <th width="15%">{{ __('Ocupación') }}</th>
                <th width="15%">{{ __('Tipo de documento') }}</th>
                <th width="10%">{{ __('Documento') }}</th>   
                <th width="15%">{{ __('Celular') }}</th>
                <th width="5%">{{ __('Editar') }}</th>
                <th width="5%">{{ __('Borrar') }}</th>
            </thead>
            <tbody>
                @foreach ($freelancers as $freelancer)
                <tr>
                    <td><center>{{ $freelancer->code }}</center></td>
                    <td>{{ $freelancer->name }}</td>
                    <td>{{ $freelancer->profile_id != 49 ? $freelancer->profile->name ?? '' : $freelancer->other }}</td>
                    <td>{{ $freelancer->documentType->name ?? '' }}</td>
                    <td><center>{{ $freelancer->document }}</center></td>
                    <td><center>{{ $freelancer->country->code.' '.$freelancer->mobile }}</center></td>
                    <td><center><a class="btn btn-secondary btn-xs" href="{{ action('FreelancerController@edit', $freelancer->id) }}" ><span class="glyphicon glyphicon-pencil"></span></a></center></td>
                    <td>
                        <center>
                        <form action="{{ action('FreelancerController@destroy', $freelancer->id) }}" method="post">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button class="btn btn-danger btn-xs" type="submit" onclick="return confirm('¿Realmente desea eliminar el independiente seleccionado?')"><span class="glyphicon glyphicon-trash"></span></button>
                        </form>
                        </center>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="fila">
    <div class="space2"></div>
    <center>
    <div class="columna columna-1">
        <a href="{{ route('freelancers.create') }}" class="btn-effie"><i class="fa fa-star"></i>&nbsp;{{ __('Nuevo') }}</a>
        <a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-reply"></i>&nbsp;{{ __('Regresar') }}</a>
    </div>
    </center>
</div>
@endsection