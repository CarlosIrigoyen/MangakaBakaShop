// CartPage.js
import React, { useContext } from 'react';
import { CartContext } from './CartContext';

const CartPage = () => {
  const { cart } = useContext(CartContext);

  return (
    <div>
      <h2>Carrito de Compras</h2>
      {cart.length === 0 ? (
        <p>No hay elementos en el carrito</p>
      ) : (
        <ul>
          {cart.map((item, index) => (
            <li key={index}>
              {item.titulo}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
};

export default CartPage;
