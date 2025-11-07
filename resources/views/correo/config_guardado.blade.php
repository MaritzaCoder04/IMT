<div style="padding:16px;">
    <h3>Configuración guardada</h3>
    <p>Se guardó el correo destinatario: <strong>{{ $email }}</strong>.</p>
    <p>Cerrando modalâ€¦</p>
    <script>
        setTimeout(function(){
            window.parent.postMessage({type:'modal-close'}, '*');
        }, 800);
    </script>
</div>
