@extends('adminlte::page')

@section('title', 'Listado de Tomos')

@section('content_header')
    <h1>Listado De Tomos</h1>
@stop

@section('css')
    <!-- Archivos de estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .card-filtros {
            margin-bottom: 20px;
        }
        .form-check {
            margin-bottom: 10px;
        }
        /* Tarjeta del tomo con ancho revertido a 240px para mantener el formato original (más alto que ancho) */
        .card-tomo {
            width: 240px;
            margin: 15px auto;
            border-radius: 10px;
            overflow: visible;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-tomo img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        /* Footer con botones dispuestos verticalmente y con separación entre cada botón */
        .card-tomo .card-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px; /* Separación vertical entre botones */
            background-color: #fff;
            border-top: 1px solid #e9ecef;
            padding: 10px;
            overflow: visible;
        }
        /* Opcional: ajustar padding interno de los botones si se requiere */
        .card-tomo .card-footer .btn {
            padding: 5px 8px;
        }
        .filtros-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
        }
        #searchBar {
            margin-bottom: 15px;
            display: block;
        }
        .separator {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
        .btn-group {
            margin-top: 15px;
            width: 100%;
            justify-content: space-between;
        }
        .btn {
            width: 45%;
        }
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination {
            list-style: none;
            padding: 0;
            display: flex;
        }
        .pagination li {
            margin: 0 5px;
        }
        .pagination a,
        .pagination button {
            text-decoration: none;
            padding: 8px 12px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }
        .pagination .active a,
        .pagination .active button {
            background-color: #007bff;
            color: white;
        }
        .pagination .disabled a,
        .pagination .disabled button {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }
        .btn-crear-tomo {
            width: auto;
            padding: 8px 20px;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
        }
        .btn-crear-tomo:hover {
            background-color: #0056b3;
        }
    </style>
@stop

@section('content')
<div class="container">
    <!-- Card con filtros, búsqueda y listado de tomos -->
    <div class="card card-filtros p-3">
        <h5>Filtros</h5>
        <div class="filtros-container">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="filter" value="idioma" id="filterIdioma">
                <label class="form-check-label" for="filterIdioma">Idioma</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="filter" value="autor" id="filterAutor">
                <label class="form-check-label" for="filterAutor">Autor</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="filter" value="manga" id="filterManga">
                <label class="form-check-label" for="filterManga">Manga</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="filter" value="editorial" id="filterEditorial">
                <label class="form-check-label" for="filterEditorial">Editorial</label>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <div class="mb-3">
            <input type="text" class="form-control" id="searchBar" placeholder="Buscar...">
        </div>
        <div class="card-header d-flex justify-content-between align-items-center">
            <!-- Botón para crear tomo -->
            <button type="button" class="btn btn-primary btn-crear-tomo" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Crear Tomo
            </button>
        </div>

        <!-- Línea separadora -->
        <div class="separator"></div>

        <!-- Modal para agregar un nuevo tomo -->
        <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="crearTomoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearTomoModalLabel">Crear Nuevo Tomo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para crear un tomo -->
                        <form id="formCrearTomo" action="{{ route('tomos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="manga_id" class="form-label">Manga</label>
                                <select class="form-control" id="manga_id" name="manga_id" required>
                                    <option value="">Seleccione un manga</option>
                                    @foreach($mangas as $manga)
                                        <option value="{{ $manga->id }}">{{ $manga->titulo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="numero_tomo" class="form-label">Número de Tomo</label>
                                <input type="number" class="form-control" id="numero_tomo" name="numero_tomo" required>
                            </div>
                            <div class="mb-3">
                                <label for="editorial_id" class="form-label">Editorial</label>
                                <select class="form-control" id="editorial_id" name="editorial_id" required>
                                    <option value="">Seleccione una editorial</option>
                                    @foreach($editoriales as $editorial)
                                        <option value="{{ $editorial->id }}">{{ $editorial->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="idioma" class="form-label">Idioma</label>
                                <select class="form-control" id="idioma" name="idioma" required>
                                    <option value="">Seleccione un idioma</option>
                                    <option value="Español">Español</option>
                                    <option value="Inglés">Inglés</option>
                                    <option value="Japonés">Japonés</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="formato" class="form-label">Formato</label>
                                <select class="form-control" id="formato" name="formato" required>
                                    <option value="">Seleccione un formato</option>
                                    <option value="Tankōbon">Tankōbon</option>
                                    <option value="Aizōban">Aizōban</option>
                                    <option value="Kanzenban">Kanzenban</option>
                                    <option value="Bunkoban">Bunkoban</option>
                                    <option value="Wideban">Wideban</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio</label>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_publicacion" class="form-label">Fecha de Publicación</label>
                                <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" required>
                            </div>
                            <div class="mb-3">
                                <label for="portada" class="form-label">Portada</label>
                                <input type="file" class="form-control" id="portada" name="portada" accept="image/*" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Crear Tomo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin del modal de creación -->

        <!-- Listado de Tomos -->
        <div class="row" id="tomoList">
            @foreach($tomos as $tomo)
                <div class="col-md-4">
                    <div class="card card-tomo">
                        <img src="{{ asset($tomo->portada) }}" alt="Portada">
                        <!-- Footer con botones dispuestos verticalmente -->
                        <div class="card-footer">
                            <!-- Botón de Info: abre modal con detalles -->
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalInfo-{{ $tomo->id }}">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <!-- Botón de Editar -->
                            <a href="{{ route('tomos.edit', $tomo->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Botón de Eliminar -->
                            <form action="{{ route('tomos.destroy', $tomo->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este tomo?');"class="d-inline">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal de Información del Tomo -->
                    <div class="modal fade" id="modalInfo-{{ $tomo->id }}" tabindex="-1" aria-labelledby="modalInfoLabel-{{ $tomo->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalInfoLabel-{{ $tomo->id }}">Detalles del Tomo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <h5>{{ $tomo->manga->titulo }} - Tomo {{ $tomo->numero_tomo }}</h5>
                                    <p><strong>Editorial:</strong> {{ $tomo->editorial->nombre ?? 'N/A' }}</p>
                                    <p><strong>Formato:</strong> {{ $tomo->formato }}</p>
                                    <p><strong>Idioma:</strong> {{ $tomo->idioma }}</p>
                                    <p><strong>Precio:</strong> ${{ $tomo->precio }}</p>
                                    <p><strong>Autor:</strong>
                                        @if(isset($tomo->manga->autor))
                                            {{ $tomo->manga->autor->nombre }} {{ $tomo->manga->autor->apellido }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <p><strong>Dibujante:</strong>
                                        @if(isset($tomo->manga->dibujante))
                                            {{ $tomo->manga->dibujante->nombre }} {{ $tomo->manga->dibujante->apellido }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <p><strong>Géneros:</strong>
                                        @if(isset($tomo->manga->generos) && $tomo->manga->generos->isNotEmpty())
                                            @foreach($tomo->manga->generos as $genero)
                                                {{ $genero->nombre }}@if(!$loop->last), @endif
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="separator"></div>
        <div class="pagination-container">
            {{ $tomos->links() }}
        </div>
    </div>
</div>
@stop

@section('js')
    <!-- Archivos de scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializa plugins o agrega scripts personalizados aquí si es necesario.
        });
    </script>
@stop
