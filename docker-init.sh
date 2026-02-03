#!/bin/bash

# Docker Initialization Script for Inventory System

echo "🚀 Iniciando sistema de inventario con Docker..."

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    echo "📋 Creando archivo .env..."
    cp .env.example .env
    php artisan key:generate
fi

# Levantar contenedores
echo "🐳 Levantando contenedores Docker..."
docker-compose up -d

# Esperar a que la base de datos esté lista
echo "⏳ Esperando a que la base de datos esté lista..."
sleep 10

# Instalar dependencias
echo "📦 Instalando dependencias de Composer..."
docker-compose exec -T app composer install

# Ejecutar migraciones
echo "🗄️  Ejecutando migraciones..."
docker-compose exec -T app php artisan migrate

# Ejecutar seeders
echo "🌱 Ejecutando seeders..."
docker-compose exec -T app php artisan db:seed

# Limpiar caché
echo "🧹 Limpiando caché..."
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear

echo ""
echo "✅ ¡Sistema listo!"
echo ""
echo "📱 Acceso a la aplicación:"
echo "  - Aplicación: http://localhost"
echo "  - phpMyAdmin: http://localhost:8080"
echo ""
echo "🔐 Credenciales phpMyAdmin:"
echo "  - Usuario: inventory_user"
echo "  - Contraseña: password"
echo ""
echo "📝 Para ver los logs:"
echo "  - docker-compose logs -f app"
echo ""
