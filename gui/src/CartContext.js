// CartContext.js
import React, { createContext, useState, useEffect } from 'react';

export const CartContext = createContext();

export const CartProvider = ({ children }) => {
  // Al iniciar, se verifica si hay un carrito guardado en localStorage
  const [cart, setCart] = useState(() => {
    const savedCart = localStorage.getItem('cart');
    return savedCart ? JSON.parse(savedCart) : [];
  });

  // Cada vez que el carrito cambie, se guarda en localStorage
  useEffect(() => {
    localStorage.setItem('cart', JSON.stringify(cart));
  }, [cart]);

  const addToCart = (item) => {
    // Ejemplo de cómo agregar un ítem, evitando duplicados
    if (!cart.find(cartItem => cartItem.id === item.id)) {
      setCart([...cart, { ...item, quantity: 1 }]);
    }
  };

  const updateCartItem = (itemId, quantity) => {
    const updatedCart = cart.map(item => item.id === itemId ? { ...item, quantity } : item);
    setCart(updatedCart);
  };

  const clearCart = () => {
    setCart([]);
  };

  return (
    <CartContext.Provider value={{ cart, addToCart, updateCartItem, clearCart }}>
      {children}
    </CartContext.Provider>
  );
};
