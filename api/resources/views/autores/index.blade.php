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
    <!-- Incluir el CSS de DataTables desde CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@stop

@section('js')
    <!-- Incluir el JS de jQuery desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Incluir el JS de DataTables desde CDN -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Inicializar DataTables en la tabla -->
    <script>
        $(document).ready(function() {
            $('#autoresTable').DataTable();
        });
    </script>
@stop
