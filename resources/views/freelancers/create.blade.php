@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>{{ __('Entidades > Independientes > Nuevo') }}</h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('freelancers.store') }}" role="form" id="frm-freelancer">
	@csrf
	<div class="fila">
		<div class="columna columna-1">
			<h6 class="title3">{{ __('Datos generales') }}</h6>
			<a id="icn-gen" onclick="showForm('gen')" class="icn-sct"><i class="fa fa-minus fa-icon"></i></a>
		</div>
	</div>
	<div id="div-gen">
		<div class="fila">
			<div class="columna columna-2">
				<label>{{ __('Nombres y apellidos*') }}</label>
				<input type="text" name="name" id="name" maxlength="100" value="{{ old('name') }}" onkeypress="return checkText(event)" required>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Código') }}</label>
				<input type="text" disabled>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Ocupación*') }}</label>
				@inject('profiles','App\Services\Profiles')
				<select name="profile_id" id="profile_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona una ocupación') }}</option>
					@foreach ($profiles->get('I') as $index => $profile)
					<option value="{{ $index }}" {{ old('profile_id') == $index ? 'selected' : '' }}>
						{{ $profile }}
					</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Tipo de documento*') }}</label>
				@inject('documentTypes','App\Services\DocumentTypes')
				<select name="document_type_id" id="document_type_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona un tipo de documento') }}</option>
					@foreach ($documentTypes->get() as $index => $documentType)
					<option value="{{ $index }}" {{ old('document_type_id') == $index ? 'selected' : '' }}>
						{{ $documentType['name'] }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-6">
				<label>{{ __('N° Documento*') }}</label>
				<input type="hidden" name="doc_pattern" id="doc_pattern" value="{{ old('doc_pattern') }}" required>
				<input type="text" name="document" id="document" value="{{ old('document') }}" onkeyup="return mayusculas(this)" disabled required>
			</div>
			<div class="columna columna-6">
				<label>{{ __('F. Nacimiento*') }}</label>
				<input type="date" name="birthdate" id="birthdate" max="{{ Carbon\Carbon::today()->subYear(18)->toDateString() }}" value="{{ old('birthdate') }}" required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Especifica otra ocupación') }}</label>
				<input type="text" name="other" id="other" maxlength="100" value="{{ old('other') }}" onkeypress="return checkText(event)" disabled>
			</div>
		</div>
	</div>
	<div class="fila">
		<div class="space"></div>
		<div class="columna columna-1">
			<h6 class="title3">{{ __('Datos de contacto') }}</h6>
			<a id="icn-ctt" onclick="showForm('ctt')" class="icn-sct"><i class="fa fa-minus fa-icon"></i></a>
		</div>
	</div>
	<div id="div-ctt">
		<div class="fila">
			<div class="columna columna-3c">
				<label>{{ __('Dirección de domicilio*') }}</label>
				<input type="text" name="address" id="address" maxlength="100" value="{{ old('address') }}" onkeypress="return checkText(event)" required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Distrito de domicilio*') }}</label>
				@inject('ubigeos','App\Services\Ubigeos')
				<select name="ubigeo_id" id="ubigeo_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona un distrito') }}</option>
					@foreach ($ubigeos->get() as $index => $ubigeo)
					<option value="{{ $index }}" {{ old('ubigeo_id') == $index ? 'selected' : '' }}>
						{{ $ubigeo }}
					</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-6">
				<label>{{ __('Código país*') }}</label>
				@inject('countries','App\Services\Countries')
				<select name="country_id" id="country_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona un país') }}</option>
					@foreach ($countries->get() as $index => $country)
					<option value="{{ $index }}" {{ old('country_id',164) == $index ? 'selected' : '' }}>
						{{ $country }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Número celular*') }}</label>
				<input type="tel" name="mobile" id="mobile" maxlength="11" value="{{ old('mobile') }}" onkeypress="return checkNumber(event)" pattern="[0-9]{3} [0-9]{3} [0-9]{3}" placeholder="999 999 999" required>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Teléfono fijo') }}</label>
				<input type="tel" name="phone" id="phone" maxlength="11" value="{{ old('phone') }}" onkeypress="return checkNumber(event)" pattern="[0-9]{2} [0-9]{3} [0-9]{4}" placeholder="99 999 9999">
			</div>
			<div class="columna columna-6">
				<label>{{ __('Anexo') }}</label>
				<input type="tel" name="annex" id="annex" maxlength="6" value="{{ old('annex') }}" onkeypress="return checkNumber(event)" pattern="[0-9]{4,6}" placeholder="4 a 6 dígitos">
			</div>
			<div class="columna columna-3">
				<label>{{ __('Correo electrónico') }}</label>
				<input type="email" name="email" id="email" maxlength="50" value="{{ old('email') }}" onkeypress="return checkEmail(event)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
			</div>
		</div>
	</div>
	<div class="fila">
		<div class="space"></div>
		<div class="columna columna-1">
			<h6 class="title3">{{ __('Datos de facturación') }}</h6>
			<a id="icn-pln" onclick="showForm('pln')" class="icn-sct"><i class="fa fa-minus fa-icon"></i></a>
		</div>
	</div>
	<div id="div-pln">
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Entidad bancaria*') }}</label>
				@inject('banks','App\Services\Banks')
				<select name="bank_id" id="bank_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona una entidad bancaria') }}</option>
					@foreach ($banks->get() as $index => $bank)
					<option value="{{ $index }}" {{ old('bank_id') == $index ? 'selected' : '' }}>
						{{ $bank }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-3">
				<label>{{ __('N° Cuenta*') }}</label>
				<input type="text" name="account" id="account" maxlength="20" value="{{ old('account') }}" onkeypress="return checkNumber(event)" disabled required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Código de Cuenta Interbancario (CCI)') }}</label>
				<input type="text" name="cci" id="cci" maxlength="23" value="{{ old('cci') }}" onkeypress="return checkNumber(event)" disabled>
			</div>
		</div>
	</div>
	<div class="fila">
		<div class="space"></div>
		<div class="columna columna-1">
			<center>
			<button type="submit" class="btn-effie"><i class="fa fa-save"></i>&nbsp;{{ __('Registrar') }}</button>
			<a href="{{ route('freelancers.index') }}" class="btn-effie-inv">{{ __('Cancelar') }}</a>
			</center>
		</div>
	</div>
</form>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<p>
			<i class="fa fa-info-circle fa-icon" aria-hidden="true"></i>&nbsp;
			<b>{{ __('Importante') }}</b>
			<ul>
				<li>{{ __('(*) Campos obligatorios.') }}</li>
				<li>{{ __('El tamaño máximo del nombre o razón social y la dirección de facturación es cien (100) caracteres.') }}</li>
				<li>{{ __('El código debe estar compuesto únicamente por tres (3) letras.') }}</li>
				<li>{{ __('El tamaño máximo del correo electrónico es cincuenta (50) caracteres.') }}</li>
			</ul>
		</p>
	</div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('js/suppliers/form3.js') }}"></script>
@endsection