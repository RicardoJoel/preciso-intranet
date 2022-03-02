@extends('layouts.app')
@section('content')
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>Menú principal</h6>
        </div>
    </div>
</div>
<div class="fila">
    <div class="columna columna-1">
        <h6 class="title3">Entidades</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('customers.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de clientes">
                            <i class="fa fa-briefcase fa-4x"></i>                            
                            <p>Clientes</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('users.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de colaboradores">
                            <i class="fa fa-users fa-4x"></i>                            
                            <p>Colaboradores</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('freelancers.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de independientes">
                            <i class="fa fa-user-circle fa-4x"></i>                            
                            <p>Independientes</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('suppliers.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de proveedores">
                            <i class="fa fa-truck fa-4x"></i>                            
                            <p>Proveedores</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <h6 class="title3">Procesos</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('visits.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra tus contactos con clientes">
                            <i class="fa fa-street-view fa-4x"></i>                            
                            <p>Visitas</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('proposals.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra tus propuestas de proyecto">
                            <i class="fa fa-file-powerpoint-o fa-4x"></i>                            
                            <p>Propuestas</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('projects.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra y estructura tus proyectos">
                            <i class="fa fa-sitemap fa-4x"></i>                            
                            <p>Proyectos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra tus facturas por proyecto">
                            <i class="fa fa-calculator fa-4x"></i>                            
                            <p>Facturación</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra tus ingresos por proyecto">
                            <i class="fa fa-money fa-4x"></i>                            
                            <p>Recaudación</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <h6 class="title3">Utilitarios</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Gestiona la lista de archivos">
                            <i class="fa fa-folder-open fa-4x"></i>                            
                            <p>Archivos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra los gastos ocurridos">
                            <i class="fa fa-dollar fa-4x"></i>                            
                            <p>Gastos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('activities.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Ingresa tus actividades del día">
                            <i class="fa fa-calendar fa-4x"></i>                            
                            <p>Hoja de tiempo</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Gestiona los permisos">
                            <i class="fa fa-calendar-check-o fa-4x"></i>                            
                            <p>Vacaciones</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Gestiona la asignación de recursos">
                            <i class="fa fa-cubes fa-4x"></i>                            
                            <p>Recursos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <h6 class="title3">Reportes</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Reporte de balance por proyecto">
                            <i class="fa fa-balance-scale fa-4x"></i>                            
                            <p>Balance</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Reporte de comisiones por proyecto">
                            <i class="fa fa-thumbs-up fa-4x"></i>                            
                            <p>Comisiones</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Flujo de caja real y proyectado">
                            <i class="fa fa-line-chart fa-4x"></i>                            
                            <p>Flujo de caja</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Reporte operativo por proyecto">
                            <i class="fa fa-check-square-o fa-4x"></i>                            
                            <p>Operaciones</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('activities.report') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Reporte de actividad por proyecto">
                            <i class="fa fa-hourglass-half fa-4x"></i>                            
                            <p>Tiempos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <h6 class="title3">Mi cuenta</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-6">
        <div class="scene">    
            <div class="card">
                <a href="{{ route('profile') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza tus datos personales">
                            <i class="fa fa-user fa-4x"></i>                            
                            <p>Mis datos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('password') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza regularmente tu contraseña">
                            <i class="fa fa-lock fa-4x"></i>                            
                            <p>Seguridad</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-6">
        <div class="scene">
            <div class="card">
                <a href="{{ route('parameters.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Gestión de parámetros del sistema">
                            <i class="fa fa-cog fa-4x"></i>                            
                            <p>Ajustes</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection