// CartPage.js
import React, { useContext } from 'react';
import { CartContext } from './CartContext';
import { Button, Image, Table } from 'react-bootstrap';

const CartPage = () => {
  const { cart, updateCartItem, clearCart } = useContext(CartContext);

  // Funciones para aumentar/disminuir cantidad
  const handleIncrease = (itemId) => {
    const item = cart.find((item) => item.id === itemId);
    if (item.quantity < item.stock) {
      updateCartItem(itemId, item.quantity + 1);
    }
  };

  const handleDecrease = (itemId) => {
    const item = cart.find((item) => item.id === itemId);
    if (item.quantity > 1) {
      updateCartItem(itemId, item.quantity - 1);
    }
  };

  // Calcular el total a pagar (suma de cada ítem: precio * cantidad)
  const totalAmount = cart.reduce(
    (sum, item) => sum + item.precio * item.quantity,
    0
  );

  // Función para procesar la compra: envía la información al backend para actualizar stock
  const handleBuy = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          items: cart.map((item) => ({
            id: item.id,
            quantity: item.quantity,
          })),
        }),
      });
      const result = await response.json();
      if (response.ok) {
        clearCart();
        alert('Compra realizada exitosamente.');
      } else {
        alert('Error al realizar la compra.');
      }
    } catch (error) {
      console.error('Error en compra:', error);
      alert('Error al realizar la compra.');
    }
  };

  if (cart.length === 0) {
    return <p className="container my-4">No hay elementos en el carrito.</p>;
  }

  return (
    <div className="container my-4">
      <h2>Carrito de Compras</h2>
      <Table striped bordered hover>
        <thead>
          <tr>
            <th>Portada</th>
            <th>Título</th>
            <th>Tomo</th>
            <th>Idioma</th>
            <th>Stock disponible</th>
            <th>Cantidad</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          {cart.map((item) => (
            <tr key={item.id}>
              <td>
                <Image
                  src={`http://localhost:8000/${item.portada}`}
                  alt={item.manga?.titulo}
                  thumbnail
                  style={{ width: '50px' }}
                />
              </td>
              <td>{item.manga?.titulo}</td>
              <td>{item.numero_tomo}</td>
              <td>{item.idioma}</td>
              <td>{item.stock}</td>
              <td>
                <Button
                  variant="secondary"
                  size="sm"
                  onClick={() => handleDecrease(item.id)}
                  disabled={item.quantity <= 1}
                >
                  -
                </Button>
                <span className="mx-2">{item.quantity}</span>
                <Button
                  variant="secondary"
                  size="sm"
                  onClick={() => handleIncrease(item.id)}
                  disabled={item.quantity >= item.stock}
                >
                  +
                </Button>
              </td>
              <td>${(item.precio * item.quantity).toFixed(2)}</td>
            </tr>
          ))}
        </tbody>
      </Table>
      <h4>Total a pagar: ${totalAmount.toFixed(2)}</h4>
      <Button variant="success" onClick={handleBuy}>
        Comprar
      </Button>
    </div>
  );
};

export default CartPage;
