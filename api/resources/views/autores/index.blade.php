@extends('adminlte::page')

@section('title', 'Listado de Autores')

@section('content_header')
    <h1>Listado de Autores</h1>
@stop

@section('content')
    <p>Bienvenido administrador</p>

    <!-- Aquí añades tu contenedor después del mensaje -->
    <div class="container">
        <!-- Agregar una fila con la tabla -->
        <div class="row">
            <div class="col">
                <!-- Tabla con ID para aplicar DataTables -->
                <table id="autoresTable" class="table table-bordered table-hover dataTable dtr-inline">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($autores as $autor)
                            <tr>
                                <td>{{ $autor->id }}</td>
                                <td>{{ $autor->nombre }}</td>
                                <td>{{ $autor->apellido }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- Cargar CSS de DataTables anticipadamente para evitar parpadeos -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    <!-- Para evitar que la tabla se muestre antes de ser cargada -->
    <style>
        #autoresTable {
            visibility: hidden;
        }
    </style>
@stop

@section('js')
    <!-- Incluir los scripts de DataTables y Bootstrap desde CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

    <!-- Inicializar DataTables en la tabla -->
    <script>
        $(document).ready(function() {
            // Inicializa DataTables después de que todo el contenido esté cargado
            $('#autoresTable').DataTable({
                "responsive": true, // Habilitar la funcionalidad responsive
                "autoWidth": false,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:"
                }
            });

            // Hacer visible la tabla después de que DataTables se haya inicializado
            $('#autoresTable').css('visibility', 'visible');
        });
    </script>
@stop
