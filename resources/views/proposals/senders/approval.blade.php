<div class="modal fade" id="mdl-prop-apv" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3 class="modal-title">{{ __('Ingresa la fecha de aprobación') }}</h3>
            </div>
            <div class="modal-body">
                <div class="space"></div>
                <center><i class="fa fa-exclamation-triangle fa-4x fa-icon"></i></center>
                <div class="space"></div>

                <div class="fila">
                    <div class="columna columna-1">
                        <p>Estás a punto de cambiar el estado actual de la propuesta a <b>APROBADO</b>. Pasado este punto, ya no podrás editarla. Para continuar, ingresa la fecha de aprobación y haz click sobre el botón <b>Confirmar</b>. Si cambiaste de opinión, haz click sobre el botón <b>Cerrar</b>.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('proposals.approve',$proposal->id) }}" role="form">
                    @csrf
                    <div class="space"></div>
                    <div class="fila">
                        <div class="columna columna-4"><br></div>
                        <div class="columna columna-2">
                            <p>Fecha de aprobación*</p>
                            <input type="date" name="changed_at" value="{{ old('changed_at',Carbon\Carbon::today()->toDateString()) }}" max="{{ Carbon\Carbon::today()->toDateString() }}" required>
                        </div>
                        <div class="columna columna-4"><br></div>
                    </div>
                    <div class="space"></div>
                    <div class="fila">
                        <div class="columna columna-1">
                            <p>(*) Campo obligatorio</p>
                        </div>
                    </div>
                    <div class="space"></div>
                    <div class="modal-footer">
                        <center>
                        <button type="submit" class="btn-effie">Confirmar</button>
                        <a class="btn-effie-inv" data-dismiss="modal">Cerrar</a>	
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>