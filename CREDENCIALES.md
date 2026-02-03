# 🔑 CREDENCIALES DE ACCESO - Sistema de Inventario

## ✅ Usuarios Listos para Usar

### 👑 ADMIN (Samuel Reyes Castro)
```
Email:      samuelreyescastro456@gmail.com
Contraseña: Admin@2026!
Rol:        admin
Permisos:   Acceso total al sistema
```

### 👤 USER 1 (Juan Pérez)
```
Email:      juan.perez@example.com
Contraseña: Juan@Perez123
Rol:        user
Permisos:   Solo ver/editar sus propios productos
```

### 👤 USER 2 (María García)
```
Email:      maria.garcia@example.com
Contraseña: Maria@Garcia456
Rol:        user
Permisos:   Solo ver/editar sus propios productos
```

---

## 🚀 Cómo Iniciar

### Sin Docker:
```bash
php artisan serve
```
Acceso: `http://localhost:8000`

### Con Docker:
```bash
chmod +x docker-init.sh
./docker-init.sh
```
Acceso: `http://localhost`

---

## ✨ Características Disponibles

- ✅ **Conectar otra entidad (Categoría)**
  - CRUD completo de categorías
  - Relación con productos
  - Validación de nombres únicos

- ✅ **Implementar imágenes a productos**
  - Subida de múltiples imágenes
  - Imagen principal automática
  - Validación MIME (JPEG, PNG, GIF, SVG)
  - Límite de 2MB por imagen

- ✅ **Tests automatizados**
  - 15+ tests funcionales
  - Cobertura de autorización
  - Validación de permisos

- ✅ **Dockerización**
  - PHP 8.2-FPM
  - Nginx + MySQL
  - phpMyAdmin incluido
  - Volúmenes persistentes

---

## 📱 URLs Importantes

| Servicio | URL |
|----------|-----|
| Aplicación | http://localhost:8000 (sin Docker) o http://localhost (con Docker) |
| phpMyAdmin | http://localhost:8080 |
| API Docs | http://localhost:8000/api/docs (si está configurada) |

---

## 🔧 Solución de Problemas

**Error: "Las credenciales no me dejan entrar"**

Solución:
1. Verificar que la base de datos está actualizada: `php artisan migrate --seed`
2. Usar el email CORRECTO: `samuelreyescastro456@gmail.com` (con "l")
3. Verificar mayúsculas/minúsculas en la contraseña
4. Limpiar caché: `php artisan cache:clear`

**Error: "Puerto 8000 ya está en uso"**

Usar otro puerto:
```bash
php artisan serve --port=8001
```

**Error: "Base de datos vacía"**

Ejecutar seeders:
```bash
php artisan db:seed
```

---

**¡Sistema listo para usar! 🎉**
