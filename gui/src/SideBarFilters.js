// SidebarFilters.js
import React, { useState, useEffect } from 'react';

const SidebarFilters = ({ onFilterChange }) => {
  const [filters, setFilters] = useState({
    authors: [],
    languages: [],
    mangas: [],
    editorials: [],
    priceRange: [0, 100],
    searchText: '',
  });

  const [availableFilters, setAvailableFilters] = useState({
    authors: [],
    languages: [],
    mangas: [],
    editorials: [],
  });

  // Al montar el componente, solicitamos al backend los filtros disponibles
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

  // Actualiza el estado de filtros y notifica al componente padre
  const updateFilters = (newFilters) => {
    setFilters(newFilters);
    onFilterChange(newFilters);
  };

  const handleCheckboxChange = (filterType, value) => {
    const current = filters[filterType];
    const updated = current.includes(value)
      ? current.filter(item => item !== value)
      : [...current, value];
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

  return (
    <div className="sidebar bg-secondary text-white p-3" style={{ minWidth: '250px' }}>

      <div className="mb-3">
        <h6>Autores</h6>
        {availableFilters.authors.map(author => (
          <div key={author.id}>
            <input
              type="checkbox"
              id={`author-${author.id}`}
              onChange={() => handleCheckboxChange('authors', author.id)}
              checked={filters.authors.includes(author.id)}
            />
            <label htmlFor={`author-${author.id}`} className="ms-1">{author.nombre}</label>
          </div>
        ))}
      </div>
      <hr />

      <div className="mb-3">
        <h6>Idiomas</h6>
        {availableFilters.languages.map(language => (
          <div key={language}>
            <input
              type="checkbox"
              id={`language-${language}`}
              onChange={() => handleCheckboxChange('languages', language)}
              checked={filters.languages.includes(language)}
            />
            <label htmlFor={`language-${language}`} className="ms-1">{language}</label>
          </div>
        ))}
      </div>
      <hr />

      <div className="mb-3">
        <h6>Mangas</h6>
        {availableFilters.mangas.map(manga => (
          <div key={manga.id}>
            <input
              type="checkbox"
              id={`manga-${manga.id}`}
              onChange={() => handleCheckboxChange('mangas', manga.id)}
              checked={filters.mangas.includes(manga.id)}
            />
            <label htmlFor={`manga-${manga.id}`} className="ms-1">{manga.titulo}</label>
          </div>
        ))}
      </div>
      <hr />

      <div className="mb-3">
        <h6>Editoriales</h6>
        {availableFilters.editorials.map(editorial => (
          <div key={editorial.id}>
            <input
              type="checkbox"
              id={`editorial-${editorial.id}`}
              onChange={() => handleCheckboxChange('editorials', editorial.id)}
              checked={filters.editorials.includes(editorial.id)}
            />
            <label htmlFor={`editorial-${editorial.id}`} className="ms-1">{editorial.nombre}</label>
          </div>
        ))}
      </div>
      <hr />

      <div className="mb-3">
        <h6>Rango de precio</h6>
        <div className="d-flex">
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
      </div>
    </div>
  );
};

export default SidebarFilters;
