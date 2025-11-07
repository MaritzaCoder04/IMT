<p>Hola,</p>

<p>Este es un recordatorio automático: en dos semanas se cumple una fecha registrada sin marcar como completada.</p>

<ul>
    <li><strong>Tipo de fecha:</strong> {{ $tipoFecha }}</li>
    <li><strong>Etapa:</strong> {{ $etapa }}</li>
    <li><strong>Fecha:</strong> {{ $fecha }}</li>
    <li><strong>Documento:</strong> {{ $documento }} ({{ $designacion }})</li>
</ul>

@if(!empty($mensaje))
<p><strong>Mensaje:</strong> {{ $mensaje }}</p>
@endif

<p>Por favor, revise el módulo Control de Avances para dar seguimiento.</p>

<p>Saludos.</p>