// App.js
import React, { useState } from 'react';
import { Navbar, Container, Form, Button, Modal } from 'react-bootstrap';

function App() {
  // Estados para controlar la visibilidad de los modales
  const [showRegister, setShowRegister] = useState(false);
  const [showLogin, setShowLogin] = useState(false);

  const handleRegisterClose = () => setShowRegister(false);
  const handleRegisterShow = () => setShowRegister(true);
  const handleLoginClose = () => setShowLogin(false);
  const handleLoginShow = () => setShowLogin(true);

  return (
    // Contenedor principal con fondo oscuro y texto blanco, ocupando el 100% de la altura
    <div className="bg-dark text-white min-vh-100">
      {/* Barra de navegación con borde inferior claro y sombra para contraste */}
      <Navbar bg="dark" variant="dark" expand="lg" className="border-bottom border-light shadow">
        <Container fluid>
          {/* Logo en la parte izquierda */}
          <Navbar.Brand href="#home">
            <img
              src="/img/Mangaka.png" // Ruta a la imagen en public/img/Mangaka.png
              alt="Logo Mangaka"
              width="40"
              height="40"
              className="d-inline-block align-top rounded-circle"
            />
            <span className="ms-2">Mangaka Baka Shop</span>
          </Navbar.Brand>

          {/* Formulario de búsqueda en el centro */}
          <Form className="d-flex mx-auto" style={{ width: '50%' }}>
            <Form.Control
              type="search"
              placeholder="Buscar"
              className="me-2"
              aria-label="Buscar"
            />
            <Button variant="outline-light">Buscar</Button>
          </Form>

          {/* Radio buttons para filtrar búsqueda: Autor, Manga, Editorial, Idioma */}
          <Form className="d-flex align-items-center ms-3">
            <Form.Check
              inline
              type="radio"
              label="Autor"
              name="searchType"
              id="radioAutor"
            />
            <Form.Check
              inline
              type="radio"
              label="Manga"
              name="searchType"
              id="radioManga"
            />
            <Form.Check
              inline
              type="radio"
              label="Editorial"
              name="searchType"
              id="radioEditorial"
            />
            <Form.Check
              inline
              type="radio"
              label="Idioma"
              name="searchType"
              id="radioIDOM"
            />
          </Form>

          {/* Botones para Registrar e Iniciar Sesión a la derecha */}
          <div className="d-flex ms-auto">
            <Button variant="primary" className="me-2" onClick={handleRegisterShow}>
              Registrar
            </Button>
            <Button variant="secondary" onClick={handleLoginShow}>
              Iniciar Sesión
            </Button>
          </div>
        </Container>
      </Navbar>

      {/* Modal para Registrar */}
      <Modal show={showRegister} onHide={handleRegisterClose}>
        <Modal.Header closeButton className="bg-dark text-white">
          <Modal.Title>Registrar</Modal.Title>
        </Modal.Header>
        <Modal.Body className="bg-dark text-white">
          <Form>
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

      {/* Modal para Iniciar Sesión */}
      <Modal show={showLogin} onHide={handleLoginClose}>
        <Modal.Header closeButton className="bg-dark text-white">
          <Modal.Title>Iniciar Sesión</Modal.Title>
        </Modal.Header>
        <Modal.Body className="bg-dark text-white">
          <Form>
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
    </div>
  );
}

export default App;
