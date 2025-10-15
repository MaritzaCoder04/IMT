<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\db;

use App\Models\Documento;
use App\Models\Parte;
USE App\Models\Titulo;
use App\Models\Tema;
USE App\Models\Tipo;
use App\Models\Libro;
use Illuminate\Support\Facades\Redis;

class DbcontrollerVistas extends Controller
{
    public function Inicio()
    {
        $documentosProcesados = Documento::all();
        $partes = Parte::all()->keyBy('ID_parte'); // Indexamos por ID_parte para búsqueda rápida
        $titulo = Titulo::all()->keyBy('ID_titulo');
        $tipo = Tipo::all()->keyBy('ID_tipo'); // Indexamos por ID_parte para búsqueda rápida
        $libro = Libro::all()->keyBy('ID_libro');
        $tema = Tema::all()->keyBy('ID_tema');

        foreach ($documentosProcesados as $doc) {
            // Formatear tema y parte a 2 dígitos
            $temafo = str_pad($doc->tema, 2, '0', STR_PAD_LEFT);
            $partefo = str_pad($doc->parte, 2, '0', STR_PAD_LEFT);
            $titulofo = str_pad($doc->titulo, 2, '0', STR_PAD_LEFT);
            $capitulofo = str_pad($doc->capitulo, 3, '0', STR_PAD_LEFT);
            // Construimos la claveparte
            $doc->claveparte = $doc->libro.$temafo.$partefo;
            $doc->clavetitulo = $doc->libro.$temafo.$partefo.$titulofo;

            // Buscamos si existe en la colección de partes

            if ($partes->has($doc->claveparte)) {
                $doc->desc_parte = $partes[$doc->claveparte]->desc;
            } else {
                $doc->desc_parte = null;
            }

            if ($titulo->has($doc->clavetitulo)) {
                $doc->desc_titulo = $titulo[$doc->clavetitulo]->desc;
            } else {
                $doc->desc_titulo = null;
            }

            if ($tipo->has($doc->tipo)) {
                $doc->clavetipo = $tipo[$doc->tipo]->clave;
            } else {
                $doc->clavetipo = null;
            }

            if ($libro->has($doc->libro)) {
                $doc->clavelibro = $libro[$doc->libro]->clave;
            } else {
                $doc->clavelibro = null;
            }

            if ($tema->has($doc->tema)) {
                $doc->clavetema = $tema[$doc->tema]->clave;
            } else {
                $doc->clavetema = null;
            }

            $doc->origen = 'IMT';
            
            $componentes = [
                $doc->clavetipo,
                $doc->clavelibro,
                $doc->clavetema,
                $doc->parte,
                $titulofo,
                $capitulofo
            ];

            // Filtrar valores nulos o 0 (también puedes usar === '' si quieres excluir vacíos)
            $componentes_filtrados = array_filter($componentes, function($valor) {
                return !is_null($valor) && $valor !== 0 && $valor !== '0' && $valor !== '00' && $valor !== 00 && $valor !== '000' && $valor !== 000;
            });

            // Unir con guiones
            $doc->designacion = implode('-', $componentes_filtrados);
        }

        return view('Inicio', compact('documentosProcesados'));
    }

    public function exportarSQL()
    {
            $documentos = Documento::all();
            $partes = Parte::all()->keyBy('ID_parte');
            $titulo = Titulo::all()->keyBy('ID_titulo');
            $tipo = Tipo::all()->keyBy('ID_tipo');
            $libro = Libro::all()->keyBy('ID_libro');
            $tema = Tema::all()->keyBy('ID_tema');

            $sql = "-- Exportación de documentos\n\n";

            foreach ($documentos as $doc) {
                // Claves formateadas
                $temafo = str_pad($doc->tema, 2, '0', STR_PAD_LEFT);
                $partefo = str_pad($doc->parte, 2, '0', STR_PAD_LEFT);
                $titulofo = str_pad($doc->titulo, 2, '0', STR_PAD_LEFT);
                $capitulofo = str_pad($doc->capitulo, 3, '0', STR_PAD_LEFT);
                $claveparte = $doc->libro.$temafo.$partefo;
                $clavetitulo = $doc->libro.$temafo.$partefo.$titulofo;

                $desc_parte = $partes->has($claveparte) ? $partes[$claveparte]->desc : null;
                $desc_titulo = $titulo->has($clavetitulo) ? $titulo[$clavetitulo]->desc : null;
                $clavetipo = $tipo->has($doc->tipo) ? $tipo[$doc->tipo]->clave : null;
                $clavelibro = $libro->has($doc->libro) ? $libro[$doc->libro]->clave : null;
                $clavetema = $tema->has($doc->tema) ? $tema[$doc->tema]->clave : null;

                $origen = 'IMT';
                $componentes = [
                    $clavetipo,
                    $clavelibro,
                    $clavetema,
                    $doc->parte,
                    $titulofo,
                    $capitulofo
                ];
                $componentes_filtrados = array_filter($componentes, fn($v) => !is_null($v) && $v !== '0' && $v !== '00' && $v !== '000');
                $designacion = implode('-', $componentes_filtrados);

                // Crear sentencia SQL
                $sql .= "INSERT INTO documentoinfo (ID_doc, nombre, tipo, libro, tema, parte, desc_parte, titulo, desc_titulo, capitulo, designacion, origen, anio_simple, anio) VALUES ("
                    . "'" . addslashes($doc->ID_doc ) . "', "
                    . "'" . addslashes($doc->nombre ) . "', "
                    . "'" . addslashes($doc->tipo) . "', "
                    . "'" . addslashes($doc->libro) . "', "
                    . "'" . addslashes($doc->tema) . "', "
                    . "'" . addslashes($doc->parte) . "', "
                    . "'" . addslashes($desc_parte ?? '') . "', "
                    . "'" . addslashes($doc->titulo) . "', "
                    . "'" . addslashes($desc_titulo ?? '') . "', "
                    . "'" . addslashes($doc->capitulo) . "', "
                    . "'" . addslashes($designacion) . "', "
                    . "'" . addslashes($origen) . "', "
                    . "'" . addslashes($doc->anio_simple ?? '') . "', "
                    . "'" . addslashes($doc->anio ?? '') . "'"
                    . ");\n";
            }

            // Retornar como archivo descargable
            return response($sql)
                ->header('Content-Type', 'text/sql')
                ->header('Content-Disposition', 'attachment; filename="documentos_export.sql"');
    }


}
