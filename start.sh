#!/bin/bash

# Levantar la API (Laravel)
echo "Levantando la API (Laravel)..."
cd api || exit  # Navega al directorio api
php artisan serve --host=0.0.0.0 --port=8000 &  # Inicia el servidor de Laravel en segundo plano

# Levantar la GUI (React)
echo "Levantando la GUI (React)..."
cd ../gui || exit  # Navega al directorio gui
npm start &  # Inicia el servidor de React en segundo plano

# Esperar a que el usuario presione Ctrl+C para finalizar ambos servidores
echo "Ambos servidores están corriendo. Presiona Ctrl+C para detenerlos."
wait
