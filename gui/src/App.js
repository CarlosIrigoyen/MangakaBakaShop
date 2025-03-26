// App.js
import React, { useState, useEffect } from 'react';
import { Navbar, Container, Form, Button, Modal, Card } from 'react-bootstrap';
import SidebarFilters from './SideBarFilters';
import { FaShoppingCart } from 'react-icons/fa';

function App() {
  const [showRegister, setShowRegister] = useState(false);
  const [showLogin, setShowLogin] = useState(false);
  const [user, setUser] = useState(null);
  const [tomos, setTomos] = useState([]);

  // Estados para manejar el modal de info
  const [showInfoModal, setShowInfoModal] = useState(false);
  const [selectedTomo, setSelectedTomo] = useState(null);

  // Cargar tomos por defecto (sin filtros) al montar el componente
  useEffect(() => {
    handleFilterChange({
      authors: [],
      languages: [],
      mangas: [],
      editorials: [],
      priceRange: [0, 999999],
      searchText: '',
    });
  }, []);

  // ---------- REGISTRO ----------
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
        // Auto-login: usamos result.cliente para iniciar sesión
        setUser(result.cliente);
        setShowRegister(false);
      } else {
        console.error(result.errors);
      }
    } catch (error) {
      console.error('Error en registro:', error);
    }
  };

  // ---------- LOGIN ----------
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

  // ---------- LOGOUT ----------
  const handleLogout = () => {
    setUser(null);
    // Aquí podrías agregar lógica adicional como limpiar tokens, etc.
  };

  // ---------- OBTENER TOMOS (FILTROS) ----------
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
      setTomos(result.data || result);
    } catch (error) {
      console.error('Error al obtener tomos:', error);
    }
  };

  // ---------- AGREGAR A CARRITO ----------
  const handleAddToCart = (tomo) => {
    console.log('Agregar a carrito:', tomo);
    // Aquí podrías implementar la lógica para agregar el producto al carrito
  };

  // ---------- INFO TOMO (MODAL) ----------
  const handleShowInfo = (tomo) => {
    setSelectedTomo(tomo);
    setShowInfoModal(true);
  };

  const handleCloseInfo = () => {
    setShowInfoModal(false);
    setSelectedTomo(null);
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
          {/* Campo de búsqueda en el navbar */}
          <Form className="d-flex mx-auto" style={{ width: '50%' }}>
            <Form.Control type="search" placeholder="Buscar" className="me-2" aria-label="Buscar" />
            <Button variant="outline-light">Buscar</Button>
          </Form>
          <div className="d-flex ms-auto align-items-center">
            {user ? (
              <>
                <Button variant="outline-light" className="me-2" style={{ fontSize: '1.5rem' }}>
                  <FaShoppingCart />
                </Button>
                <Button variant="danger" onClick={handleLogout}>
                  Cerrar Sesión
                </Button>
              </>
            ) : (
              <>
                <Button variant="primary" className="me-2" onClick={() => setShowRegister(true)}>
                  Registrar
                </Button>
                <Button variant="secondary" onClick={() => setShowLogin(true)}>
                  Iniciar Sesión
                </Button>
              </>
            )}
          </div>
        </Container>
      </Navbar>

      {/* CONTENEDOR PRINCIPAL EN FLEX (Sidebar fijo + Contenido) */}
      <div className="d-flex" style={{ minHeight: 'calc(100vh - 56px)' }}>
        {/* SIDEBAR de ancho fijo */}
        <div style={{ width: '250px' }} className="bg-secondary">
          <SidebarFilters onFilterChange={handleFilterChange} />
        </div>

        {/* CONTENIDO PRINCIPAL: se expande en el resto del espacio */}
        <div className="flex-fill p-3">
          <h4>Tomos</h4>
          {tomos.length ? (
            <div className="row">
              {tomos.map((tomo) => (
                <div key={tomo.id} className="col-sm-6 col-md-4 col-lg-3 mb-3">
                  <Card bg="dark" text="white" className="h-100">
                  <Card.Img
                      variant="top"
                      src={`http://localhost:8000/${tomo.portada}`}
                      alt={tomo.nombre}
                      style={{
                        width: '100%',
                        height: 'auto',
                        maxHeight: '300px',
                        objectFit: 'contain', 
                      }}
                  />
                    <Card.Body>
                        <Card.Title>{tomo.nombre}</Card.Title>
                        <div className="d-grid gap-2">
                          <Button
                            variant="info"
                            onClick={() => handleShowInfo(tomo)}
                          >
                            Info
                          </Button>
                          <Button
                            variant="primary"
                            onClick={() => handleAddToCart(tomo)}
                          >
                            Agregar a Carrito
                          </Button>
                        </div>
                    </Card.Body>
                  </Card>
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
            <Button variant="primary" type="submit">
              Registrar
            </Button>
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
            <Button variant="primary" type="submit">
              Iniciar Sesión
            </Button>
          </Form>
        </Modal.Body>
      </Modal>

      {/* MODAL DE INFO DEL TOMO */}
      <Modal show={showInfoModal} onHide={handleCloseInfo}>
        <Modal.Header closeButton className="bg-dark text-white">
          <Modal.Title>Información del Tomo</Modal.Title>
        </Modal.Header>
        <Modal.Body className="bg-dark text-white">
          {selectedTomo && (
            <>
              <Card bg="dark" text="white">
                <Card.Img
                  variant="top"
                  src={`http://localhost:8000/${selectedTomo.portada}`}
                  alt={selectedTomo.nombre}
                  style={{
                    width: '100%',
                    height: 'auto',
                    maxHeight: '300px',
                    objectFit: 'cover',
                  }}
                />
                <Card.Body>
                  <Card.Title>{selectedTomo.nombre}</Card.Title>
                  <Card.Text>
                    <strong>Número de Tomo:</strong>{' '}
                    {selectedTomo.numero_tomo || 'No disponible'}
                    <br />
                    <strong>Editorial:</strong>{' '}
                    {selectedTomo.editorial?.nombre || 'No disponible'}
                    <br />
                    <strong>Formato:</strong>{' '}
                    {selectedTomo.formato || 'Tankōbon'}
                    <br />
                    <strong>Idioma:</strong>{' '}
                    {selectedTomo.idioma || 'No disponible'}
                    <br />
                    <strong>Precio:</strong>{' '}
                    ${Number(selectedTomo.precio).toFixed(2)}
                    <br />
                    <strong>Autor:</strong>{' '}
                    {selectedTomo.manga?.autor
                      ? `${selectedTomo.manga.autor.nombre} ${selectedTomo.manga.autor.apellido}`
                      : 'No disponible'}
                    <br />
                    <strong>Dibujante:</strong>{' '}
                    {selectedTomo.manga?.dibujante
                      ? `${selectedTomo.manga.dibujante.nombre} ${selectedTomo.manga.dibujante.apellido}`
                      : selectedTomo.manga?.autor
                      ? `${selectedTomo.manga.autor.nombre} ${selectedTomo.manga.autor.apellido}`
                      : 'No disponible'}
                    <br />
                    <strong>Géneros:</strong>{' '}
                    {selectedTomo.manga?.generos && selectedTomo.manga.generos.length
                      ? selectedTomo.manga.generos.join(', ')
                      : 'No disponible'}
                  </Card.Text>
                </Card.Body>
              </Card>
            </>
          )}
        </Modal.Body>
        <Modal.Footer className="bg-dark text-white">
          <Button variant="secondary" onClick={handleCloseInfo}>
            Cerrar
          </Button>
        </Modal.Footer>
      </Modal>
    </div>
  );
}

export default App;
