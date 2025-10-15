<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
    <body>
        <a href="{{ route('exportar.sql') }}" class="btn btn-success">Exportar a SQL</a>

        <div class="all-form"> 
            <div class="form-container">
                <div class="docs-container">
                    <div class="docs-table-wrapper">
                        <table class="docs-table">
                            <thead>
                                <tr>
                                    <th>Norma/Manual</th>
                                    <th>Libro</th>
                                    <th>Tema</th>
                                    <th>Parte</th>
                                    <th></th>
                                    <th>Título</th>
                                    <th></th>
                                    <th>Capítulo</th>
                                    <th>Designación</th>
                                    <th>Nombre</th>
                                    <th>Origen</th>
                                    <th>Ano Simple</th>
                                    <th>Año</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentosProcesados as $documento)
                                <tr>
                                    <td>{{ $documento->tipo }}</td>
                                    <td>{{ $documento->libro }}</td>
                                    <td>{{ $documento->tema }}</td>
                                    <td>{{ $documento->parte }}</td>
                                    {{-- <td>{{ $documento->claveparte}}</td> --}}
                                    <td>{{ $documento->desc_parte}}</td>
                                    <td>{{ $documento->titulo }}</td>
                                    {{-- <td>{{ $documento->clavetitulo}}</td> --}}
                                    <td>{{ $documento->desc_titulo}}</td>
                                    <td>{{ $documento->capitulo }}</td>
                                    <td>{{ $documento->designacion ?? '--' }}</td>
                                    <td>{{ $documento->nombre ?? '--' }}</td>
                                    <td>{{ $documento->origen ?? '--' }}</td>
                                    <td>{{ $documento->anio_simple ?? '--' }}</td>
                                    <td>{{ $documento->anio ?? '--' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>