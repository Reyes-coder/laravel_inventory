# Sistema de Roles para Gestión de Productos

Este documento describe la implementación del sistema de roles (Admin y User) para la gestión de productos en la aplicación.

## 📋 Descripción

El sistema de roles permite:

- **Usuarios (User)**: Pueden crear, editar y eliminar solo sus propios productos
- **Administradores (Admin)**: Pueden crear, editar y eliminar cualquier producto, así como ver los productos creados por todos los usuarios

## 🗂️ Cambios Realizados

### 1. **Migraciones Creadas**

#### `2026_01_29_000000_add_user_and_role_to_productos_table.php`
- Agrega columna `user_id` (relación con la tabla users)
- Agrega columna `role` (enum: admin, user)

#### `2026_01_29_000001_add_role_to_users_table.php`
- Agrega columna `role` a tabla users (enum: admin, user)
- Por defecto, los nuevos usuarios tienen rol `user`

#### `2026_01_29_000002_set_first_user_as_admin.php`
- Asigna automáticamente el rol `admin` al usuario con ID 1

### 2. **Modelos Actualizados**

#### `app/Models/User.php`
```php
// Propiedades añadidas
protected $fillable = [..., 'role'];

// Métodos añadidos
public function productos() // Relación con productos
public function isAdmin()   // Verifica si es admin
public function isUser()    // Verifica si es user
```

#### `app/Models/Producto.php`
```php
// Propiedades añadidas
protected $fillable = [..., 'user_id', 'role'];

// Relación añadida
public function user() // Relación con el usuario propietario
```

### 3. **Policy Creada**

#### `app/Policies/ProductoPolicy.php`
Controla los permisos de:
- **view**: Admin ve todo, User ve solo sus productos
- **create**: Todos pueden crear
- **update**: Admin actualiza cualquier producto, User solo los suyos
- **delete**: Admin elimina cualquier producto, User solo los suyos

### 4. **Controlador Actualizado**

#### `app/Http/Controllers/Web/ProductoController.php`
- En `index()`: Admin ve todos los productos, User ve solo los suyos
- En `store()`: Asigna automáticamente el `user_id` y `role` del usuario autenticado
- En `show()`, `edit()`, `destroy()`: Utiliza autorización con `@can` directives

### 5. **Vistas Actualizadas**

#### `resources/views/productos/index.blade.php`
- Admins ven una columna adicional "Propietario" con código de colores:
  - Púrpura: Productos del admin actual
  - Naranja: Productos de otros usuarios
- Solo muestra botones de editar/eliminar si el usuario tiene permisos

#### `resources/views/productos/show.blade.php`
- Muestra el propietario del producto (solo para admins)
- Solo muestra botones de editar/eliminar si el usuario tiene permisos

### 6. **Service Provider Actualizado**

#### `app/Providers/AppServiceProvider.php`
- Registra la `ProductoPolicy` para el modelo `Producto`

### 7. **Comando Artisan Creado**

#### `app/Console/Commands/AssignRole.php`
- Permite asignar roles a usuarios desde la consola

## 🚀 Cómo Usar

### Ejecutar Migraciones

```bash
php artisan migrate
```

### Asignar Roles a Usuarios

#### Opción 1: Usar el comando artisan

```bash
# Convertir usuario con ID 2 en admin
php artisan user:assign-role 2 admin

# Convertir usuario con ID 3 en user
php artisan user:assign-role 3 user
```

#### Opción 2: Usar tinker

```bash
php artisan tinker
User::find(2)->update(['role' => 'admin']);
exit
```

### Verificar Roles de Usuarios

```bash
php artisan tinker
User::all(['id', 'name', 'email', 'role']);
exit
```

## 📊 Flujo de Autorización

### Crear Producto
```
Cualquier usuario autenticado puede crear un producto
↓
Se asigna automáticamente user_id = usuario actual
Se asigna automáticamente role = rol del usuario
```

### Ver Producto
```
Admin: Puede ver cualquier producto
User: Solo puede ver sus propios productos
↓
Si intenta acceder a uno que no es suyo: 403 Forbidden
```

### Editar/Eliminar Producto
```
Admin: Puede editar/eliminar cualquier producto
User: Solo puede editar/eliminar sus propios productos
↓
Si intenta acceder a uno que no es suyo: 403 Forbidden
```

## 🔐 Seguridad

- La autorización se verifica en el controlador usando `$this->authorize()`
- Las vistas usan `@can` directives para mostrar/ocultar botones
- Los permisos se validan tanto en backend como en frontend
- La relación `user_id` evita que usuarios vean/modifiquen productos de otros

## 📝 Base de Datos

### Tabla `productos`
```sql
ALTER TABLE productos ADD COLUMN user_id BIGINT UNSIGNED NULLABLE;
ALTER TABLE productos ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user';
ALTER TABLE productos ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
```

### Tabla `users`
```sql
ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user' AFTER email;
```

## 🎯 Ejemplos de Uso

### Usuario Regular (user)
1. Accede a `/productos`
2. Solo ve sus propios productos
3. Puede crear nuevos productos
4. Solo puede editar/eliminar los que él creó
5. No ve la columna "Propietario"

### Administrador (admin)
1. Accede a `/productos`
2. Ve todos los productos de todos los usuarios
3. Puede crear nuevos productos
4. Puede editar/eliminar cualquier producto
5. Ve la columna "Propietario" con identificación de quién creó cada producto
6. Los productos propios están marcados en púrpura
7. Los productos de otros usuarios están marcados en naranja

## 🔧 Mantenimiento

### Cambiar rol de usuario después del registro

```bash
php artisan user:assign-role 5 admin
```

### Restablecer todos los usuarios a rol 'user'

```bash
php artisan tinker
User::query()->update(['role' => 'user']);
exit
```

### Ver estadísticas de usuarios por rol

```bash
php artisan tinker
User::selectRaw('role, count(*) as count')->groupBy('role')->get();
exit
```

## ✅ Checklist de Verificación

- [ ] Ejecutar migraciones: `php artisan migrate`
- [ ] Crear usuarios de prueba
- [ ] Asignar un admin: `php artisan user:assign-role 1 admin`
- [ ] Probar crear productos como admin
- [ ] Probar crear productos como user
- [ ] Verificar que admin ve todos los productos
- [ ] Verificar que user solo ve los suyos
- [ ] Probar editar/eliminar con permisos correctos
- [ ] Probar intentar acceder a producto de otro (debe dar 403)

