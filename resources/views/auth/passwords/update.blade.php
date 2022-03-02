@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>{{ __('Mi cuenta > Cambiar contraseña') }}</h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('changePassword') }}" role="form">
	@csrf
	<input type="hidden" name="email" value="{{ Auth::user()->email }}">

	<div class="fila">
		<div class="columna columna-3">
			<label>{{ __('Contraseña actual*') }}</label>
			<input type="password" name="current_password" id="current_password" maxlength="50" required>
		</div>
		<div class="columna columna-3">
			<label>{{ __('Nueva contraseña*') }}</label>
			<input type="password" name="new_password" id="new_password" maxlength="50" required>
		</div>
		<div class="columna columna-3">
			<label>{{ __('Confirmar contraseña*') }}</label>
			<input type="password" name="new_confirm_password" id="new_confirm_password" maxlength="50" required>
		</div>
	</div>
	<div class="fila">
		<div class="space"></div>
		<div class="columna columna-1">
			<center>
			<button type="submit" class="btn-effie"><i class="fa fa-save"></i>&nbsp;{{ __('Guardar') }}</button>
			<a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-reply"></i>&nbsp;{{ __('Regresar') }}</a>
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
				<li>{{ __('La nueva contraseña debe estar compuesta por entre ocho (8) y cincuenta (50) caracteres con, al menos, una letra y un dígito.') }}</li>
				<li>{{ __('La nueva contraseña debe ser diferente a su correo electrónico.') }}</li>
			</ul>
		</p>
	</div>
</div>
@endsection