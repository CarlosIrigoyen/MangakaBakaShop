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
        /* Tarjeta del tomo individual: ancho 240px, formato "libro" */
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
        /* Footer de cada tarjeta de tomo: botones en columna con separación */
        .card-tomo .card-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            background-color: #fff;
            border-top: 1px solid #e9ecef;
            padding: 10px;
            overflow: visible;
        }
        .card-tomo .card-footer .btn {
            padding: 5px 8px;
        }
        /* Filtros: radio buttons y select dinámico */
        .filtros-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        .filter-select {
            display: none;
            max-width: 250px;
        }
        #searchBar {
            margin-bottom: 15px;
            display: block;
        }
        .separator {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
        /* Paginación personalizada */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
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
    <!-- Formulario de Filtros -->
    <div class="card card-filtros p-3">
        <h5>Filtros</h5>
        <form id="filterForm" method="GET" action="{{ route('tomos.index') }}">
            <!-- Radio buttons para elegir el tipo de filtro -->
            <div class="d-flex flex-wrap gap-3">
                <div class="form-check">
                    <input class="form-check-input filter-radio" type="radio" name="filter_type" value="idioma" id="filterIdioma"
                           {{ request()->get('filter_type') == 'idioma' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterIdioma">Idioma</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-radio" type="radio" name="filter_type" value="autor" id="filterAutor"
                           {{ request()->get('filter_type') == 'autor' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterAutor">Autor</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-radio" type="radio" name="filter_type" value="manga" id="filterManga"
                           {{ request()->get('filter_type') == 'manga' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterManga">Manga</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-radio" type="radio" name="filter_type" value="editorial" id="filterEditorial"
                           {{ request()->get('filter_type') == 'editorial' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterEditorial">Editorial</label>
                </div>
            </div>
            <!-- Contenedor para el select que corresponde al filtro elegido -->
            <div id="filterSelectContainer" class="mt-3">
                <select id="select-idioma" class="form-control filter-select" name="idioma">
                    <option value="">Seleccione un idioma</option>
                    <option value="Español" {{ request()->get('idioma') == 'Español' ? 'selected' : '' }}>Español</option>
                    <option value="Inglés" {{ request()->get('idioma') == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                    <option value="Japonés" {{ request()->get('idioma') == 'Japonés' ? 'selected' : '' }}>Japonés</option>
                </select>
                @php
                    // Extraer autores únicos de los mangas (suponiendo que cada manga tiene un autor relacionado)
                    $autores = [];
                    foreach($mangas as $manga){
                        if(isset($manga->autor)){
                            $autores[$manga->autor->id] = $manga->autor->nombre . ' ' . $manga->autor->apellido;
                        }
                    }
                @endphp
                <select id="select-autor" class="form-control filter-select" name="autor">
                    <option value="">Seleccione un autor</option>
                    @foreach($autores as $id => $nombre)
                        <option value="{{ $id }}" {{ request()->get('autor') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                <select id="select-manga" class="form-control filter-select" name="manga_id">
                    <option value="">Seleccione un manga</option>
                    @foreach($mangas as $manga)
                        <option value="{{ $manga->id }}" {{ request()->get('manga_id') == $manga->id ? 'selected' : '' }}>{{ $manga->titulo }}</option>
                    @endforeach
                </select>
                <select id="select-editorial" class="form-control filter-select" name="editorial_id">
                    <option value="">Seleccione una editorial</option>
                    @foreach($editoriales as $editorial)
                        <option value="{{ $editorial->id }}" {{ request()->get('editorial_id') == $editorial->id ? 'selected' : '' }}>{{ $editorial->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>
    </div>
    <div class="mb-3 text-start">
        <button type="button" class="btn btn-success btn-crear-tomo" data-bs-toggle="modal" data-bs-target="#modalCrearTomo">
            Crear Tomo
        </button>
    </div>

    <!-- Mensaje cuando no se ha seleccionado ningún filtro o búsqueda -->
    @if( ! request()->has('filter_type') && ! request()->filled('search') )
        <div class="alert alert-info text-center">
            Por favor, seleccione un filtro o realice una búsqueda para listar los tomos.
        </div>
    @endif

    <!-- Listado de Tomos (se muestra cuando hay filtro o búsqueda) -->
    @if(request()->has('filter_type') || request()->filled('search'))
        @if($tomos->total() == 0)
            <div class="alert alert-warning text-center">
                No se encontraron tomos para los filtros o búsqueda aplicados.
            </div>
        @else
            <!-- Card contenedor para los tomos -->
            <div class="card">
                <div class="card-body">
                    <div class="row" id="tomoList">
                        @foreach($tomos as $tomo)
                            <div class="col-md-4">
                                <div class="card card-tomo">
                                    <img src="{{ asset($tomo->portada) }}" alt="Portada">
                                    <div class="card-footer">
                                        <!-- Botón para abrir el modal de información -->
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalInfo-{{ $tomo->id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <!-- Botón para abrir el modal de edición -->
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $tomo->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Botón para abrir el modal de eliminación -->
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $tomo->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Modal de Información -->
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
                                <!-- Modal de Edición -->
                                <div class="modal fade" id="modalEdit-{{ $tomo->id }}" tabindex="-1" aria-labelledby="modalEditLabel-{{ $tomo->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEditLabel-{{ $tomo->id }}">Editar Tomo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('tomos.update', $tomo->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- Campo oculto para preservar la URL actual -->
                                                    <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                                                    <div class="mb-3">
                                                        <label for="manga_id_{{ $tomo->id }}" class="form-label">Manga</label>
                                                        <!-- Mostramos el manga en un select deshabilitado y enviamos su valor mediante un hidden -->
                                                        <select class="form-select" id="manga_id_{{ $tomo->id }}" disabled>
                                                            @foreach($mangas as $manga)
                                                                <option value="{{ $manga->id }}" {{ $manga->id == $tomo->manga_id ? 'selected' : '' }}>{{ $manga->titulo }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="manga_id" value="{{ $tomo->manga_id }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="editorial_id_{{ $tomo->id }}" class="form-label">Editorial</label>
                                                        <select class="form-select" name="editorial_id" id="editorial_id_{{ $tomo->id }}" required>
                                                            <option value="">Seleccione una editorial</option>
                                                            @foreach($editoriales as $editorial)
                                                                <option value="{{ $editorial->id }}" {{ $editorial->id == $tomo->editorial_id ? 'selected' : '' }}>{{ $editorial->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="formato_{{ $tomo->id }}" class="form-label">Formato</label>
                                                        <select class="form-select" name="formato" id="formato_{{ $tomo->id }}" required>
                                                            <option value="">Seleccione un formato</option>
                                                            <option value="Tankōbon" {{ $tomo->formato == 'Tankōbon' ? 'selected' : '' }}>Tankōbon</option>
                                                            <option value="Aizōban" {{ $tomo->formato == 'Aizōban' ? 'selected' : '' }}>Aizōban</option>
                                                            <option value="Kanzenban" {{ $tomo->formato == 'Kanzenban' ? 'selected' : '' }}>Kanzenban</option>
                                                            <option value="Bunkoban" {{ $tomo->formato == 'Bunkoban' ? 'selected' : '' }}>Bunkoban</option>
                                                            <option value="Wideban" {{ $tomo->formato == 'Wideban' ? 'selected' : '' }}>Wideban</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="idioma_{{ $tomo->id }}" class="form-label">Idioma</label>
                                                        <select class="form-select" name="idioma" id="idioma_{{ $tomo->id }}" required>
                                                            <option value="">Seleccione un idioma</option>
                                                            <option value="Español" {{ $tomo->idioma == 'Español' ? 'selected' : '' }}>Español</option>
                                                            <option value="Inglés" {{ $tomo->idioma == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                                                            <option value="Japonés" {{ $tomo->idioma == 'Japonés' ? 'selected' : '' }}>Japonés</option>
                                                        </select>
                                                    </div>
                                                    <!-- Número de Tomo (no editable) -->
                                                    <div class="mb-3">
                                                        <label for="numero_tomo_{{ $tomo->id }}" class="form-label">Número de Tomo</label>
                                                        <input type="number" class="form-control" id="numero_tomo_{{ $tomo->id }}" name="numero_tomo" value="{{ $tomo->numero_tomo }}" readonly>
                                                    </div>
                                                    <!-- Fecha de Publicación -->
                                                    <div class="mb-3">
                                                        <label for="fecha_publicacion_{{ $tomo->id }}" class="form-label">Fecha de Publicación</label>
                                                        <input type="date" class="form-control" name="fecha_publicacion" id="fecha_publicacion_{{ $tomo->id }}" value="{{ $tomo->fecha_publicacion }}" required max="{{ date('Y-m-d') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="precio_{{ $tomo->id }}" class="form-label">Precio</label>
                                                        <input type="number" step="0.01" class="form-control" name="precio" id="precio_{{ $tomo->id }}" value="{{ $tomo->precio }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="portada_{{ $tomo->id }}" class="form-label">Portada (Dejar en blanco para mantener la actual)</label>
                                                        <input type="file" class="form-control" name="portada" id="portada_{{ $tomo->id }}">
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
                                <!-- Modal de Eliminación -->
                                <div class="modal fade" id="modalDelete-{{ $tomo->id }}" tabindex="-1" aria-labelledby="modalDeleteLabel-{{ $tomo->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalDeleteLabel-{{ $tomo->id }}">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas eliminar este tomo?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('tomos.destroy', $tomo->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Paginación personalizada -->
            <div class="separator"></div>
            <div class="pagination-container">
                <button class="btn btn-light"
                        onclick="window.location.href='{{ $tomos->previousPageUrl() }}'"
                        {{ $tomos->onFirstPage() ? 'disabled' : '' }}>
                    &laquo; Anterior
                </button>
                <span id="pageIndicator">
                    Página {{ $tomos->currentPage() }} / {{ $tomos->lastPage() }}
                </span>
                <button class="btn btn-light"
                        onclick="window.location.href='{{ $tomos->nextPageUrl() }}'"
                        {{ $tomos->currentPage() == $tomos->lastPage() ? 'disabled' : '' }}>
                    Siguiente &raquo;
                </button>
            </div>
        @endif
    @endif
</div>

<!-- Modal Crear Tomo -->
<div class="modal fade" id="modalCrearTomo" tabindex="-1" aria-labelledby="modalCrearTomoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCrearTomoLabel">Crear Tomo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('tomos.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
          <div class="mb-3">
            <label for="manga_id" class="form-label">Manga</label>
            <select class="form-select" name="manga_id" id="manga_id" required>
              <option value="">Seleccione un manga</option>
              @foreach($mangas as $manga)
                <option value="{{ $manga->id }}">{{ $manga->titulo }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="editorial_id" class="form-label">Editorial</label>
            <select class="form-select" name="editorial_id" id="editorial_id" required>
              <option value="">Seleccione una editorial</option>
              @foreach($editoriales as $editorial)
                <option value="{{ $editorial->id }}">{{ $editorial->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="formato" class="form-label">Formato</label>
            <select class="form-select" name="formato" id="formato" required>
              <option value="">Seleccione un formato</option>
              <option value="Tankōbon">Tankōbon</option>
              <option value="Aizōban">Aizōban</option>
              <option value="Kanzenban">Kanzenban</option>
              <option value="Bunkoban">Bunkoban</option>
              <option value="Wideban">Wideban</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="idioma" class="form-label">Idioma</label>
            <select class="form-select" name="idioma" id="idioma" required>
              <option value="">Seleccione un idioma</option>
              <option value="Español">Español</option>
              <option value="Inglés">Inglés</option>
              <option value="Japonés">Japonés</option>
            </select>
          </div>
          <!-- Campo Número de Tomo (no editable) -->
          <div class="mb-3">
            <label for="numero_tomo" class="form-label">Número de Tomo</label>
            <input type="number" class="form-control" id="numero_tomo" name="numero_tomo" readonly>
          </div>
          <!-- Campo Fecha de Publicación -->
          <div class="mb-3">
            <label for="fecha_publicacion" class="form-label">Fecha de Publicación</label>
            <input type="date" class="form-control" name="fecha_publicacion" id="fecha_publicacion" required>
          </div>
          <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" name="precio" id="precio" required>
          </div>
          <div class="mb-3">
            <label for="portada" class="form-label">Portada</label>
            <input type="file" class="form-control" name="portada" id="portada" required>
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

@stop

@section('js')
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            // Manejo de la visibilidad de los selects según el radio seleccionado
            $('.filter-radio').on('change', function(){
                var filterType = $(this).val();
                $('.filter-select').hide().prop('disabled', true);
                $('#filterSelectContainer').show();
                if(filterType == 'idioma'){
                    $('#select-idioma').show().prop('disabled', false);
                } else if(filterType == 'autor'){
                    $('#select-autor').show().prop('disabled', false);
                } else if(filterType == 'manga'){
                    $('#select-manga').show().prop('disabled', false);
                } else if(filterType == 'editorial'){
                    $('#select-editorial').show().prop('disabled', false);
                }
            });

            var currentFilter = "{{ request()->get('filter_type') }}";
            if(currentFilter){
                $('.filter-radio[value="'+currentFilter+'"]').prop('checked', true);
                $('#filterSelectContainer').show();
                if(currentFilter == 'idioma'){
                    $('#select-idioma').show().prop('disabled', false);
                } else if(currentFilter == 'autor'){
                    $('#select-autor').show().prop('disabled', false);
                } else if(currentFilter == 'manga'){
                    $('#select-manga').show().prop('disabled', false);
                } else if(currentFilter == 'editorial'){
                    $('#select-editorial').show().prop('disabled', false);
                }
            }

            // Variable con los datos para calcular el siguiente número de tomo y la fecha mínima (para el modal de creación).
            var nextTomos = @json($nextTomos);
            $('#manga_id').on('change', function(){
                var mangaId = $(this).val();
                var today = new Date().toISOString().split("T")[0];
                if(mangaId && nextTomos[mangaId]){
                    var data = nextTomos[mangaId];
                    $('#numero_tomo').val(data.numero);
                    if(data.numero > 1){
                        $('#fecha_publicacion').val(data.fecha);
                        $('#fecha_publicacion').attr('min', data.fecha);
                        $('#fecha_publicacion').attr('max', today);
                    } else {
                        $('#numero_tomo').val(1);
                        $('#fecha_publicacion').val('');
                        $('#fecha_publicacion').removeAttr('min');
                        $('#fecha_publicacion').attr('max', today);
                    }
                } else {
                    $('#numero_tomo').val('');
                    $('#fecha_publicacion').val('');
                    $('#fecha_publicacion').removeAttr('min');
                    $('#fecha_publicacion').attr('max', today);
                }
            });
        });
    </script>
@stop
