<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{asset('LOGO COMSITEC1.jpg')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Comsitec</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('LOGO COMSITEC.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ route('perfil') }}" class="d-block">{{ Auth()->user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          @php
            $cotizador = request()->routeIs('cotizacion.showForm');
            $isActiveCotizacion = request()->routeIs('cotizacion.listado');
            $isActiveCotizadorCRM = request()->routeIs('cotizacion.listado_crm');
            $isActiveCotizadorEdit = request()->routeIs('cotizacion.showForm_editar');
            $isActiveProductos = request()->routeIs('catalogos.listado_productos');
            $productosForm = request()->routeIs('catalogos.form_productos');
            $isActiveProveedores = request()->routeIs('catalogos.listado_proveedores');
            $isActiveMarcas = request()->routeIs('catalogos.listado_marcas');
            $isActiveTextos = request()->routeIs('catalogos.listado_conceptos');
            $textosForm = request()->routeIs('catalogos.conceptos');
            $isActiveAdicionales = request()->routeIs('catalogos.listado_adicionales');
            $adicionalesForm = request()->routeIs('catalogos.form_adicionales');
            $isActiveSalas = request()->routeIs('catalogos.listado_salas');
            $salasForm = request()->routeIs('catalogos.form_salas');
            $isActiveCalendario = request()->routeIs('catalogos.calendario');
            $isActiveCliente = request()->routeIs('catalogos.listado_clientes');
            $clientesForm = request()->routeIs('catalogos.show_form');
            $isActiveUsuarios = request()->routeIs('admin.listado_usuario');
            $isActiveRoles = request()->routeIs('admin.listado_roles');
            $roles = request()->routeIs('admin.roles');
            $rolXpermiso = request()->routeIs('admin.rolxpermiso');
            $permisos = request()->routeIs('admin.permisos');
            $inicio = request()->routeIs('inicio');
          @endphp

          <li class="nav-item {{  $inicio ? 'menu-open menu-is-opening' : '' }}">
            <a href="" class="nav-link {{  $inicio ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Inicio
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $inicio ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inicio</p>
                </a>
              </li>
            </ul>
          </li>

          @if (validacion_rol(Auth()->user()->idrol,'CALENDARIO'))
            <li class="nav-item {{  $isActiveCalendario || $isActiveSalas || $salasForm ? 'menu-is-opening menu-open' : '' }}">
                <a href="" class="nav-link {{  $isActiveCalendario || $isActiveSalas || $salasForm ? 'active' : '' }}">
                    <i class="nav-icon far fa-calendar-alt"></i>
                    <p>
                        Reservas
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @if (validacion_rol(Auth()->user()->idrol, 'CALENDARIO'))
                        <li class="nav-item">
                            <a href="{{ url('/catalogos/calendario') }}" class="nav-link {{ $isActiveCalendario ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Calendario</p>
                            </a>
                        </li>
                    @endif
                    @if (validacion_rol(Auth()->user()->idrol, 'SALAS'))
                        <li class="nav-item">
                            <a href="{{ url('/catalogos/listado/salas') }}" class="nav-link {{ $isActiveSalas ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Salas</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
          @endif

          @if (validacion_rol(Auth()->user()->idrol,'COTIZACIONES'))
            <li class="nav-item {{  $isActiveCotizacion || $cotizador || $isActiveCotizadorCRM || $isActiveCotizadorEdit ? 'menu-is-opening menu-open' : '' }}">
                <a href="" class="nav-link {{  $isActiveCotizacion || $cotizador || $isActiveCotizadorCRM || $isActiveCotizadorEdit ? 'active' : '' }}">
                <i class="far fa-file-alt mr-1"></i>
                <p>
                    Cotizador
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/cotizacion/listado') }}" class="nav-link {{ $isActiveCotizacion ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Mis Cotizaciones</p>
                        </a>
                    </li>
                    @if (validacion_rol(Auth()->user()->idrol, 'FORMCOTIZA'))
                        <li class="nav-item">
                            <a href="{{ url('/cotizacion') }}" class="nav-link {{ $cotizador ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Formulario</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
          @endif

          @if (validacion_rol(Auth()->user()->idrol,'PRODUCTOS'))
            <li class="nav-item {{  $isActiveProductos || $productosForm || $isActiveMarcas || $isActiveProveedores ? 'menu-is-opening menu-open' : '' }}">
                <a href="" class="nav-link {{  $isActiveProductos || $productosForm || $isActiveMarcas || $isActiveProveedores ? 'active' : '' }}">
                <i class="fas fa-barcode"></i>
                <p>
                    Productos
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/catalogos/listado/productos') }}" class="nav-link {{ $isActiveProductos ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Productos</p>
                        </a>
                    </li>
                    @if (validacion_rol(Auth()->user()->idrol, 'MARCAS'))
                        <li class="nav-item">
                            <a href="{{ url('catalogos/listado/marcas') }}" class="nav-link {{ $isActiveMarcas ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Marcas</p>
                            </a>
                        </li>
                    @endif
                    @if (validacion_rol(Auth()->user()->idrol, 'PROVEEDORES'))
                        <li class="nav-item">
                            <a href="{{ url('/catalogos/listado/proveedores') }}" class="nav-link {{ $isActiveProveedores ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Proveedores</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
          @endif

          @if (validacion_rol(Auth()->user()->idrol,'ADICIONALES'))
            <li class="nav-item {{  $isActiveAdicionales || $adicionalesForm ? 'menu-is-opening menu-open' : '' }}">
                <a href="" class="nav-link {{  $isActiveAdicionales || $adicionalesForm ? 'active' : '' }}">
                <i class="fas fa-inbox"></i>
                <p>
                    Adicionales
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/catalogos/listado/adicionales') }}" class="nav-link {{ $isActiveAdicionales ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Adicionales</p>
                        </a>
                    </li>
                </ul>
            </li>
          @endif

          @if (validacion_rol(Auth()->user()->idrol,'CONCEPTOS'))
            <li class="nav-item {{  $isActiveTextos || $textosForm ? 'menu-is-opening menu-open' : '' }}">
                <a href="" class="nav-link {{  $isActiveTextos || $textosForm ? 'active' : '' }}">
                    <i class="nav-icon fas fa-handshake"></i>
                <p>
                    Textos
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/catalogos/listado/conceptos') }}" class="nav-link {{ $isActiveTextos ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Presentaciones</p>
                        </a>
                    </li>
                </ul>
            </li>
          @endif

          @if (validacion_rol(Auth()->user()->idrol,'CLIENTES'))
            <li class="nav-item {{  $isActiveCliente || $clientesForm ? 'menu-is-opening menu-open' : '' }}">
                <a href="" class="nav-link {{  $isActiveCliente || $clientesForm ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <p>
                    Clientes
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/catalogos/listado/clientes') }}" class="nav-link {{ $isActiveCliente ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Clientes</p>
                        </a>
                    </li>
                </ul>
            </li>
          @endif

          @if (validacion_rol(Auth()->user()->idrol,'USUARIOS'))
            <li class="nav-item {{  $isActiveUsuarios ? 'menu-is-opening menu-open' : '' }}">
                <a href="" class="nav-link {{  $isActiveUsuarios ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                <p>
                    Usuarios
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/admin/listado/usuarios') }}" class="nav-link {{ $isActiveUsuarios ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Usuarios</p>
                        </a>
                    </li>
                </ul>
            </li>
          @endif

          @if (validacion_rol(Auth()->user()->idrol,'ROLES'))
            <li class="nav-item {{  $isActiveRoles || $roles || $rolXpermiso || $permisos ? 'menu-is-opening menu-open' : '' }}">
                <a href="" class="nav-link {{  $isActiveRoles || $roles || $rolXpermiso || $permisos ? 'active' : '' }}">
                    <i class="fas fa-wrench"></i>
                <p>
                    Roles / Perfiles
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/admin/listado/roles') }}" class="nav-link {{ $isActiveRoles ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Roles</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/roles') }}" class="nav-link {{ $roles ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Agregar Roles</p>
                        </a>
                    </li>
                </ul>
            </li>
          @endif

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
