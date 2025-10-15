<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- jquery v3.7.1 (local) --}}
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    {{-- Estilo para el select2 --}}
    <link href="{{ asset('/css/select2.min.css' ) }}" rel="stylesheet" />
    {{-- Ejecutable select2 --}}
    <script src="{{ asset('/js/select2.min.js') }}"></script>
    <!-- (Opcional) Traducción al español -->
    <script src="{{ asset('/js/i18n/es.js') }}"></script>

    {{-- <title></title> --}}
    <link rel="icon" href="{{asset('/img/Logo_IMT.png')}}" type="image/png" />
    
    {{-- Estilos IMT Incicio --}}
      <link rel="stylesheet" href="{{ asset('/css/estilovistas.css') }}">
      <link rel="stylesheet" href="{{ asset('/css/estilosBotones.css') }}">
      <link rel="stylesheet" href="{{ asset('/css/estilosForm.css') }}">
      <link rel="stylesheet" href="{{ asset('/css/estilosTablas.css') }}">
      <link rel="stylesheet" href="{{ asset('/css/estilosImagenes.css') }}">
      <link rel="stylesheet" href="{{ asset('/css/estiloformularios.css') }}">
    {{-- Estilos IMT Fin --}}

</head>
<body> 
    <header class="responsive-header">
        <div class="header-icon left-icon">
          <img src="{{asset("/img/Logo_IMT.png")}}" alt="Icono Izquierdo">
        </div>
        <h1 class="header-title">Gestión de avances de la CNIT</h1>
        <h4 class="user-title">Nombre de usuario</h4>
        <div class="space-title">&nbsp;&nbsp;</div>
        <div class="header-icon right-icon">
          <a href="">
            <button class="btn-logout" data-tooltip="Salir">
              <img src="{{asset("/img/user_icon_1.png")}}" alt="Icono Derecho" id="icono">
            </button>
          </a>
        </div>
    </header>

    <nav class="sidebar">
      <div class="scrollable">
        <ul>
          <li>
            <a href="{{ route('todoslosdocumentos') }}">
              <img src="{{asset('/img/invent_bl.png')}}" alt="">
              &nbsp;Todos los documentos
            </a>
          </li>
          <li>
            <a href="{{ route('controldeavances') }}">
              <img src="{{asset('/img/invent_bl.png')}}" alt="">
              &nbsp;Control de Avances
            </a>
          </li>
          <li>
            <a href="{{ route('productosterminados') }}">
              <img src="{{asset('/img/invent_bl.png')}}" alt="">
              &nbsp;Productos Terminados
            </a>
          </li>
            <li class="submenu-toggle">
              <a href="" onclick="event.preventDefault(); this.parentElement.classList.toggle('open');">
                <img src="{{asset('/img/config.png')}}" alt="">
                &nbsp;Representaciones
              </a>
                <ul class="submenu">
                  <li>
                    <a href="{{ route('representaciones') }}">
                      &nbsp;&nbsp;&nbsp;
                      <img src="{{asset('/img/marca.png')}}" alt="">
                      &nbsp;Gestion de organismos
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('organismos') }}">
                      &nbsp;&nbsp;&nbsp;
                      <img src="{{asset('/img/marca.png')}}" alt="">
                      &nbsp;Registro de reuniones
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('busqueda') }}">
                      &nbsp;&nbsp;&nbsp;
                      <img src="{{asset('/img/marca.png')}}" alt="">
                      &nbsp;Busqueda avanzada
                    </a>
                  </li>
                </ul>
            </li>
            <li class="submenu-toggle">
                <a href="" onclick="event.preventDefault(); this.parentElement.classList.toggle('open');">
                  <img src="{{asset('/img/config.png')}}" alt="">
                  &nbsp;Informes
                </a>
                  <ul class="submenu">
                    <li>
                      <a href="{{ route('registroinformes') }}">
                        &nbsp;&nbsp;&nbsp;
                        <img src="{{asset('/img/invent_bl.png')}}" alt="">
                        &nbsp;Registro Informes
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('informes') }}">
                        &nbsp;&nbsp;&nbsp;
                        <img src="{{asset('/img/tablefull.png')}}" alt="">
                        &nbsp;Avances
                      </a>
                    </li>
                  </ul>
              </li>
        </ul>
      </div>
    </nav>

    <main class="contenido">
    
    @yield('contenido')

      <footer>
          2025 © Desarrollado por la División de Telemática
      </footer>

    </main>
    <script>
      // Guardar scroll antes de cualquier recarga
      if (history.scrollRestoration) {
          history.scrollRestoration = 'manual';
      }
      
      window.addEventListener('beforeunload', function() {
          sessionStorage.setItem('scrollPosition', window.scrollY.toString());
      });

      // Restaurar scroll al cargar
      window.addEventListener('DOMContentLoaded', function() {
          const savedPosition = sessionStorage.getItem('scrollPosition');
          if (savedPosition !== null) {
              window.scrollTo(0, parseInt(savedPosition));
          }
      });
  </script>
</body>
</html>
