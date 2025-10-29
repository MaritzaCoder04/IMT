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
          <img src="{{asset("/img/Logo_blanco.png")}}" alt="Icono Izquierdo">
        </div>
        <h1 class="header-title">Gestión de avances de la CNIT</h1>
        <h4 class="user-title">Nombre de usuario</h4>
        <div class="space-title">&nbsp;&nbsp;</div>
        <div class="header-icon right-icon">
          <a href="">
            <button class="btn-logout" data-tooltip="Salir">
              <img src="{{asset("/img/circle-user.png")}}" alt="Icono Derecho" id="icono">
            </button>
          </a>
        </div>
    </header>

    <nav class="sidebar" id="sidebar">
      <div >
        <ul>
          <li>
            <a href="{{ route('todoslosdocumentos') }}" data-tooltip="Todos los documentos">
              <img src="{{asset('/img/invent_bl.png')}}" alt="">
              <span class="link-text">&nbsp;Todos los documentos</span>
            </a>
          </li>
          <li>
            <a href="{{ route('controldeavances') }}" data-tooltip="Control de Avances">
              <img src="{{asset('/img/invent_bl.png')}}" alt="">
              <span class="link-text">&nbsp;Control de Avances</span>
            </a>
          </li>
          <li>
            <a href="{{ route('productosterminados') }}" data-tooltip="Productos Terminados">
              <img src="{{asset('/img/invent_bl.png')}}" alt="">
              <span class="link-text">&nbsp;Productos Terminados</span>
            </a>
          </li>
          <li>
            <a href="{{ route('grupotrabajo.index') }}" data-tooltip="Programaciones">
              <img src="{{asset('/img/config.png')}}" alt="">
              <span class="link-text">&nbsp;Programaciones</span>
            </a>
          </li>
          <li>
            <a href="{{ route('grupotrabajo.agenda') }}" data-tooltip="Representaciones">
              <img src="{{asset('/img/config.png')}}" alt="">
              <span class="link-text">&nbsp;Representaciones</span>
            </a>
          </li>
          <li>
            <a href="{{ route('grupotrabajo.reporte') }}" data-tooltip="Informe Anual">
              <img src="{{asset('/img/config.png')}}" alt="">
              <span class="link-text">&nbsp;Informe Anual</span>
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

    <script>
      // Funcionalidad del doble clic en el sidebar
      document.addEventListener('DOMContentLoaded', function() {
          const sidebar = document.getElementById('sidebar');
          const sidebarLinks = sidebar.querySelectorAll('a');
          const body = document.body;
          let clickTimer = null;
          let clickCount = 0;

          // Cargar estado guardado
          const sidebarState = localStorage.getItem('sidebarCollapsed');
          if (sidebarState === 'true') {
              sidebar.classList.add('collapsed');
              body.classList.add('sidebar-collapsed');
          }
          // Accesibilidad: reflejar estado expandido/colapsado
          

          // Función para colapsar/expandir el sidebar con animación
          function toggleSidebar() {
              sidebar.classList.add('collapsing');
              sidebar.classList.toggle('collapsed');
              body.classList.toggle('sidebar-collapsed');
              
              // Guardar estado
              const isCollapsed = sidebar.classList.contains('collapsed');
              localStorage.setItem('sidebarCollapsed', isCollapsed);
              

              // Remover clase de animación después de completar
              setTimeout(() => {
                  sidebar.classList.remove('collapsing');
              }, 300);

              // Efecto de vibración sutil
              if ('vibrate' in navigator) {
                  navigator.vibrate(10);
              }
          }

          // Agregar evento a cada enlace
          sidebarLinks.forEach(link => {
              link.addEventListener('click', function(e) {
                  clickCount++;
                  
                  if (clickCount === 1) {
                      // Primer clic
                      clickTimer = setTimeout(function() {
                          clickCount = 0;
                          // Si está colapsado, expandir al hacer clic
                          if (sidebar.classList.contains('collapsed')) {
                              e.preventDefault();
                              toggleSidebar();
                          }
                          // Si no está colapsado, dejar que navegue normalmente
                      }, 300);
                  } else if (clickCount === 2) {
                      // Segundo clic - prevenir navegación y colapsar
                      e.preventDefault();
                      clearTimeout(clickTimer);
                      clickCount = 0;
                      toggleSidebar();
                  }
              });

              // Efecto visual al hacer hover
              link.addEventListener('mouseenter', function() {
                  if (!sidebar.classList.contains('collapsed')) {
                      this.style.transform = 'translateX(5px)';
                  }
              });

              link.addEventListener('mouseleave', function() {
                  this.style.transform = 'translateX(0)';
              });
          });

          // También permitir expandir haciendo clic en cualquier parte del sidebar colapsado
          sidebar.addEventListener('click', function(e) {
              if (sidebar.classList.contains('collapsed') && !e.target.closest('a')) {
                  toggleSidebar();
              }
          });
      });

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