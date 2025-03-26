// App.js
import React, { useState } from 'react';
import { Navbar, Container, Form, Button, Modal, Alert } from 'react-bootstrap';

function App() {
  // Estados para controlar la visibilidad de los modales y almacenar el usuario
  const [showRegister, setShowRegister] = useState(false);
  const [showLogin, setShowLogin] = useState(false);
  const [user, setUser] = useState(null);

  const handleRegisterClose = () => setShowRegister(false);
  const handleRegisterShow = () => setShowRegister(true);
  const handleLoginClose = () => setShowLogin(false);
  const handleLoginShow = () => setShowLogin(true);

  // Función para enviar datos de registro al backend de Laravel en localhost
  const handleRegisterSubmit = async (event) => {
    event.preventDefault();

    // Recopilamos los datos del formulario de registro
    const nombre = event.target.elements.formNombre.value;
    const email = event.target.elements.formEmailRegister.value;
    const password = event.target.elements.formPasswordRegister.value;

    const data = { nombre, email, password };

    try {
      const response = await fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
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
      console.error('Error al registrar:', error);
    }
  };

  // Función para enviar datos de inicio de sesión al backend de Laravel
  const handleLoginSubmit = async (event) => {
    event.preventDefault();

    // Recopilamos los datos del formulario de inicio de sesión
    const email = event.target.elements.formEmailLogin.value;
    const password = event.target.elements.formPasswordLogin.value;

    const data = { email, password };

    try {
      const response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();

      if (response.ok) {
        console.log('Login exitoso:', result);
        // Se espera que el backend retorne el usuario en result.user
        setUser(result.user);
        setShowLogin(false);
      } else {
        console.error('Errores de login:', result.errors);
      }
    } catch (error) {
      console.error('Error al iniciar sesión:', error);
    }
  };

  return (
    <div className="bg-dark text-white min-vh-100">
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
          <Form className="d-flex mx-auto" style={{ width: '50%' }}>
            <Form.Control type="search" placeholder="Buscar" className="me-2" aria-label="Buscar" />
            <Button variant="outline-light">Buscar</Button>
          </Form>
          <Form className="d-flex align-items-center ms-3">
            <Form.Check inline type="radio" label="Autor" name="searchType" id="radioAutor" />
            <Form.Check inline type="radio" label="Manga" name="searchType" id="radioManga" />
            <Form.Check inline type="radio" label="Editorial" name="searchType" id="radioEditorial" />
            <Form.Check inline type="radio" label="Idioma" name="searchType" id="radioIDOM" />
          </Form>
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

      {/* Mensaje de bienvenida si el usuario está logueado */}
      {user && (
        <Container className="mt-3">
          <Alert variant="success">
            Bienvenido, {user.nombre}!
          </Alert>
        </Container>
      )}

      {/* Modal para Registrar */}
      <Modal show={showRegister} onHide={handleRegisterClose}>
        <Modal.Header closeButton className="bg-dark text-white">
          <Modal.Title>Registrar</Modal.Title>
        </Modal.Header>
        <Modal.Body className="bg-dark text-white">
          <Form onSubmit={handleRegisterSubmit}>
            <Form.Group className="mb-3" controlId="formNombre">
              <Form.Label>Nombre</Form.Label>
              <Form.Control type="text" placeholder="Ingresa tu nombre" className="bg-secondary text-white" />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formEmailRegister">
              <Form.Label>Correo electrónico</Form.Label>
              <Form.Control type="email" placeholder="Ingresa tu correo" className="bg-secondary text-white" />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formPasswordRegister">
              <Form.Label>Contraseña</Form.Label>
              <Form.Control type="password" placeholder="Contraseña" className="bg-secondary text-white" />
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
          <Form onSubmit={handleLoginSubmit}>
            <Form.Group className="mb-3" controlId="formEmailLogin">
              <Form.Label>Correo electrónico</Form.Label>
              <Form.Control type="email" placeholder="Ingresa tu correo" className="bg-secondary text-white" />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formPasswordLogin">
              <Form.Label>Contraseña</Form.Label>
              <Form.Control type="password" placeholder="Contraseña" className="bg-secondary text-white" />
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
