// App.js
import React, { useState, useEffect } from 'react';
import { Navbar, Container, Form, Button } from 'react-bootstrap';
import { FaShoppingCart } from 'react-icons/fa';
import SidebarFilters from './SideBarFilters';
import RegisterModal from './RegisterModal';
import LoginModal from './LoginModal';
import InfoModal from './InfoModal';
import TomoList from './TomoList';
import { CartProvider } from './CartContext';

function App() {
  const [showRegister, setShowRegister] = useState(false);
  const [showLogin, setShowLogin] = useState(false);
  const [user, setUser] = useState(null);
  const [tomos, setTomos] = useState([]);
  const [pagination, setPagination] = useState(null);

  // Estados para el modal de info
  const [showInfoModal, setShowInfoModal] = useState(false);
  const [selectedTomo, setSelectedTomo] = useState(null);

  // Opcional: guardar filtros actuales en estado para reutilizarlos en la paginación
  const [currentFilters, setCurrentFilters] = useState({
    authors: [],
    languages: [],
    mangas: [],
    editorials: [],
    priceRange: [0, 999999],
    searchText: '',
  });

  useEffect(() => {
    checkAuth(); // Verificar usuario autenticado
    // Cargar la primera página con filtros iniciales
    handleFilterChange(currentFilters, 1);
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
        body: JSON.stringify(data),
      });
      const result = await response.json();
      if (response.ok) {
        console.log('Registro exitoso:', result.mensaje);
        localStorage.setItem('token', result.token);
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
        body: JSON.stringify(data),
      });
      const result = await response.json();
      if (response.ok) {
        console.log('Login exitoso:', result);
        localStorage.setItem('token', result.token);
        setUser(result.cliente);
        setShowLogin(false);
      } else {
        console.error('Errores en login:', result.errors);
      }
    } catch (error) {
      console.error('Error en login:', error);
    }
  };

  // ---------- OBTENER USUARIO AUTENTICADO ----------
  const checkAuth = async () => {
    const token = localStorage.getItem('token');
    if (!token) return;
    try {
      const response = await fetch('http://localhost:8000/api/me', {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });
      const result = await response.json();
      if (response.ok) {
        setUser(result);
      } else {
        localStorage.removeItem('token');
      }
    } catch (error) {
      console.error('Error al obtener usuario:', error);
      localStorage.removeItem('token');
    }
  };

  // ---------- LOGOUT ----------
  const handleLogout = async () => {
    const token = localStorage.getItem('token');
    if (!token) {
      setUser(null);
      return;
    }
    try {
      await fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });
    } catch (error) {
      console.error('Error en logout:', error);
    } finally {
      localStorage.removeItem('token');
      setUser(null);
    }
  };

  // ---------- OBTENER TOMOS (FILTROS y PAGINACIÓN) ----------
  const handleFilterChange = async (filters, page = 1) => {
    // Actualizar filtros actuales para reutilizarlos en la paginación
    setCurrentFilters(filters);

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
    queryParams.append('page', page);

    try {
      const response = await fetch(`http://localhost:8000/api/public/tomos?${queryParams.toString()}`);
      const result = await response.json();
      setTomos(result.data);
      setPagination({
        currentPage: result.current_page,
        lastPage: result.last_page,
        total: result.total,
      });
    } catch (error) {
      console.error('Error al obtener tomos:', error);
    }
  };

  // Función para cambiar de página utilizando los filtros actuales
  const handlePageChange = (page) => {
    handleFilterChange(currentFilters, page);
  };

  // Función para manejar la acción de mostrar información del tomo
  const handleShowInfo = (tomo) => {
    setSelectedTomo(tomo);
    setShowInfoModal(true);
  };

  return (
    <CartProvider>
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
            <Form className="d-flex mx-auto" style={{ width: '50%' }}>
              <Form.Control type="search" placeholder="Buscar" className="me-2" aria-label="Buscar" />
              <Button variant="outline-light">Buscar</Button>
            </Form>
            <div className="d-flex ms-auto align-items-center">
              {user ? (
                <>
                  <span className="me-2">Hola, {user.nombre}</span>
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
                    Registrarse
                  </Button>
                  <Button variant="secondary" onClick={() => setShowLogin(true)}>
                    Iniciar Sesión
                  </Button>
                </>
              )}
            </div>
          </Container>
        </Navbar>

        {/* CONTENEDOR PRINCIPAL */}
        <div className="d-flex" style={{ minHeight: 'calc(100vh - 56px)' }}>
          <SidebarFilters onFilterChange={(filters) => handleFilterChange(filters, 1)} />
          <TomoList
            tomos={tomos}
            pagination={pagination}
            onPageChange={handlePageChange}
            onShowInfo={handleShowInfo}
            isLoggedIn={Boolean(user)}  // Se usa Boolean(user) para determinar el estado de autenticación
          />
        </div>

        {/* Modales */}
        <RegisterModal show={showRegister} onHide={() => setShowRegister(false)} onSubmit={handleRegisterSubmit} />
        <LoginModal show={showLogin} onHide={() => setShowLogin(false)} onSubmit={handleLoginSubmit} />
        <InfoModal show={showInfoModal} onClose={() => setShowInfoModal(false)} tomo={selectedTomo} />
      </div>
    </CartProvider>
  );
}

export default App;
