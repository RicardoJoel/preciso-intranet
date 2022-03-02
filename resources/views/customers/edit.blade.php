@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>{{ __('Entidades > Clientes > Editar') }}</h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('customers.update',$customer->id) }}" role="form" id="frm-customer">
	@csrf
	<input type="hidden" name="_method" id="_method" value="PATCH">
	<div class="fila">
		<div class="columna columna-1">
			<h6 class="title3">{{ __('Datos generales') }}</h6>
			<a id="icn-gen" onclick="showForm('gen')" class="icn-sct"><i class="fa fa-minus fa-icon"></i></a>
		</div>
	</div>
	<div id="div-gen">
		<div class="fila">
			<div class="columna columna-2">
				<label>{{ __('Razón social*') }}</label>
				<input type="text" name="name" id="name" maxlength="100" value="{{ old('name',$customer->name) }}" onkeypress="return checkText(event)" required>
			</div>
			<div class="columna columna-4">
				<label>{{ __('Nombre comercial*') }}</label>
				<input type="text" name="alias" id="alias" maxlength="50" value="{{ old('alias',$customer->alias) }}" onkeypress="return checkText(event)" onkeyup="return mayusculas(this)" required>
			</div>
			<div class="columna columna-6">
				<label>{{ __('R. U. C.*') }}</label>
				<input type="text" name="ruc" id="ruc" maxlength="11" value="{{ old('ruc',$customer->ruc) }}" onkeypress="return checkNumber(event)" required>
			</div>
			<div class="columna columna-12">
				<label>{{ __('Código*') }}</label>
				<input type="text" name="code" id="code" maxlength="3" value="{{ old('code',$customer->code) }}" onkeypress="return checkAlpha(event)" onkeyup="return mayusculas(this)" required>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-2">
				<label>{{ __('Dirección de facturación*') }}</label>
				<input type="text" name="address" id="address" maxlength="100" value="{{ old('address',$customer->address) }}" onkeypress="return checkText(event)" required>
			</div>
			<div class="columna columna-4">
				<label>{{ __('Distrito de facturación*') }}</label>
				@inject('ubigeos','App\Services\Ubigeos')
				<select name="ubigeo_id" id="ubigeo_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona un distrito') }}</option>
					@foreach ($ubigeos->get() as $index => $ubigeo)
					<option value="{{ $index }}" {{ old('ubigeo_id',$customer->ubigeo_id) == $index ? 'selected' : '' }}>
						{{ $ubigeo }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-4">
				<label>{{ __('Rubro de negocio*') }}</label>
				@inject('bussiness','App\Services\Bussiness')
				<select name="business_id" id="business_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona un rubro') }}</option>
					@foreach ($bussiness->get() as $index => $business)
					<option value="{{ $index }}" {{ old('business_id',$customer->business_id) == $index ? 'selected' : '' }}>
						{{ $business }}
					</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
</form>
@include('customers/contact')
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<center>
		<button type="submit" class="btn-effie" onclick="document.getElementById('frm-customer').submit();"><i class="fa fa-save"></i>&nbsp;{{ __('Guardar') }}</button>
		<a href="{{ route('customers.index') }}" class="btn-effie-inv">{{ __('Cancelar') }}</a>
		</center>
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<p>
			<i class="fa fa-info-circle fa-icon" aria-hidden="true"></i>&nbsp;
			<b>{{ __('Importante') }}</b>
			<ul>
				<li>{{ __('(*) Campos obligatorios.') }}</li>
				<li>{{ __('El tamaño máximo de la razón social y la dirección de facturación es cien (100) caracteres.') }}</li>
				<li>{{ __('El tamaño máximo del nombre comercial es cincuenta (50) caracteres.') }}</li>
				<li>{{ __('El código debe estar compuesto únicamente por tres (3) letras.') }}</li>
				<li>{{ __('El R. U. C. debe estar compuesto por once (11) caracteres.') }}</li>
				<li>{{ __('El tamaño máximo del nombre completo del contacto es cincuenta (50) caracteres.') }}</li>
				<li>{{ __('El tamaño máximo del cargo y correo electrónico del contacto es cincuenta (50) caracteres.') }}</li>
				<li>{{ __('Para cancelar la edición de datos de un contacto presione el botón "Limpiar" sobre la lista de contactos regitrados.') }}</li>
				<li>{{ __('Para guardar los cambios efectuados en la lista de contactos presione el botón "Guardar" en la zona inferior del formulario.') }}</li>
			</ul>
		</p>
	</div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('js/customers/contact.js') }}"></script>
<script src="{{ asset('js/customers/form.js') }}"></script>
@endsection