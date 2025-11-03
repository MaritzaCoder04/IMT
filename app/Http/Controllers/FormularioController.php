<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Documento;
use App\Models\DocumentoInfo;
use App\Models\Origen;
use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function create()
    {
        $libros = Libro::select(['ID_libro','desc','clave'])->orderByRaw('`desc`')->get();
        // Cargar tipos (manual/norma), temas y orígenes
        $tipos = \App\Models\Tipo::select(['ID_tipo','desc','clave'])->orderBy('desc')->get();
        $temas = \App\Models\Tema::select(['ID_tema','desc','clave'])->orderBy('desc')->get();
        $origenes = \App\Models\Origen::select(['ID_origen','desc'])->orderBy('desc')->get();
        return view('formulario', compact('libros','tipos','temas','origenes'));
    }

    public function guardar(Request $request)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'tipoDocumento' => 'required',
            'ID_libro' => 'required',
            'tema' => 'required',
            'nombre' => 'required',
            'origen' => 'required',
            'fechaPublicacion' => 'nullable',
            'parte' => 'required|string',
            'titulo' => 'required|string',
            'capitulo' => 'required|string',
        ]);

    // Crear el documento en la base de datos
    $documento = new Documento();
    $documento->nombre = $request->nombre;
    
    // Mapear clave de tipo (ej. 'm'/'n') al ID en tabla tipo
    $tipoClave = $request->tipoDocumento;
    $tipoId = \App\Models\Tipo::where('clave', $tipoClave)->value('ID_tipo');
    $documento->tipo = $tipoId ?? (($tipoClave == 'm') ? 1 : 2);
    
    $documento->libro = $request->ID_libro;
    $documento->origen = $request->origen; // ID_origen en tabla documento
    
    // Guardar tema si viene del formulario; de lo contrario 0
    $documento->tema = (int)($request->tema ?? 0);

    // Parsear parte/título/capítulo del formato "NN. Descripción"
    [$parteNum, $parteDesc] = $this->parseSeccion($request->input('parte'));
    [$tituloNum, $tituloDesc] = $this->parseSeccion($request->input('titulo'));
    [$capituloNum] = $this->parseSeccion($request->input('capitulo'));

    $documento->parte = (int)($parteNum ?? 0);
    $documento->titulo = (int)($tituloNum ?? 0);
    $documento->capitulo = (int)($capituloNum ?? 0);
    
    // Extraer el año de la fecha de publicación
    $anio = $request->fechaPublicacion ?? date('Y');
    $documento->anio = $anio;
    $documento->anio_simple = substr($anio, -2);
    
    // Campos booleanos
    $documento->terracerias = 0;
    $documento->estructuras = 0;
    $documento->drenaje = 0;
    $documento->pavimentos = 0;
    $documento->tuneles = 0;
    $documento->cimentaciones = 0;
    $documento->senalamiento = 0;
    $documento->obras_marginales = 0;
    $documento->SIT = 0;
    $documento->novedades = "0";
    $documento->vigente = 1;
    
    $documento->save();

    // Crear/actualizar registro en documentoinfo para que las vistas muestren descripciones y origen
    try {
        $origenRow = Origen::find($request->origen);
        $origenDesc = $origenRow ? $origenRow->desc : null;

        // Obtener claves para construir la designación
        $tipoClaveDb = \App\Models\Tipo::where('ID_tipo', $documento->tipo)->value('clave');
        $libroClaveDb = Libro::where('ID_libro', $documento->libro)->value('clave');
        $temaClaveDb = \App\Models\Tema::where('ID_tema', $documento->tema)->value('clave');

        // Formatear secciones numéricas (parte sin padding; título 2 dígitos; capítulo 3 dígitos)
        $tituloPad = str_pad((string)($documento->titulo ?? 0), 2, '0', STR_PAD_LEFT);
        $capituloPad = str_pad((string)($documento->capitulo ?? 0), 3, '0', STR_PAD_LEFT);

        $componentes = [
            $tipoClaveDb,
            $libroClaveDb,
            $temaClaveDb,
            $documento->parte,
            $tituloPad,
            $capituloPad
        ];

        // Excluir nulos y ceros no informativos
        $componentesFiltrados = array_filter($componentes, function($v) {
            return !is_null($v) && $v !== '' && $v !== 0 && $v !== '0' && $v !== '00' && $v !== '000';
        });
        $designacionGenerada = implode('-', $componentesFiltrados);

        DocumentoInfo::updateOrCreate(
            ['ID_doc' => $documento->ID_doc],
            [
                'ID_doc' => $documento->ID_doc,
                'nombre' => $documento->nombre,
                'tipo' => (string)$documento->tipo,
                'libro' => (string)$documento->libro,
                'tema' => (string)$documento->tema,
                'parte' => (string)$documento->parte,
                'desc_parte' => $parteDesc,
                'titulo' => (string)$documento->titulo,
                'desc_titulo' => $tituloDesc,
                'capitulo' => (string)$documento->capitulo,
                'designacion' => $designacionGenerada ?: null,
                'origen' => $origenDesc,
                'anio_simple' => $documento->anio_simple,
                'anio' => $documento->anio,
            ]
        );
    } catch (\Exception $e) {
        // No bloquear el flujo si documentoinfo falla; se puede corregir después
    }

    $target = route('controldeavances');
    if ($request->boolean('modal')) {
        return redirect()->to($target.'?modal=1&saved=1')->with('success');
    }
    return redirect()->to($target)->with('success');
    }

    // Extrae número y descripción de una cadena "NN. Texto" o "NN Texto"
    private function parseSeccion(?string $valor): array
    {
        if (!$valor) return [null, null];
        $valor = trim($valor);
        $matches = [];
        if (preg_match('/^\s*(\d+)\s*\.\s*(.*)$/u', $valor, $matches)) {
            $num = $matches[1];
            $desc = trim($matches[2]);
            return [$num, $desc !== '' ? $desc : null];
        }
        if (preg_match('/^(\d+)\s+(.*)$/u', $valor, $matches)) {
            $num = $matches[1];
            $desc = trim($matches[2]);
            return [$num, $desc !== '' ? $desc : null];
        }
        // Si no hay número al inicio, intenta convertir todo a número
        $num = preg_replace('/\D+/', '', $valor);
        return [$num !== '' ? $num : null, null];
    }
}