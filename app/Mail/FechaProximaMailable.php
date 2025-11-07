<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FechaProximaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $tipoFecha;    // Inicio/Entrega/Terminación
    public $etapa;        // APT/AFT/PPT/NA/NP
    public $fecha;        // dd/mm/aaaa
    public $documento;    // nombre
    public $designacion;  // designación
    public $mensaje;      // personalizado

    public function __construct($tipoFecha, $etapa, $fecha, $documento, $designacion, $mensaje)
    {
        $this->tipoFecha = $tipoFecha;
        $this->etapa = $etapa;
        $this->fecha = $fecha;
        $this->documento = $documento;
        $this->designacion = $designacion;
        $this->mensaje = $mensaje;
    }

    public function build()
    {
        return $this->from('mtzmaritz05@gmail.com')
            ->subject('Recordatorio: fecha próxima en documento')
            ->view('emails.fecha_proxima')
            ->with([
                'tipoFecha' => $this->tipoFecha,
                'etapa' => $this->etapa,
                'fecha' => $this->fecha,
                'documento' => $this->documento,
                'designacion' => $this->designacion,
                'mensaje' => $this->mensaje,
            ]);
    }
}