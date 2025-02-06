@extends('adminlte::page')

@section('title', 'Listado de Mangas')

@section('content_header')
    <h1>Listado de Mangas</h1>
@stop

@section('css')
    <style>
        /* Contenedor flex para los botones de acción */
        .acciones-container {
            display: flex;
            gap: 10px; /* Espacio entre botones */
            justify-content: center;
            align-items: center;
        }
    </style>

    <!-- Cargar CSS de DataTables anticipadamente para evitar parpadeos -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- FontAwesome para los iconos -->
    <style>
        #Contenido {
            visibility: hidden;
        }
    </style>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <!-- Botón para crear manga -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                    Crear Manga
                </button>
            </div>
            <div class="card-body">
                <!-- Tabla con ID para aplicar DataTables -->
                <table id="Contenido" class="table table-bordered table-hover dataTable dtr-inline">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Autor</th>
                            <th>Dibujante</th>
                            <th>Género</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th style="width: 80px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mangas as $manga)
                            <tr>
                                <td>{{ $manga->id }}</td>
                                <td>{{ $manga->titulo }}</td>
                                <td>{{ $manga->autor->nombre }} {{ $manga->autor->apellido }}</td>
                                <td>{{ $manga->dibujante->nombre }} {{ $manga->dibujante->apellido }}</td>
                                <td>
                                    @foreach($manga->generos as $genero)
                                        {{ $genero->nombre }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td>{{ \Carbon\Carbon::parse($manga->fecha_inicio)->format('d/m/Y') }}</td>

                                <td>{{ \Carbon\Carbon::parse($manga->fecha_fin)->format('d/m/Y') }}</td>

                                <td class="text-center">
                                    <div class="acciones-container">
                                        <!-- Botón para editar manga -->
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditar" onclick="editarManga(@json($manga))">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <!-- Botón para eliminar manga -->
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar" onclick="configurarEliminar({{ $manga->id }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- Modal para crear manga -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearLabel">Crear Manga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCrear" action="{{ route('mangas.store') }}" method="POST">
                    @csrf <!-- Método HTTP para crear -->
                    <div class="mb-3">
                        <label for="titulo_crear" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo_crear" name="titulo" required>
                    </div>

                    <div class="mb-3">
                        <label for="autor_crear" class="form-label">Autor</label>
                        <select class="form-control" id="autor_crear" name="autor_id" required>
                            @foreach($autores as $autor)
                                <option value="{{ $autor->id }}">{{ $autor->nombre }} {{ $autor->apellido }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dibujante_crear" class="form-label">Dibujante</label>
                        <select class="form-control" id="dibujante_crear" name="dibujante_id" required>
                            @foreach($dibujantes as $dibujante)
                                <option value="{{ $dibujante->id }}">{{ $dibujante->nombre }} {{ $dibujante->apellido }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Géneros</label>
                        <div class="border p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                            <div class="row">
                                @foreach($generos as $index => $genero)
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input genero-checkbox" type="checkbox" id="genero_crear{{ $genero->id }}" name="generos[]" value="{{ $genero->id }}">
                                            <label class="form-check-label" for="genero_crear{{ $genero->id }}">{{ $genero->nombre }}</label>
                                        </div>
                                    </div>
                                    @if(($index + 1) % 4 == 0)
                                        </div><div class="row">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio_crear" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio_crear" name="fecha_inicio" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_fin_crear" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin_crear" name="fecha_fin">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Manga</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  <!-- Modal para editar manga -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Manga</h5>
                <input type="hidden" id="manga_id" name="id">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('mangas.update', $manga->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Método HTTP para actualizar -->
                    <input type="hidden" id="manga_id" name="id"> <!-- ID del manga -->

                    <div class="mb-3">
                        <label for="titulo_editar" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo_editar" name="titulo" required>
                    </div>

                    <div class="mb-3">
                        <label for="autor_editar" class="form-label">Autor</label>
                        <select class="form-control" id="autor_editar" name="autor_id" required>
                            @foreach($autores as $autor)
                                <option value="{{ $autor->id }}">{{ $autor->nombre }} {{ $autor->apellido }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dibujante_editar" class="form-label">Dibujante</label>
                        <select class="form-control" id="dibujante_editar" name="dibujante_id" required>
                            @foreach($dibujantes as $dibujante)
                                <option value="{{ $dibujante->id }}">{{ $dibujante->nombre }} {{ $dibujante->apellido }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Géneros</label>
                        <div class="border p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                            <div class="row">
                                @foreach($generos as $index => $genero)
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input genero-checkbox" type="checkbox" id="genero_editar{{ $genero->id }}" name="generos[]" value="{{ $genero->id }}">
                                            <label class="form-check-label" for="genero_editar{{ $genero->id }}">{{ $genero->nombre }}</label>
                                        </div>
                                    </div>
                                    @if(($index + 1) % 4 == 0)
                                        </div><div class="row">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio_editar" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio_editar" name="fecha_inicio" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_fin_editar" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin_editar" name="fecha_fin">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal para eliminar manga -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarLabel">Eliminar Manga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este manga?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminar" action="" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE') <!-- Aquí está el método DELETE -->
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <!-- Cargar CSS de DataTables anticipadamente para evitar parpadeos -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- FontAwesome para los iconos -->
    <style>
        #Contenido {
            visibility: hidden;
        }
    </style>
@stop
@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            $('#Contenido').DataTable({
                responsive: true,
                order: [[0, 'desc']],
            });

        });
        $('#Contenido').css('visibility', 'visible');

        function editarManga(manga) {
            // Rellenar el formulario con la información de la manga
            $('#manga_id').val(manga.id);
            $('#titulo_editar').val(manga.titulo);
            $('#autor_editar').val(manga.autor_id);
            $('#dibujante_editar').val(manga.dibujante_id);
            manga.generos.forEach(function(genero) {
                $('#genero_editar' + genero.id).prop('checked', true);
            });
            $('#fecha_inicio_editar').val(manga.fecha_inicio);
            $('#fecha_fin_editar').val(manga.fecha_fin || '');
        }
        // Validar fechas al crear el manga
$('#formCrear').on('submit', function(event) {
    // Obtener las fechas de inicio y fin
    var fechaInicio = $('#fecha_inicio_crear').val();
    var fechaFin = $('#fecha_fin_crear').val();

    // Si la fecha de fin fue proporcionada, verificar que sea mayor que la fecha de inicio
    if (fechaFin && new Date(fechaFin) <= new Date(fechaInicio)) {
        event.preventDefault(); // Prevenir que el formulario se envíe
        alert('La fecha de fin debe ser mayor que la fecha de inicio.');
        return false;
    }
});

        function configurarEliminar(mangaId) {
            $('#formEliminar').attr('action', '/mangas/' + mangaId);
        }
    </script>
@stop
