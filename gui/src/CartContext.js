// CartContext.js
import React, { createContext, useState } from 'react';

export const CartContext = createContext();

export const CartProvider = ({ children }) => {
  const [cart, setCart] = useState([]);

  // Agrega el tomo si no existe; en este ejemplo, si ya existe no incrementamos
  const addToCart = (tomo) => {
    setCart((prevCart) => {
      const existing = prevCart.find((item) => item.id === tomo.id);
      if (existing) {
        return prevCart; // Ya está en el carrito, no lo agregamos nuevamente
      }
      return [...prevCart, { ...tomo, quantity: 1 }];
    });
  };

  // Permite actualizar la cantidad de un item en el carrito
  const updateCartItem = (id, newQuantity) => {
    setCart((prevCart) =>
      prevCart.map((item) =>
        item.id === id ? { ...item, quantity: newQuantity } : item
      )
    );
  };

  // Limpia el carrito (por ejemplo, después de una compra exitosa)
  const clearCart = () => {
    setCart([]);
  };

  return (
    <CartContext.Provider value={{ cart, addToCart, updateCartItem, clearCart }}>
      {children}
    </CartContext.Provider>
  );
};
