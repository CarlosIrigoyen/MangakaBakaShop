// TomoList.js
import React, { useContext } from 'react';
import { Card, Button, Pagination } from 'react-bootstrap';
import { FaShoppingCart, FaInfoCircle } from 'react-icons/fa';
import { CartContext } from './CartContext';

const TomoList = ({ tomos, pagination, onPageChange, onShowInfo, isLoggedIn }) => {
  // Llamada al hook en el nivel superior para obtener addToCart del contexto
  const { addToCart } = useContext(CartContext);

  // Si la respuesta viene paginada, se espera que los registros estén en tomos.data
  const data = tomos.data ? tomos.data : tomos;

  // Si no hay registros, muestra un mensaje informativo
  if (data.length === 0) {
    return <p>No se encontraron resultados para los filtros seleccionados.</p>;
  }

  // Ordenar tomos: primero por título (alfabéticamente) y luego por número de tomo (convertido a número)
  const sortedTomos = [...data].sort((a, b) => {
    const titleA = (a.manga?.titulo || '').toLowerCase();
    const titleB = (b.manga?.titulo || '').toLowerCase();
    if (titleA < titleB) return -1;
    if (titleA > titleB) return 1;
    return Number(a.numero_tomo) - Number(b.numero_tomo);
  });

  return (
    <div className="container my-4">
      <div className="row">
        {sortedTomos.map((tomo) => (
          <div key={tomo.id} className="col-md-3 mb-4 d-flex">
            <Card className="w-100 h-100">
              <Card.Img
                variant="top"
                src={`http://localhost:8000/${tomo.portada}`}
                alt={`${tomo.manga?.titulo} Tomo ${tomo.numero_tomo}`}
                style={{ objectFit: 'cover', height: '200px' }}
              />
              <Card.Body className="d-flex flex-column">
                <Card.Title>
                  {tomo.manga?.titulo} Tomo {tomo.numero_tomo} - {tomo.idioma}
                </Card.Title>
                <Card.Text>
                  Precio: ${parseFloat(tomo.precio).toFixed(0)}
                </Card.Text>
                <div className="mt-auto d-flex justify-content-center">
                  {isLoggedIn && (
                    <Button
                      variant="primary"
                      className="me-2"
                      onClick={() => addToCart(tomo)}
                    >
                      <FaShoppingCart /> Agregar Carrito
                    </Button>
                  )}
                  <Button variant="info" onClick={() => onShowInfo(tomo)}>
                    <FaInfoCircle /> Info
                  </Button>
                </div>
              </Card.Body>
            </Card>
          </div>
        ))}
      </div>
      {pagination && (
        <div className="d-flex justify-content-center">
          <Pagination>
            {[...Array(pagination.lastPage)].map((_, index) => (
              <Pagination.Item
                key={index + 1}
                active={index + 1 === pagination.currentPage}
                onClick={() => onPageChange(index + 1)}
              >
                {index + 1}
              </Pagination.Item>
            ))}
          </Pagination>
        </div>
      )}
    </div>
  );
};

export default TomoList;
