# Docker Setup - Guía de Instalación

## Requisitos Previos

- Docker Desktop instalado
- Docker Compose instalado (viene con Docker Desktop)

## Pasos para Ejecutar la Aplicación en Docker

### 1. Construir las imágenes
```bash
docker-compose build
```

### 2. Iniciar los contenedores
```bash
docker-compose up -d
```

### 3. Verificar que los contenedores están corriendo
```bash
docker-compose ps
```

Deberías ver:
- `inventory_app` - PHP-FPM running
- `inventory_nginx` - Nginx running

### 4. Acceder a la aplicación
- **URL Local:** http://localhost
- **Puerto:** 80

## Credenciales de Acceso

### Admin
- Email: samuelreyescastro456@gmail.com
- Password: Admin@2026!

### User 1
- Email: juan.perez@example.com
- Password: Juan@Perez123

### User 2
- Email: maria.garcia@example.com
- Password: Maria@Garcia456

## Comandos Útiles

### Ver logs de la aplicación
```bash
docker-compose logs -f app
```

### Ver logs de nginx
```bash
docker-compose logs -f nginx
```

### Ejecutar artisan dentro del contenedor
```bash
docker-compose exec app php artisan <command>
```

### Ejecutar tests
```bash
docker-compose exec app php artisan test
```

### Resetear base de datos
```bash
docker-compose exec app php artisan migrate:fresh --seed --force
```

### Acceder a la shell del contenedor
```bash
docker-compose exec app bash
```

### Detener los contenedores
```bash
docker-compose down
```

### Detener y eliminar volúmenes (limpia todo)
```bash
docker-compose down -v
```

## Solución de Problemas

### La aplicación no inicia
```bash
# Ver los logs
docker-compose logs app

# Reconstruir las imágenes
docker-compose build --no-cache

# Reiniciar
docker-compose down && docker-compose up -d
```

### Error de permisos en storage
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Base de datos corrupta
```bash
# Resetear completamente
docker-compose down -v
docker-compose up -d
docker-compose exec app php artisan migrate:fresh --seed --force
```

### Puerto 80 ya está en uso
Cambiar el puerto en `docker-compose.yml`:
```yaml
ports:
  - "8000:80"  # Cambiar a otro puerto si es necesario
```

Luego acceder a: http://localhost:8000

## Estructura de Contenedores

```
inventory_app (PHP 8.4-FPM)
    ↓
inventory_nginx (Reverse Proxy)
    ↓
http://localhost (Tu navegador)
```

## Variables de Entorno

Las siguientes variables están configuradas automáticamente en Docker:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=sqlite`
- `APP_URL=http://localhost`

Para cambiarlas, edita `docker-compose.yml` en la sección `environment` del servicio `app`.

## Performance

Para mejor performance en desarrollo:
1. Asegúrate que Docker tiene suficientes recursos asignados
2. En Docker Desktop → Settings → Resources, asigna mínimo 4GB de RAM

## Limpieza

Para eliminar todo y empezar de cero:
```bash
docker-compose down -v
docker system prune -a
docker-compose up -d
```
