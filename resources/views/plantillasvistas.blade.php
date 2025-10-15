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
    {{-- Estilos IMT Fin --}}
</head>
<body>
    <header class="responsive-header">
        <div class="header-icon left-icon">
          <img src="{{asset("/img/Logo_IMT.png")}}" alt="Icono Izquierdo">
        </div>
        <h1 class="header-title">Manuales y Normas</h1>
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
              <a href="{{ route('inicio') }}">
                <img src="{{asset('/img/home.png')}}" alt="">
                &nbsp;Inicio
              </a>
            </li>
            <li>
              <a href="{{ route('formulario') }}">
                <img src="{{asset('/img/invent_bl.png')}}" alt="">
                &nbsp;Formulario
              </a>
            </li>
            <li>
              <a href="{{ route('fechas') }}">
                <img src="{{asset('/img/entrada.png')}}" alt="">
                &nbsp;Gestión de Fechas
              </a>
            </li>
            <li>
              <a href="#">
                <img src="{{asset('/img/config.png')}}" alt="">
                &nbsp;Salida de almacén
              </a>
            </li>
            <li>
              <a href="#">
                <img src="{{asset('/img/config.png')}}" alt="">
                &nbsp;Resguardo
              </a>
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
</body>
</html>
