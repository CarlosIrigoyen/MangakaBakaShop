@extends('adminlte::page')

@section('title', 'Listado de Mangas')

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
        .card-tomo {
            width: 240px;
            height: 400px;
            margin: 15px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-tomo img {
            width: 100%;
            height: 60%;
            object-fit: cover;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100%;
        }
        .card-title {
            font-weight: bold;
            margin: 10px 0 5px;
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
        .pagination a, .pagination button {
            text-decoration: none;
            padding: 8px 12px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }
        .pagination .active a, .pagination .active button {
            background-color: #007bff;
            color: white;
        }
        .pagination .disabled a, .pagination .disabled button {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
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

        <!-- Barra de búsqueda (ahora visible) -->
        <div class="mb-3">
            <input type="text" class="form-control" id="searchBar" placeholder="Buscar...">
        </div>

        <!-- Línea separadora entre filtros y la búsqueda -->
        <div class="separator"></div>

        <!-- Listado de Tomos -->
        <div class="row" id="tomoList">
            <!-- Cards de tomos, estos deben ser llenados dinámicamente -->
        </div>

        <!-- Línea separadora entre filtros y listado de tomos -->
        <div class="separator"></div>

        <!-- Paginación (botones de anterior y siguiente) -->
        <div class="pagination-container">
            <ul class="pagination">
                <li class="page-item disabled">
                    <button class="page-link">Anterior</button>
                </li>
                <li class="page-item active">
                    <button class="page-link">1</button>
                </li>
                <li class="page-item">
                    <button class="page-link">2</button>
                </li>
                <li class="page-item">
                    <button class="page-link">3</button>
                </li>
                <li class="page-item">
                    <button class="page-link">Siguiente</button>
                </li>
            </ul>
        </div>
    </div>
</div>
@stop

@section('js')
    <!-- Archivos JS (sin funcionalidad de búsqueda o filtros, solo carga visual) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            let tomos = [
                { id: 1, nombre: 'Tomo #1', idioma: 'Japonés', autor: 'Ejemplo Autor', manga: 'Ejemplo Manga', editorial: 'Ejemplo Editorial', precio: '$15.00', imagen: 'https://images.gog-statics.com/56012eb0358a7243a1bd1d9e1590d848a3abeb3e5f6b72329abbb174f3a50196.jpg' },
                { id: 2, nombre: 'Tomo #2', idioma: 'Inglés', autor: 'Autor Ejemplo', manga: 'Manga Ejemplo', editorial: 'Editorial Ejemplo', precio: '$20.00', imagen: 'https://images.gog-statics.com/56012eb0358a7243a1bd1d9e1590d848a3abeb3e5f6b72329abbb174f3a50196.jpg' },
                { id: 3, nombre: 'Tomo #3', idioma: 'Español', autor: 'Autor Prueba', manga: 'Manga Prueba', editorial: 'Editorial Prueba', precio: '$18.50', imagen: 'https://images.gog-statics.com/56012eb0358a7243a1bd1d9e1590d848a3abeb3e5f6b72329abbb174f3a50196.jpg' }
            ];

            function renderTomos(tomos) {
                let html = '';
                tomos.forEach(tomo => {
                    html += `
                        <div class="col-md-4">
                            <div class="card card-tomo">
                                <img src="${tomo.imagen}" alt="Portada de Tomo">
                                <div class="card-body">
                                    <h5 class="card-title">${tomo.nombre}</h5>
                                    <p class="card-text">
                                        <strong>Idioma:</strong> ${tomo.idioma} <br>
                                        <strong>Autor:</strong> ${tomo.autor} <br>
                                        <strong>Manga:</strong> ${tomo.manga} <br>
                                        <strong>Editorial:</strong> ${tomo.editorial} <br>
                                        <strong>Precio:</strong> ${tomo.precio}
                                    </p>
                                    <div class="btn-group">
                                        <button class="btn btn-primary me-2">Editar</button>
                                        <button class="btn btn-danger">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#tomoList').html(html);
            }

            // Inicializa el listado de tomos
            renderTomos(tomos);

            // Filtro y búsqueda
            $('#searchBar').on('keyup', function() {
                let searchQuery = $(this).val().toLowerCase();
                let filteredTomos = tomos.filter(tomo => {
                    return tomo.nombre.toLowerCase().includes(searchQuery) ||
                           tomo.idioma.toLowerCase().includes(searchQuery) ||
                           tomo.autor.toLowerCase().includes(searchQuery) ||
                           tomo.manga.toLowerCase().includes(searchQuery) ||
                           tomo.editorial.toLowerCase().includes(searchQuery);
                });
                renderTomos(filteredTomos);
            });
        });
    </script>
@stop
