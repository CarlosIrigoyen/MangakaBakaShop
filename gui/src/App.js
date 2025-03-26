// App.js
import React, { useState, useEffect } from 'react';
import { Navbar, Container, Form, Button, Modal, Alert } from 'react-bootstrap';
import SidebarFilters from './SideBarFilters';

function App() {
  const [showRegister, setShowRegister] = useState(false);
  const [showLogin, setShowLogin] = useState(false);
  const [user, setUser] = useState(null);
  const [tomos, setTomos] = useState([]);

  // Cargar tomos por defecto (sin filtros) al montar el componente
  useEffect(() => {
    // Llamamos a handleFilterChange sin parámetros para cargar todo
    handleFilterChange({
      authors: [],
      languages: [],
      mangas: [],
      editorials: [],
      priceRange: [0, 999999], // Ajusta el rango máximo según tu necesidad
      searchText: '',
    });
  }, []);

  const handleRegisterSubmit = async (event) => {
    event.preventDefault();
    const nombre = event.target.elements.formNombre.value;
    const email = event.target.elements.formEmailRegister.value;
    const password = event.target.elements.formPasswordRegister.value;
    const data = { nombre, email, password };

    try {
      const response = await fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const result = await response.json();
      if (response.ok) {
        console.log(result.mensaje);
        setShowRegister(false);
      } else {
        console.error(result.errors);
      }
    } catch (error) {
      console.error('Error en registro:', error);
    }
  };

  const handleLoginSubmit = async (event) => {
    event.preventDefault();
    const email = event.target.elements.formEmailLogin.value;
    const password = event.target.elements.formPasswordLogin.value;
    const data = { email, password };

    try {
      const response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const result = await response.json();
      if (response.ok) {
        console.log('Login exitoso:', result);
        setUser(result.user);
        setShowLogin(false);
      } else {
        console.error('Errores en login:', result.errors);
      }
    } catch (error) {
      console.error('Error en login:', error);
    }
  };

  // Función para recibir los cambios de filtros desde el Sidebar
  const handleFilterChange = async (filters) => {
    const queryParams = new URLSearchParams();
    if (filters.authors.length) queryParams.append('authors', filters.authors.join(','));
    if (filters.languages.length) queryParams.append('languages', filters.languages.join(','));
    if (filters.mangas.length) queryParams.append('mangas', filters.mangas.join(','));
    if (filters.editorials.length) queryParams.append('editorials', filters.editorials.join(','));
    if (filters.searchText) queryParams.append('search', filters.searchText);
    if (filters.priceRange) {
      queryParams.append('minPrice', filters.priceRange[0]);
      queryParams.append('maxPrice', filters.priceRange[1]);
    }

    try {
      const response = await fetch(`http://localhost:8000/api/public/tomos?${queryParams.toString()}`);
      const result = await response.json();
      // Si la respuesta está paginada, tal vez debas usar result.data
      setTomos(result.data || result);
    } catch (error) {
      console.error('Error al obtener tomos:', error);
    }
  };

  return (
    <div className="bg-dark text-white min-vh-100">
      {/* NAVBAR */}
      <Navbar bg="dark" variant="dark" expand="lg" className="border-bottom border-light shadow">
        <Container fluid>
          <Navbar.Brand href="#home">
            <img
              src="/img/Mangaka.png"
              alt="Logo Mangaka"
              width="40"
              height="40"
              className="d-inline-block align-top rounded-circle"
            />
            <span className="ms-2">Mangaka Baka Shop</span>
          </Navbar.Brand>
          {/* Campo de búsqueda en el navbar (opcional) */}
          <Form className="d-flex mx-auto" style={{ width: '50%' }}>
            <Form.Control type="search" placeholder="Buscar" className="me-2" aria-label="Buscar" />
            <Button variant="outline-light">Buscar</Button>
          </Form>
          {/* Se ELIMINAN los filtros que estaban aquí (Autor, Manga, Editorial, Idioma) */}
          <div className="d-flex ms-auto">
            <Button variant="primary" className="me-2" onClick={() => setShowRegister(true)}>
              Registrar
            </Button>
            <Button variant="secondary" onClick={() => setShowLogin(true)}>
              Iniciar Sesión
            </Button>
          </div>
        </Container>
      </Navbar>

      {/* MENSAJE DE BIENVENIDA */}
      {user && (
        <Container className="mt-3">
          <Alert variant="success">Bienvenido, {user.nombre}!</Alert>
        </Container>
      )}

      {/* LAYOUT PRINCIPAL CON SIDEBAR A LA IZQUIERDA */}
      <div className="row g-0">
        {/* SIDEBAR: ocupa la parte izquierda debajo del navbar */}
        <div className="col-2 bg-secondary" style={{ minHeight: 'calc(100vh - 56px)' }}>
          <SidebarFilters onFilterChange={handleFilterChange} />
        </div>

        {/* CONTENIDO PRINCIPAL: tomos */}
        <div className="col-10 p-3">
          <h4>Tomos</h4>
          {tomos.length ? (
            <div className="d-flex flex-wrap">
              {tomos.map(tomo => (
                <div key={tomo.id} className="m-2 text-center">
                  <img
                    src={`http://localhost:8000/${tomo.portada}`}
                    alt={tomo.nombre}
                    style={{ width: '150px', height: 'auto' }}
                  />
                  <p>{tomo.nombre}</p>
                </div>
              ))}
            </div>
          ) : (
            <p>No se encontraron tomos con los filtros aplicados.</p>
          )}
        </div>
      </div>

      {/* MODAL DE REGISTRO */}
      <Modal show={showRegister} onHide={() => setShowRegister(false)}>
        <Modal.Header closeButton className="bg-dark text-white">
          <Modal.Title>Registrar</Modal.Title>
        </Modal.Header>
        <Modal.Body className="bg-dark text-white">
          <Form onSubmit={handleRegisterSubmit}>
            <Form.Group className="mb-3" controlId="formNombre">
              <Form.Label>Nombre</Form.Label>
              <Form.Control
                type="text"
                placeholder="Ingresa tu nombre"
                className="bg-secondary text-white"
              />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formEmailRegister">
              <Form.Label>Correo electrónico</Form.Label>
              <Form.Control
                type="email"
                placeholder="Ingresa tu correo"
                className="bg-secondary text-white"
              />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formPasswordRegister">
              <Form.Label>Contraseña</Form.Label>
              <Form.Control
                type="password"
                placeholder="Contraseña"
                className="bg-secondary text-white"
              />
            </Form.Group>
            <Button variant="primary" type="submit">Registrar</Button>
          </Form>
        </Modal.Body>
      </Modal>

      {/* MODAL DE INICIAR SESIÓN */}
      <Modal show={showLogin} onHide={() => setShowLogin(false)}>
        <Modal.Header closeButton className="bg-dark text-white">
          <Modal.Title>Iniciar Sesión</Modal.Title>
        </Modal.Header>
        <Modal.Body className="bg-dark text-white">
          <Form onSubmit={handleLoginSubmit}>
            <Form.Group className="mb-3" controlId="formEmailLogin">
              <Form.Label>Correo electrónico</Form.Label>
              <Form.Control
                type="email"
                placeholder="Ingresa tu correo"
                className="bg-secondary text-white"
              />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formPasswordLogin">
              <Form.Label>Contraseña</Form.Label>
              <Form.Control
                type="password"
                placeholder="Contraseña"
                className="bg-secondary text-white"
              />
            </Form.Group>
            <Button variant="primary" type="submit">Iniciar Sesión</Button>
          </Form>
        </Modal.Body>
      </Modal>
    </div>
  );
}

export default App;
