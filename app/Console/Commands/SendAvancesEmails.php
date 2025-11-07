<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\FechaProximaMailable;
use App\Models\Documento;

class SendAvancesEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:avances-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía correos de recordatorio dos semanas antes de fechas sin check';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Leer configuración del destinatario y mensaje
        if (!Storage::disk('local')->exists('notifications.json')) {
            $this->info('Sin configuración de correo. Abortando.');
            return Command::SUCCESS;
        }

        $config = json_decode(Storage::disk('local')->get('notifications.json'), true);
        $destinatario = $config['email'] ?? null;
        $mensaje = $config['mensaje'] ?? '';

        if (empty($destinatario)) {
            $this->info('Destinatario vacío. Abortando.');
            return Command::SUCCESS;
        }

        $hoy = Carbon::today();
        $targetDiff = 14; // días

        $documentos = Documento::with(['etapas', 'info', 'eventos'])->get();

        foreach ($documentos as $doc) {
            $etapas = $doc->etapas;
            if (!$etapas) { continue; }

            $eventos = collect($doc->eventos)->pluck('etapa')->toArray();

            // Recorremos todas las fechas registradas
            $map = [
                // Inicio
                '1a' => ['tipo' => 'Fecha de inicio', 'etapa' => 'APT'],
                '1b' => ['tipo' => 'Fecha de inicio', 'etapa' => 'AFT'],
                '1c' => ['tipo' => 'Fecha de inicio', 'etapa' => 'PPT'],
                '1d' => ['tipo' => 'Fecha de inicio', 'etapa' => 'NA'],
                '1e' => ['tipo' => 'Fecha de inicio', 'etapa' => 'NP'],
                // Entrega
                '2a' => ['tipo' => 'Fecha de entrega', 'etapa' => 'APT'],
                '2b' => ['tipo' => 'Fecha de entrega', 'etapa' => 'AFT'],
                '2c' => ['tipo' => 'Fecha de entrega', 'etapa' => 'PPT'],
                '2d' => ['tipo' => 'Fecha de entrega', 'etapa' => 'NA'],
                '2e' => ['tipo' => 'Fecha de entrega', 'etapa' => 'NP'],
                // Terminación
                '3a' => ['tipo' => 'Fecha de terminación', 'etapa' => 'APT'],
                '3b' => ['tipo' => 'Fecha de terminación', 'etapa' => 'AFT'],
                '3c' => ['tipo' => 'Fecha de terminación', 'etapa' => 'PPT'],
                '3d' => ['tipo' => 'Fecha de terminación', 'etapa' => 'NA'],
                '3e' => ['tipo' => 'Fecha de terminación', 'etapa' => 'NP'],
            ];

            foreach ($map as $campo => $info) {
                $fechaStr = $etapas->{$campo} ?? null;
                if (empty($fechaStr)) { continue; }

                // Si ya está con check, no enviar
                if (in_array($campo, $eventos)) { continue; }

                try {
                    $fecha = Carbon::parse($fechaStr)->startOfDay();
                } catch (\Exception $e) {
                    continue;
                }

                $diff = $fecha->diffInDays($hoy, false); // negativo si en futuro
                // queremos: faltan 14 días => fecha - hoy = 14 => diff == -14
                if (($fecha->greaterThanOrEqualTo($hoy)) && ($fecha->diffInDays($hoy) === $targetDiff)) {
                    // Enviar correo
                    $fechaTexto = $fecha->format('d/m/Y');
                    $mailable = new FechaProximaMailable(
                        $info['tipo'],
                        $info['etapa'],
                        $fechaTexto,
                        $doc->nombre ?? '',
                        optional($doc->info)->designacion ?? '',
                        $mensaje
                    );
                    Mail::to($destinatario)->send($mailable);
                }
            }
        }

        $this->info('Proceso de envío finalizado.');
        return Command::SUCCESS;
    }
}