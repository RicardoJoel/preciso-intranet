@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>{{ __('Mi cuenta > Mis datos') }}</h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('updateAccount') }}" role="form" id="frm-profile">
	@csrf
	<div class="fila">
		<div class="columna columna-1">
			<h6 class="title3">{{ __('Datos generales') }}</h6>
			<a id="icn-gen" onclick="showForm('gen')" class="icn-sct"><i class="fa fa-minus fa-icon"></i></a>
		</div>
	</div>
	<div id="div-gen">
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Nombres*') }}</label>
				<input type="text" name="name" id="name" maxlength="50" value="{{ old('name',Auth::user()->name) }}" onkeypress="return checkName(event)" required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Apellidos*') }}</label>
				<input type="text" name="lastname" id="lastname" maxlength="50" value="{{ old('lastname',Auth::user()->lastname) }}" onkeypress="return checkName(event)" required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Correo electrónico Preciso') }}</label>
				<input type="text" value="{{ Auth::user()->email }}" disabled>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Tipo de documento') }}</label>
				<input type="text" value="{{ Auth::user()->documentType->name ?? '' }}" disabled>
			</div>
			<div class="columna columna-6">
				<label>{{ __('N° Documento') }}</label>
				<input type="text" value="{{ Auth::user()->document }}" disabled>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Género*') }}</label>
				@inject('genders','App\Services\Genders')
				<select name="gender_id" id="gender_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona') }}</option>
					@foreach ($genders->get() as $index => $gender)
					<option value="{{ $index }}" {{ old('gender_id',Auth::user()->gender_id) == $index ? 'selected' : '' }}>
						{{ $gender }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Fecha de nacimiento') }}</label>
				<input type="text" value="{{ Carbon\Carbon::parse(Auth::user()->birthdate)->format('d/m/Y') }}" disabled>
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
				<input type="text" name="address" id="address" maxlength="100" value="{{ old('address',Auth::user()->address) }}" onkeypress="return checkText(event)" required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Distrito*') }}</label>
				@inject('ubigeos','App\Services\Ubigeos')
				<select name="ubigeo_id" id="ubigeo_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona un distrito') }}</option>
					@foreach ($ubigeos->get() as $index => $ubigeo)
					<option value="{{ $index }}" {{ old('ubigeo_id',Auth::user()->ubigeo_id) == $index ? 'selected' : '' }}>
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
					<option value="{{ $index }}" {{ old('country_id',Auth::user()->country_id ?? 164) == $index ? 'selected' : '' }}>
						{{ $country }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Número celular*') }}</label>
				<input type="tel" name="mobile" id="mobile" maxlength="17" value="{{ old('mobile',Auth::user()->mobile) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{3} [0-9]{3} [0-9]{3}" placeholder="999 999 999" required>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Teléfono fijo') }}</label>
				<input type="tel" name="phone" id="phone" maxlength="11" value="{{ old('phone',Auth::user()->phone) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{2} [0-9]{3} [0-9]{4}" placeholder="99 999 9999">
			</div>
			<div class="columna columna-6">
				<label>{{ __('Anexo') }}</label>
				<input type="tel" name="annex" id="annex" maxlength="6" value="{{ old('annex',Auth::user()->annex) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{4,6}" placeholder="4 a 6 dígitos">
			</div>
			<div class="columna columna-3">
				<label>{{ __('Correo electrónico personal') }}</label>
				<input type="email" name="alt_email" id="alt_email" maxlength="50" value="{{ old('alt_email',Auth::user()->alt_email) }}" onkeypress="return checkEmail(event)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
			</div>
		</div>
	</div>
	<div class="fila">
		<div class="space"></div>
		<div class="columna columna-1">
			<h6 class="title3">{{ __('Datos laborales') }}</h6>
			<a id="icn-lab" onclick="showForm('lab')" class="icn-sct"><i class="fa fa-minus fa-icon"></i></a>
		</div>
	</div>
	<div id="div-lab">
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Vínculo laboral') }}</label>
				<input type="text" value="{{ Auth::user()->relationship->name ?? '' }}" disabled>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Cargo') }}</label>
				<input type="text" value="{{ Auth::user()->profile->name ?? '' }}" disabled>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Sueldo actual (S/)') }}</label>
				<input type="text" value="{{ number_format(Auth::user()->cur_salary) }}" disabled>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Comisión (%)') }}</label>
				<input type="text" value="{{ number_format(Auth::user()->commission) }}" disabled>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Frec. Variación salarial') }}</label>
				<input type="text" value="{{ Auth::user()->frequency->name ?? '' }}" disabled>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Fecha de inicio') }}</label>
				<input type="text" value="{{ Carbon\Carbon::parse(Auth::user()->start_at)->format('d/m/Y') }}" disabled>
			</div>
		</div>
	</div>
	<div class="fila">
		<div class="space"></div>
		<div class="columna columna-1">
			<h6 class="title3">{{ __('Datos de planilla') }}</h6>
			<a id="icn-pln" onclick="showForm('pln')" class="icn-sct"><i class="fa fa-minus fa-icon"></i></a>
		</div>
	</div>
	<div id="div-pln">
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Entidad bancaria Sueldo') }}</label>
				<input type="text" value="{{ Auth::user()->bank->name ?? '' }}" disabled>
			</div>
			<div class="columna columna-3">
				<label>{{ __('N° Cuenta Sueldo') }}</label>
				<input type="text" value="{{ Auth::user()->bank_account }}" disabled>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Código de Cuenta Interbancario (CCI)') }}</label>
				<input type="text" value="{{ Auth::user()->cci }}" disabled>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Adm. Fondo de pensiones (AFP)') }}</label>
				<input type="text" value="{{ Auth::user()->afp->name ?? '' }}" disabled>

			</div>
			<div class="columna columna-3">
				<label>{{ __('Tipo de comisión') }}</label>
				<input type="text" value="{{ Auth::user()->afpCommission->name ?? '' }}" disabled>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Código CUSPP') }}</label>
				<input type="text" value="{{ Auth::user()->cuspp }}" disabled>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Entidad bancaria CTS') }}</label>
				<input type="text" value="{{ Auth::user()->cts->name ?? '' }}" disabled>
			</div>
			<div class="columna columna-3">
				<label>{{ __('N° Cuenta CTS') }}</label>
				<input type="text" value="{{ Auth::user()->cts_account }}" disabled>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Plan EPS') }}</label>
				<input type="text" value="{{ Auth::user()->eps->name ?? '' }}" disabled>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Código Essalud') }}</label>
				<input type="text" value="{{ Auth::user()->essalud }}" disabled>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Código autogenerado') }}</label>
				<input type="text" value="{{ Auth::user()->code }}" disabled>
			</div>
		</div>
	</div>
	<div class="fila">
		<div class="space"></div>
		<div class="columna columna-1">
			<h6 class="title3">{{ __('Contacto en caso de emergencia') }}</h6>
			<a id="icn-mrg" onclick="showForm('mrg')" class="icn-sct"><i class="fa fa-plus fa-icon"></i></a>
		</div>
	</div>
	<div id="div-mrg" style="display:none">
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Nombre completo') }}</label>
				<input type="text" name="contact_fullname" id="contact_fullname" maxlength="50" value="{{ old('contact_fullname',Auth::user()->contact_fullname) }}" onkeypress="return checkName(event)">
			</div>
			<div class="columna columna-3">
				<label>{{ __('Parentesco') }}</label>
				<input type="text" name="contact_relationship" id="contact_relationship" maxlength="50" value="{{ old('contact_relationship',Auth::user()->contact_relationship) }}" onkeypress="return checkName(event)">
			</div>
			<div class="columna columna-3">
				<label>{{ __('Dirección laboral o de domicilio') }}</label>
				<input type="text" name="contact_address" id="contact_address" maxlength="100" value="{{ old('contact_address',Auth::user()->contact_address) }}" onkeypress="return checkText(event)">
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Distrito') }}</label>
				@inject('ubigeos','App\Services\Ubigeos')
				<select name="contact_ubigeo_id" id="contact_ubigeo_id">
					<option selected disabled hidden value="">{{ __('Selecciona un distrito') }}</option>
					@foreach ($ubigeos->get() as $index => $ubigeo)
					<option value="{{ $index }}" {{ old('contact_ubigeo_id',Auth::user()->contact_ubigeo_id) == $index ? 'selected' : '' }}>
						{{ $ubigeo }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Código país') }}</label>
				@inject('countries','App\Services\Countries')
				<select name="contact_country_id" id="contact_country_id">
					<option selected disabled hidden value="">{{ __('Selecciona un país') }}</option>
					@foreach ($countries->get() as $index => $country)
					<option value="{{ $index }}" {{ old('contact_country_id',Auth::user()->contact_country_id ?? 164) == $index ? 'selected' : '' }}>
						{{ $country }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Número celular') }}</label>
				<input type="tel" name="contact_mobile" id="contact_mobile" maxlength="11" value="{{ old('contact_mobile',Auth::user()->contact_mobile) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{3} [0-9]{3} [0-9]{3}" placeholder="999 999 999">
			</div>
			<div class="columna columna-6">
				<label>{{ __('Teléfono fijo') }}</label>
				<input type="tel" name="contact_phone" id="contact_phone" maxlength="11" value="{{ old('contact_phone',Auth::user()->contact_phone) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{2} [0-9]{3} [0-9]{4}" placeholder="99 999 9999">
			</div>
			<div class="columna columna-6">
				<label>{{ __('Anexo') }}</label>
				<input type="tel" name="contact_annex" id="contact_annex" maxlength="6" value="{{ old('contact_annex',Auth::user()->contact_annex) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{4,6}" placeholder="4 a 6 dígitos">
			</div>
		</div>
	</div>
</form>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<h6 class="title3">{{ __('Dependientes registrados') }}</h6>
		<a id="icn-lst" onclick="showForm('lst')" class="icn-sct"><i class="fa fa-plus fa-icon"></i></a>
	</div>
</div>
<div id="div-lst" class="fila" style="display:none">
	<div class="columna columna-1">
		<table id="tbl-dependents" class="tablealumno">
			<thead>
				<th width="20%">{{ __('Nombre completo') }}</th>
				<th width="20%">{{ __('Vínculo familiar') }}</th>
				<th width="20%">{{ __('Tipo de documento') }}</th>
				<th width="15%">{{ __('N° Documento') }}</th>
				<th width="15%">{{ __('F. Nacimiento') }}</th>
				<th width="10%">{{ __('Género') }}</th>
			</thead>
			<tbody>
				@if (Auth::user()->dependents->count())
				@foreach (Auth::user()->dependents as $dependent)
				<tr>
					<td>{{ $dependent->dependent_fullname }}</td>
					<td><center>{{ $dependent->dependentType->name ?? '' }}</center></td>
					<td><center>{{ $dependent->documentType->name ?? '' }}</center></td>
					<td><center>{{ $dependent->dependent_document }}</center></td>
					<td><center>{{ Carbon\Carbon::parse($dependent->dependent_birthdate)->format('d/m/Y') }}</center></td>
					<td><center>{{ $dependent->gender->name ?? '' }}</center></td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="6">{{ __('Sin resultados encontrados') }}</td>
				</tr>
				@endif
			</tbody>			
		</table>
	</div>
	<div class="columna columna-1">
		<div class="space"></div>
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<h6 class="title3">{{ __('Variaciones salariales') }}</h6>
		<a id="icn-sal" onclick="showForm('sal')" class="icn-sct"><i class="fa fa-plus fa-icon"></i></a>
	</div>
</div>
<div id="div-sal" class="fila" style="display:none">
	<div class="columna columna-1">
		<table id="tbl-variations" class="tablealumno">
			<thead>
				<th width="20%">{{ __('F. Efectiva') }}</th>
				<th width="20%">{{ __('Tipo de variación') }}</th>
				<th width="20%">{{ __('Sueldo inicial (S/)') }}</th>
				<th width="20%">{{ __('Monto (S/)') }}</th>
				<th width="20%">{{ __('Sueldo final (S/)') }}</th>
			</thead>
			<tbody>
				@if (Auth::user()->variations->count())
				@foreach (Auth::user()->variations as $variation)
				<tr>
					<td><center>{{ Carbon\Carbon::parse($variation->variation_start_at)->format('d/m/Y') }}</center></td>
					<td><center>{{ $variation->variation_type }}</center></td>
					<td><center>{{ number_format($variation->variation_before) }}</center></td>
					<td><center>{{ number_format($variation->variation_amount) }}</center></td>
					<td><center>{{ number_format($variation->variation_after) }}</center></td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="5">{{ __('Sin resultados encontrados') }}</td>
				</tr>
				@endif
			</tbody>			
		</table>
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<center>
		<button type="submit" class="btn-effie" onclick="document.getElementById('frm-profile').submit()"><i class="fa fa-save"></i>&nbsp;{{ __('Guardar') }}</button>
		<a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-reply"></i>&nbsp;{{ __('Regresar') }}</a>
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
					<li>{{ __('El tamaño máximo del nombre y apellidos es cincuenta (50) caracteres.') }}</li>
					<li>{{ __('El tamaño máximo de la dirección de domicilio es cien (100) caracteres.') }}</li>
					<li>{{ __('El tamaño máximo del nombre completo y parentesco del contacto de emergencia es cincuenta (50) caracteres.') }}</li>
					<li>{{ __('El tamaño máximo de la dirección del contacto de emergencia es cien (100) caracteres.') }}</li>
				</ul>
			</p>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('js/account.js') }}"></script>
@endsection