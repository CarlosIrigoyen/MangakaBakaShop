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

        /* Card de Filtros y Buscador */
        .card-filtros {
            margin-top: 15px;
        }

        /* Estilo para la barra de búsqueda pequeña */
        .search-container {
            display: flex;
            justify-content: flex-start;
        }
        .search-container input {
            width: 250px;
        }

        /* Estilo para los radio buttons de filtros */
        .form-check {
            margin-bottom: 10px;
        }

        /* Línea de separación entre secciones */
        .separator-line {
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        /* Card de Tomos */
        .card-tomo {
            margin-top: 15px;
        }

        /* Ajuste del tamaño de la imagen */
        .card-img-top {
            width: 320px;
            height: 240px;
            object-fit: cover;
        }
    </style>
@stop

@section('content')
<div class="container mt-4">

    <!-- Card Principal que contiene Buscador, Filtros y Tomos -->
    <div class="card">
        <div class="card-body">
            <!-- Barra de búsqueda pequeña -->
            <div class="search-container mb-3">
                <input type="text" class="form-control" placeholder="Buscar por título, autor, etc." id="searchInput">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">Buscar</button>
            </div>
              <!-- Línea de separación entre filtros y tomos -->
              <div class="separator-line"></div>
            <!-- Filtros con radio buttons exclusivos debajo de la barra de búsqueda -->
            <div class="card-filtros">
                <h5>Filtros</h5>
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

            <!-- Línea de separación entre filtros y tomos -->
            <div class="separator-line"></div>

            <!-- Listado de Tomos -->
            <div class="row">
                <!-- Card de Tomo -->
                <div class="col-md-4 mb-4 card-tomo">
                    <div class="card shadow-sm">
                        <img src="https://images.gog-statics.com/56012eb0358a7243a1bd1d9e1590d848a3abeb3e5f6b72329abbb174f3a50196.jpg" class="card-img-top" alt="Portada de Tomo">
                        <div class="card-body">
                            <h5 class="card-title">Tomo #1</h5>
                            <p class="card-text">
                                <strong>Idioma:</strong> Japonés <br>
                                <strong>Autor:</strong> Ejemplo Autor <br>
                                <strong>Manga:</strong> Ejemplo Manga <br>
                                <strong>Editorial:</strong> Ejemplo Editorial
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card de otro Tomo -->
                <div class="col-md-4 mb-4 card-tomo">
                    <div class="card shadow-sm">
                        <img src="https://images.gog-statics.com/56012eb0358a7243a1bd1d9e1590d848a3abeb3e5f6b72329abbb174f3a50196.jpg" class="card-img-top" alt="Portada de Tomo">
                        <div class="card-body">
                            <h5 class="card-title">Tomo #2</h5>
                            <p class="card-text">
                                <strong>Idioma:</strong> Inglés <br>
                                <strong>Autor:</strong> Autor Ejemplo <br>
                                <strong>Manga:</strong> Manga Ejemplo <br>
                                <strong>Editorial:</strong> Editorial Ejemplo
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Más Tomos Se Repetirían Aquí -->
            </div>
        </div>
    </div>
</div>
@endsection

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
</script>
@stop
