// SidebarFilters.js
import React, { useState, useEffect } from 'react';

const SidebarFilters = ({ onFilterChange }) => {
  // Filtros exclusivos (un solo valor por cada uno)
  const [filters, setFilters] = useState({
    author: null,
    language: null,
    manga: null,
    editorial: null,
    priceRange: [0, 100],
    searchText: '',
  });

  const [availableFilters, setAvailableFilters] = useState({
    authors: [],
    languages: [],
    mangas: [],
    editorials: [],
  });

  // Estados para secciones colapsables
  const [openSections, setOpenSections] = useState({
    authors: true,
    languages: true,
    mangas: true,
    editorials: true,
    price: true,
  });

  // Se solicita al backend los filtros disponibles al montar el componente
  useEffect(() => {
    async function fetchFilters() {
      try {
        const response = await fetch('http://localhost:8000/api/filters');
        const result = await response.json();
        // Se espera que el backend retorne un objeto con arrays de autores, idiomas, mangas y editoriales
        setAvailableFilters(result);
      } catch (error) {
        console.error('Error fetching filters', error);
      }
    }
    fetchFilters();
  }, []);

  // Actualiza el estado de filtros y notifica al componente padre,
  // transformando los filtros exclusivos en arrays y enviando el criterio de ordenamiento.
  const updateFilters = (newFilters) => {
    setFilters(newFilters);
    const transformedFilters = {
      authors: newFilters.author ? [newFilters.author] : [],
      languages: newFilters.language ? [newFilters.language] : [],
      mangas: newFilters.manga ? [newFilters.manga] : [],
      editorials: newFilters.editorial ? [newFilters.editorial] : [],
      priceRange: newFilters.priceRange,
      searchText: newFilters.searchText,
      sortBy: 'titulo,numero_tomo',
    };
    onFilterChange(transformedFilters);
  };

  // Para filtros exclusivos: si se hace clic sobre el mismo valor se deselecciona
  const handleExclusiveChange = (filterType, value) => {
    const current = filters[filterType];
    const updated = current === value ? null : value;
    updateFilters({ ...filters, [filterType]: updated });
  };

  const handlePriceChange = (event) => {
    const { name, value } = event.target;
    let newRange = [...filters.priceRange];
    if (name === 'minPrice') {
      newRange[0] = parseFloat(value);
    } else {
      newRange[1] = parseFloat(value);
    }
    updateFilters({ ...filters, priceRange: newRange });
  };

  const handleSearchTextChange = (event) => {
    updateFilters({ ...filters, searchText: event.target.value });
  };

  const toggleSection = (section) => {
    setOpenSections({ ...openSections, [section]: !openSections[section] });
  };

  return (
    <div
      className="sidebar bg-secondary text-white p-3"
      style={{ width: '200px' }}
    >
      {/* Autores */}
      <div className="mb-3">
        <div className="d-flex justify-content-between align-items-center">
          <h6 className="mb-0">Autores</h6>
          <button
            className="btn btn-sm btn-light"
            onClick={() => toggleSection('authors')}
          >
            {openSections.authors ? '-' : '+'}
          </button>
        </div>
        {openSections.authors && (
          <div>
            {availableFilters.authors.map((author) => (
              <div key={author.id}>
                <input
                  type="radio"
                  id={`author-${author.id}`}
                  name="author"
                  value={author.id}
                  checked={filters.author === author.id}
                  onChange={() => handleExclusiveChange('author', author.id)}
                />
                <label htmlFor={`author-${author.id}`} className="ms-1">
                  {author.nombre} {author.apellido}
                </label>
              </div>
            ))}
          </div>
        )}
      </div>
      <hr />

      {/* Idiomas */}
      <div className="mb-3">
        <div className="d-flex justify-content-between align-items-center">
          <h6 className="mb-0">Idiomas</h6>
          <button
            className="btn btn-sm btn-light"
            onClick={() => toggleSection('languages')}
          >
            {openSections.languages ? '-' : '+'}
          </button>
        </div>
        {openSections.languages && (
          <div>
            {availableFilters.languages.map((language, index) => (
              <div key={index}>
                <input
                  type="radio"
                  id={`language-${language}`}
                  name="language"
                  value={language}
                  checked={filters.language === language}
                  onChange={() => handleExclusiveChange('language', language)}
                />
                <label htmlFor={`language-${language}`} className="ms-1">
                  {language}
                </label>
              </div>
            ))}
          </div>
        )}
      </div>
      <hr />

      {/* Mangas */}
      <div className="mb-3">
        <div className="d-flex justify-content-between align-items-center">
          <h6 className="mb-0">Mangas</h6>
          <button
            className="btn btn-sm btn-light"
            onClick={() => toggleSection('mangas')}
          >
            {openSections.mangas ? '-' : '+'}
          </button>
        </div>
        {openSections.mangas && (
          <div>
            {availableFilters.mangas.map((manga) => (
              <div key={manga.id}>
                <input
                  type="radio"
                  id={`manga-${manga.id}`}
                  name="manga"
                  value={manga.id}
                  checked={filters.manga === manga.id}
                  onChange={() => handleExclusiveChange('manga', manga.id)}
                />
                <label htmlFor={`manga-${manga.id}`} className="ms-1">
                  {manga.titulo}
                </label>
              </div>
            ))}
          </div>
        )}
      </div>
      <hr />

      {/* Editoriales */}
      <div className="mb-3">
        <div className="d-flex justify-content-between align-items-center">
          <h6 className="mb-0">Editoriales</h6>
          <button
            className="btn btn-sm btn-light"
            onClick={() => toggleSection('editorials')}
          >
            {openSections.editorials ? '-' : '+'}
          </button>
        </div>
        {openSections.editorials && (
          <div>
            {availableFilters.editorials.map((editorial) => (
              <div key={editorial.id}>
                <input
                  type="radio"
                  id={`editorial-${editorial.id}`}
                  name="editorial"
                  value={editorial.id}
                  checked={filters.editorial === editorial.id}
                  onChange={() =>
                    handleExclusiveChange('editorial', editorial.id)
                  }
                />
                <label htmlFor={`editorial-${editorial.id}`} className="ms-1">
                  {editorial.nombre}
                </label>
              </div>
            ))}
          </div>
        )}
      </div>
      <hr />

      {/* Rango de precio */}
      <div className="mb-3">
        <div className="d-flex justify-content-between align-items-center">
          <h6 className="mb-0">Precio</h6>
          <button
            className="btn btn-sm btn-light"
            onClick={() => toggleSection('price')}
          >
            {openSections.price ? '-' : '+'}
          </button>
        </div>
        {openSections.price && (
          <div className="d-flex mt-2">
            <input
              type="number"
              name="minPrice"
              value={filters.priceRange[0]}
              onChange={handlePriceChange}
              className="form-control me-2"
              placeholder="Min"
            />
            <input
              type="number"
              name="maxPrice"
              value={filters.priceRange[1]}
              onChange={handlePriceChange}
              className="form-control"
              placeholder="Max"
            />
          </div>
        )}
      </div>
    </div>
  );
};

export default SidebarFilters;
