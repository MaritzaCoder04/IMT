<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('/img/Logo_IMT.png') }}" type="image/png" />

  {{-- Estilos IMT --}}
  <link rel="stylesheet" href="{{ asset('/css/estilovistas.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/estilosBotones.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/estilosForm.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/estilosTablas.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/estilosImagenes.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/estiloformularios.css') }}">

  <title>Iniciar sesión</title>

  <style>
    /* Ajustes mínimos para esta vista */
    body { background: #f6f8fb; }
    .auth-wrap { min-height: 100dvh; display: flex; align-items: center; }
    .form-container { max-width: 480px; margin: 0 auto; } /* reutiliza tu card */
    .brand { margin-bottom: 8px; }
    .lead  { color:#555; margin:.25rem 0 1rem; }

    /* Icono dentro del input de password */
    .password-wrap { position: relative; }
    .toggle-icon {
      position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
      border: 0; background: transparent; cursor: pointer; padding: 4px; line-height: 0;
    }
    .toggle-icon img { display: block; width: 20px; height: 20px; object-fit: contain; }

    /* Caja de errores con tu paleta */
    .errors{
      background:#ffecec; border:1px solid #ffc9c9; color:#7a1e1e;
      padding:10px 12px; border-radius:10px; margin-bottom:14px; font-size:14px;
    }
  </style>
</head>
<body>
  <div class="auth-wrap">
    <div class="all-form" style="width:100%;">
      <div class="form-container" role="main" aria-labelledby="title">
        <div class="brand">
          {{-- <img src="{{ asset('img/Logo_IMT.png') }}" alt="Logo" style="height:42px"> --}}
          <h1 id="title">Iniciar sesión</h1>
        </div>
        <p class="lead">¡Bienvenida! Por favor ingresa Email y Contraseña</p>

        {{-- Errores de validación --}}
        @if ($errors->any())
          <div class="errors">
            <ul style="margin:0 0 0 18px; padding:0;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
          @csrf

          <div class="form-group">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" autocomplete="username"
                   value="{{ old('email') }}" required placeholder="tucorreo@ejemplo.com">
          </div>

          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="password-wrap">
              <input id="password" name="password" type="password" autocomplete="current-password"
                     required placeholder="••••••••">
              <button type="button" class="btn-icon toggle-icon" id="togglePass"
                      aria-label="Mostrar contraseña" aria-pressed="false" title="Mostrar contraseña">
                <img id="icon-pass" src="{{ asset('img/showb.png') }}" alt="Mostrar contraseña">
              </button>
            </div>
          </div>

          <div class="form-group" style="display:flex; align-items:center; justify-content:space-between;">
            <label style="display:flex; align-items:center; gap:8px; margin:0;">
              <input type="checkbox" name="remember"> Recuérdame
            </label>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
          </div>

          {{-- Usa tu botón ya definido para formularios --}}
          <button type="submit" class="submit-btn">Entrar</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const btn  = document.getElementById('togglePass');
      const input = document.getElementById('password');
      const img  = document.getElementById('icon-pass');

      const ICON_SHOW = "{{ asset('img/showb.png') }}";      // ojo abierto
      const ICON_HIDE = "{{ asset('img/hidenb.png') }}";  // ojo tachado

      // Pre-carga para evitar parpadeo en el primer toggle
      (new Image()).src = ICON_HIDE;

      if (btn && input && img) {
        btn.addEventListener('click', function () {
          const show = (input.type === 'password');      // ¿vamos a mostrar?
          input.type  = show ? 'text' : 'password';

          const label = show ? 'Ocultar contraseña' : 'Mostrar contraseña';
          btn.setAttribute('aria-pressed', String(show));
          btn.setAttribute('aria-label', label);
          btn.title = label;
          img.alt   = label;
          img.src   = show ? ICON_HIDE : ICON_SHOW;
        });
      }
    })();
  </script>
</body>
</html>
