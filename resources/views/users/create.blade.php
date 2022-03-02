@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>{{ __('Entidades > Colaboradores > Nuevo') }}</h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('register') }}" role="form" id="frm-user">
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
				<input type="text" name="name" id="name" maxlength="50" value="{{ old('name') }}" onkeypress="return checkName(event)" required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Apellidos*') }}</label>
				<input type="text" name="lastname" id="lastname" maxlength="50" value="{{ old('lastname') }}" onkeypress="return checkName(event)" required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Correo electrónico Preciso*') }}</label>
				<input type="email" name="email" id="email" maxlength="50" value="{{ old('email') }}" onkeypress="return checkEmail(event)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2, 4}$" required>
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
				<input type="hidden" name="doc_pattern" id="doc_pattern" value="{{ old('doc_pattern') }}">
				<input type="text" name="document" id="document" value="{{ old('document') }}" onkeyup="return mayusculas(this)" disabled required>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Género*') }}</label>
				@inject('genders','App\Services\Genders')
				<select name="gender_id" id="gender_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona') }}</option>
					@foreach ($genders->get() as $index => $gender)
					<option value="{{ $index }}" {{ old('gender_id') == $index ? 'selected' : '' }}>
						{{ $gender }}
					</option>
					@endforeach
				</select>					
			</div>
			<div class="columna columna-3">
				<label>{{ __('Fecha de nacimiento*') }}</label>
				<input type="date" name="birthdate" id="birthdate" max="{{ Carbon\Carbon::today()->subYear(18)->toDateString() }}" value="{{ old('birthdate') }}" required>
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
				<label>{{ __('Distrito*') }}</label>
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
				<label>{{ __('Correo electrónico personal') }}</label>
				<input type="email" name="alt_email" id="alt_email" maxlength="50" value="{{ old('alt_email') }}" onkeypress="return checkEmail(event)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
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
				<label>{{ __('Vínculo laboral*') }}</label>
				@inject('relationships','App\Services\Relationships')
				<select name="relationship_id" id="relationship_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona un vínculo laboral') }}</option>
					@foreach ($relationships->get() as $index => $relationship)
					<option value="{{ $index }}" {{ old('relationship_id') == $index ? 'selected' : '' }}>
						{{ $relationship }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Cargo*') }}</label>
				@inject('profiles','App\Services\Profiles')
				<select name="profile_id" id="profile_id" required>
					<option selected disabled hidden value="">{{ __('Selecciona un cargo') }}</option>
					@foreach ($profiles->get('C') as $index => $profile)
					<option value="{{ $index }}" {{ old('profile_id') == $index ? 'selected' : '' }}>
						{{ $profile }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Sueldo bruto (S/)*') }}</label>
				<input type="number" name="str_salary" id="str_salary" value="{{ old('str_salary') }}" onkeypress="return checkNumber(event)" required>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Comisión (%)') }}</label>
				<input type="number" name="commission" id="commission" value="{{ old('commission',0) }}" onkeypress="return checkNumber(event)">
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Frec. Variación salarial') }}</label>
				@inject('frequencies','App\Services\Frequencies')
				<select name="frequency_id" id="frequency_id">
					<option selected value="">{{ __('Sueldo no variable') }}</option>
					@foreach ($frequencies->get() as $index => $frequency)
					<option value="{{ $index }}" {{ old('frequency_id') == $index ? 'selected' : '' }}>
						{{ $frequency }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Fecha de inicio*') }}</label>
				<input type="date" name="start_at" id="start_at" value="{{ old('start_at') }}" required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Fecha de cese') }}</label>
				<input type="date" name="end_at" id="end_at" value="{{ old('end_at') }}" max="{{ Carbon\Carbon::today()->toDateString() }}">
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
				<label>{{ __('Entidad bancaria Sueldo*') }}</label>
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
				<label>{{ __('N° Cuenta Sueldo*') }}</label>
				<input type="text" name="bank_account" id="bank_account" maxlength="20" value="{{ old('bank_account') }}" onkeypress="return checkNumber(event)" disabled required>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Código de Cuenta Interbancario (CCI)*') }}</label>
				<input type="text" name="cci" id="cci" maxlength="23" value="{{ old('cci') }}" onkeypress="return checkNumber(event)" disabled required>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Adm. Fondo de pensiones (AFP) / ONP') }}</label>
				@inject('afps','App\Services\AFPs')
				<select name="afp_id" id="afp_id">
					<option selected value="">{{ __('No cuenta con AFP / ONP') }}</option>
					@foreach ($afps->get() as $index => $afp)
					<option value="{{ $index }}" {{ old('afp_id') == $index ? 'selected' : '' }}>
						{{ $afp }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Tipo de comisión') }}</label>
				@inject('commissions','App\Services\Commissions')
				<select name="commission_id" id="commission_id" disabled>
					<option selected disabled hidden value="">{{ __('Selecciona un tipo de comisión') }}</option>
					@foreach ($commissions->get() as $index => $commission)
					<option value="{{ $index }}" {{ old('commission_id') == $index ? 'selected' : '' }}>
						{{ $commission }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Código CUSPP') }}</label>
				<input type="text" name="cuspp" id="cuspp" maxlength="12" value="{{ old('cuspp') }}" onkeypress="return checkAlNum(event)" onkeyup="return mayusculas(this)" disabled>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Entidad bancaria CTS') }}</label>
				@inject('banks','App\Services\Banks')
				<select name="cts_id" id="cts_id">
					<option selected value="">{{ __('No cuenta con CTS') }}</option>
					@foreach ($banks->get() as $index => $bank)
					<option value="{{ $index }}" {{ old('cts_id') == $index ? 'selected' : '' }}>
						{{ $bank }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-3">
				<label>{{ __('N° Cuenta CTS') }}</label>
				<input type="text" name="cts_account" id="cts_account" maxlength="20" value="{{ old('cts_account') }}" onkeypress="return checkNumber(event)" disabled>
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Plan EPS') }}</label>
				@inject('epss','App\Services\EPSs')
				<select name="eps_id" id="eps_id">
					<option selected value="">{{ __('No cuenta con EPS') }}</option>
					@foreach ($epss->get() as $index => $eps)
					<option value="{{ $index }}" {{ old('eps_id') == $index ? 'selected' : '' }}>
						{{ $eps }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-3">
				<label>{{ __('Código Essalud') }}</label>
				<input type="text" name="essalud" id="essalud" maxlength="15" value="{{ old('essalud') }}" onkeypress="return checkAlNum(event)" onkeyup="return mayusculas(this)">
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
				<input type="text" name="contact_fullname" id="contact_fullname" maxlength="50" value="{{ old('contact_fullname') }}" onkeypress="return checkName(event)">
			</div>
			<div class="columna columna-3">
				<label>{{ __('Parentesco') }}</label>
				<input type="text" name="contact_relationship" id="contact_relationship" maxlength="50" value="{{ old('contact_relationship') }}" onkeypress="return checkName(event)">
			</div>
			<div class="columna columna-3">
				<label>{{ __('Dirección') }}</label>
				<input type="text" name="contact_address" id="contact_address" maxlength="100" value="{{ old('contact_address') }}" onkeypress="return checkText(event)">
			</div>
		</div>
		<div class="fila">
			<div class="columna columna-3">
				<label>{{ __('Distrito') }}</label>
				@inject('ubigeos','App\Services\Ubigeos')
				<select name="contact_ubigeo_id" id="contact_ubigeo_id">
					<option selected disabled hidden value="">{{ __('Selecciona un distrito') }}</option>
					@foreach ($ubigeos->get() as $index => $ubigeo)
					<option value="{{ $index }}" {{ old('contact_ubigeo_id') == $index ? 'selected' : '' }}>
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
					<option value="{{ $index }}" {{ old('contact_country_id',164) == $index ? 'selected' : '' }}>
						{{ $country }}
					</option>
					@endforeach
				</select>
			</div>
			<div class="columna columna-6">
				<label>{{ __('Número celular') }}</label>
				<input type="tel" name="contact_mobile" id="contact_mobile" maxlength="11" value="{{ old('contact_mobile') }}" onkeypress="return checkNumber(event)" pattern="[0-9]{3} [0-9]{3} [0-9]{3}" placeholder="999 999 999">
			</div>
			<div class="columna columna-6">
				<label>{{ __('Teléfono fijo') }}</label>
				<input type="tel" name="contact_phone" id="contact_phone" maxlength="11" value="{{ old('contact_phone') }}" onkeypress="return checkNumber(event)" pattern="[0-9]{2} [0-9]{3} [0-9]{4}" placeholder="99 999 9999">
			</div>
			<div class="columna columna-6">
				<label>{{ __('Anexo') }}</label>
				<input type="tel" name="contact_annex" id="contact_annex" maxlength="6" value="{{ old('contact_annex') }}" onkeypress="return checkNumber(event)" pattern="[0-9]{4,6}" placeholder="4 a 6 dígitos">
			</div>
		</div>
	</div>
</form>
@include('users/dependent')
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<center>
		<button type="submit" class="btn-effie" onclick="document.getElementById('frm-user').submit();"><i class="fa fa-save"></i>&nbsp;{{ __('Registrar') }}</button>
		<a href="{{ route('users.index') }}" class="btn-effie-inv">{{ __('Cancelar') }}</a>
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
				<li>{{ __('El correo electrónico Preciso es único y tiene un tamaño máximo de cincuenta (50) caracteres.') }}</li>
				<li>{{ __('El tamaño máximo del nombre y apellidos del colaborador es cincuenta (50) caracteres.') }}</li>
				<li>{{ __('El tamaño máximo de la dirección de domicilio del colaborador es cien (100) caracteres.') }}</li>
				<li>{{ __('El tamaño máximo del nombre completo y parentesco del contacto en caso de emergencia es cincuenta (50) caracteres.') }}</li>
				<li>{{ __('El tamaño máximo de la dirección del contacto en caso de emergencia es cien (100) caracteres.') }}</li>
				<li>{{ __('El tamaño máximo del nombre completo del dependiente es cincuenta (50) caracteres.') }}</li>
				<li>{{ __('Para cancelar la inserción o edición de datos de un dependiente presiona el botón "Limpiar".') }}</li>
				<li>{{ __('Para guardar los cambios efectuados en el colaborador y/o lista de dependientes presiona el botón "Registrar".') }}</li>
			</ul>
		</p>
	</div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('js/users/dependents.js') }}"></script>
<script src="{{ asset('js/users/form3.js') }}"></script>
@endsection