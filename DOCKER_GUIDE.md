# 🐳 Guía de Dockerización - Sistema de Inventario

## Descripción General

Este proyecto ha sido dockerizado para facilitar su despliegue y desarrollo. La configuración incluye:

- **PHP 8.2-FPM**: Motor de ejecución PHP
- **Nginx**: Servidor web y proxy inverso
- **MySQL 8.0**: Base de datos
- **phpMyAdmin**: Gestor de base de datos web
- **Volúmenes persistentes**: Para almacenamiento de datos

---

## 📋 Requisitos

- Docker Desktop (v20.10+) o Docker Engine + Docker Compose
- 2GB de RAM disponible mínimo
- 3GB de espacio en disco
- Puerto 80, 443, 3306 y 8080 disponibles

### Instalar Docker

#### En Windows/Mac:
```bash
# Descargar e instalar Docker Desktop desde:
https://www.docker.com/products/docker-desktop
```

#### En Linux (Ubuntu/Debian):
```bash
# Actualizar paquetes
sudo apt update

# Instalar Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Agregar usuario al grupo docker
sudo usermod -aG docker $USER
newgrp docker

# Instalar Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

---

## 🚀 Inicio Rápido

### Opción 1: Script Automático (Recomendado)

```bash
# Ejecutar el script de inicialización
chmod +x docker-init.sh
./docker-init.sh
```

El script automáticamente:
- Crea el archivo `.env`
- Genera la clave de aplicación
- Levanta los contenedores
- Instala dependencias de Composer
- Ejecuta las migraciones
- Ejecuta los seeders

### Opción 2: Instalación Manual

```bash
# 1. Clonar o descargar el proyecto
cd inventory

# 2. Copiar archivo de configuración
cp .env.example .env

# 3. Generar clave de aplicación
docker-compose run --rm app php artisan key:generate

# 4. Levantar contenedores
docker-compose up -d

# 5. Instalar dependencias
docker-compose exec app composer install

# 6. Ejecutar migraciones
docker-compose exec app php artisan migrate

# 7. Ejecutar seeders (opcional)
docker-compose exec app php artisan db:seed

# 8. Limpiar caché
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

---

## 🌐 Acceso a la Aplicación

| Servicio | URL | Usuario | Contraseña |
|----------|-----|---------|------------|
| Aplicación | http://localhost | - | - |
| phpMyAdmin | http://localhost:8080 | inventory_user | password |
| MySQL | localhost:3306 | inventory_user | password |

---

## 📝 Comandos Útiles

### Gestión de Contenedores

```bash
# Ver estado de los contenedores
docker-compose ps

# Levantar contenedores
docker-compose up -d

# Detener contenedores
docker-compose down

# Reiniciar contenedores
docker-compose restart

# Ver logs
docker-compose logs -f app

# Ver logs de Nginx
docker-compose logs -f nginx

# Ver logs de MySQL
docker-compose logs -f db
```

### Comandos de Artisan

```bash
# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Revertir migraciones
docker-compose exec app php artisan migrate:rollback

# Ejecutar seeders
docker-compose exec app php artisan db:seed

# Crear caché de configuración
docker-compose exec app php artisan config:cache

# Ver rutas registradas
docker-compose exec app php artisan route:list
```

### Comandos de Composer

```bash
# Instalar dependencias
docker-compose exec app composer install

# Actualizar dependencias
docker-compose exec app composer update

# Limpiar caché de Composer
docker-compose exec app composer dump-autoload
```

### Acceder a la Terminal

```bash
# Acceder al contenedor PHP
docker-compose exec app bash

# Acceder a MySQL
docker-compose exec db mysql -u inventory_user -p inventory_db

# Ejecutar tinker (repl de Laravel)
docker-compose exec app php artisan tinker
```

---

## 🧪 Ejecución de Tests

```bash
# Ejecutar todos los tests
docker-compose exec app php artisan test

# Ejecutar tests específicos
docker-compose exec app php artisan test tests/Feature/ProductoTest.php

# Ejecutar tests con coverage
docker-compose exec app php artisan test --coverage
```

---

## 🔧 Configuración Personalizada

### Variables de Entorno (.env)

```env
# Base de Datos
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=inventory_user
DB_PASSWORD=password

# Aplicación
APP_NAME=Inventory
APP_ENV=production
APP_URL=http://localhost
```

### Modificar Puerto de Nginx

En `docker-compose.yml`, cambiar la sección de puertos de Nginx:

```yaml
nginx:
  ports:
    - "8000:80"  # Cambiar 8000 por el puerto deseado
```

### Aumentar Límite de Carga de Archivos

Editar `docker/php/local.ini`:

```ini
upload_max_filesize=500M  # Aumentar según necesidad
post_max_size=500M
```

---

## 🐛 Solución de Problemas

### Error: "Port 80 is already in use"

```bash
# Encontrar qué servicio usa el puerto
lsof -i :80

# Opción 1: Cambiar el puerto en docker-compose.yml
# Opción 2: Liberar el puerto
sudo kill -9 <PID>
```

### Error: "Cannot connect to database"

```bash
# Verificar que el contenedor de MySQL está corriendo
docker-compose ps

# Ver logs de MySQL
docker-compose logs db

# Reiniciar solo MySQL
docker-compose restart db
```

### Permiso denegado en archivos

```bash
# Ajustar permisos
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

### Caché de Aplicación Corrupto

```bash
# Limpiar todo el caché
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan route:clear
```

---

## 📦 Estructura de Directorios Docker

```
project/
├── docker/
│   ├── nginx/
│   │   ├── conf.d/
│   │   │   └── default.conf      # Configuración de Nginx
│   │   └── ssl/                   # Certificados SSL (opcional)
│   └── php/
│       └── local.ini              # Configuración de PHP
├── Dockerfile                     # Imagen de PHP
├── docker-compose.yml             # Orquestación de servicios
├── docker-init.sh                 # Script de inicialización
└── .dockerignore                  # Archivos ignorados en Docker
```

---

## 🔒 Seguridad en Producción

Para desplegar en producción:

1. **Cambiar contraseñas**:
   ```bash
   # Actualizar en .env
   DB_PASSWORD=tu_contraseña_segura
   ```

2. **SSL/TLS**:
   - Colocar certificados en `docker/nginx/ssl/`
   - Actualizar configuración de Nginx

3. **Configuración de Producción**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

4. **Backups**:
   ```bash
   # Backup de la base de datos
   docker-compose exec db mysqldump -u inventory_user -p inventory_db > backup.sql
   ```

---

## 📚 Recursos Adicionales

- [Documentación de Docker](https://docs.docker.com/)
- [Documentación de Docker Compose](https://docs.docker.com/compose/)
- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Nginx](https://nginx.org/en/docs/)

---

## 📞 Soporte

Para reportar problemas o sugerencias:

1. Revisar los logs: `docker-compose logs -f`
2. Verificar que Docker está corriendo
3. Consultar la sección de "Solución de Problemas"

---

**Última actualización**: 3 de febrero de 2026
