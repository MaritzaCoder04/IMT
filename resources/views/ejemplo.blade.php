@extends('plantillasvistas')
@section('contenido')

    <style>
        .tr-dcheck{
            background: rgba(165, 42, 42, 0.279);
        }
        .tr-check{
            background: rgba(0, 128, 0, 0.279);
        }
    </style>
    <title>Inspección</title>

    <div class="title-content">
        Inspección de bienes
    </div>
    <br>
    {{-- <p>Este es el contenido principal de la página.</p> --}}
    <div class="div-fila-alt-start">
        <input type="text" id="filtro-tabla" placeholder="Buscar...">
    </div>
    <br>
    <div class="content-info">
        <table class="responsive-table" id="tabla-gen">
            <thead>
                <tr>
                    <th>Número de contrato</th>
                    <th>Pedido de entrada</th>
                    <th>Número de factura</th>
                    <th>Fecha de adquisición</th>
                    <th>Área solicitante </th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CT-00123</td>
                    <td>PE-45678</td>
                    <td>FAC-78901</td>
                    <td>2025-08-15</td>
                    <td>Recursos Humanos</td>
                    <td>Proveedor A S.A.</td>
                    <td><button>Ver</button> <button>Editar</button></td>
                </tr>
                <tr>
                    <td>CT-00124</td>
                    <td>PE-45679</td>
                    <td>FAC-78902</td>
                    <td>2025-08-20</td>
                    <td>TI</td>
                    <td>Proveedor B Ltda.</td>
                    <td><button>Ver</button> <button>Editar</button></td>
                </tr>
                <tr>
                    <td>CT-00125</td>
                    <td>PE-45680</td>
                    <td>FAC-78903</td>
                    <td>2025-08-22</td>
                    <td>Finanzas</td>
                    <td>Proveedor C S.A.</td>
                    <td><button>Ver</button> <button>Editar</button></td>
                </tr>
                <tr>
                    <td>CT-00126</td>
                    <td>PE-45681</td>
                    <td>FAC-78904</td>
                    <td>2025-08-25</td>
                    <td>Logística</td>
                    <td>Proveedor D</td>
                    <td><button>Ver</button> <button>Editar</button></td>
                </tr>
                <tr>
                    <td>CT-00127</td>
                    <td>PE-45682</td>
                    <td>FAC-78905</td>
                    <td>2025-08-30</td>
                    <td>Compras</td>
                    <td>Proveedor E</td>
                    <td><button>Ver</button> <button>Editar</button></td>
                </tr>
            </tbody>
        </table>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filtroInput = document.getElementById('filtro-tabla');
        const tabla = document.getElementById('tabla-gen').getElementsByTagName('tbody')[0];

        filtroInput.addEventListener('input', function () {
            const texto = this.value.toLowerCase();
            const filas = tabla.getElementsByTagName('tr');

            Array.from(filas).forEach(fila => {
                const textoFila = fila.textContent.toLowerCase();
                fila.style.display = textoFila.includes(texto) ? '' : 'none';
            });
        });
    });
</script>

@endsection
