# 📡 API CRUD de Productos - Documentación

## ℹ️ Información General

La API de productos está **sin protección** y es completamente pública. No requiere autenticación para acceder.

**Base URL**: `http://localhost:8000/api`

---

## 📚 Endpoints Disponibles

### 1. **Obtener todos los productos**
```
GET /api/productos
```

**Respuesta (200 OK)**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Laptop",
      "description": "Laptop de 15 pulgadas",
      "category": "Electrónica",
      "price": "999.99",
      "stock": 5,
      "sku": "LAP-001",
      "active": true,
      "user_id": 1,
      "role": "admin",
      "created_at": "2026-01-29T10:00:00.000000Z",
      "updated_at": "2026-01-29T10:00:00.000000Z"
    }
  ],
  "message": "Productos obtenidos correctamente"
}
```

---

### 2. **Crear un nuevo producto**
```
POST /api/productos
```

**Headers requeridos**:
```
Content-Type: application/json
```

**Body (JSON)**:
```json
{
  "name": "Monitor 4K",
  "description": "Monitor ultra HD 4K de 27 pulgadas",
  "category": "Electrónica",
  "price": 599.99,
  "stock": 10,
  "sku": "MON-001",
  "active": true
}
```

**Respuesta (201 Created)**:
```json
{
  "success": true,
  "data": {
    "name": "Monitor 4K",
    "description": "Monitor ultra HD 4K de 27 pulgadas",
    "category": "Electrónica",
    "price": "599.99",
    "stock": 10,
    "sku": "MON-001",
    "active": true,
    "user_id": 1,
    "role": "admin",
    "updated_at": "2026-01-29T12:00:00.000000Z",
    "created_at": "2026-01-29T12:00:00.000000Z",
    "id": 15
  },
  "message": "Producto creado exitosamente"
}
```

---

### 3. **Obtener un producto específico**
```
GET /api/productos/{id}
```

**Ejemplo**:
```
GET /api/productos/1
```

**Respuesta (200 OK)**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Laptop",
    "description": "Laptop de 15 pulgadas",
    "category": "Electrónica",
    "price": "999.99",
    "stock": 5,
    "sku": "LAP-001",
    "active": true,
    "user_id": 1,
    "role": "admin",
    "created_at": "2026-01-29T10:00:00.000000Z",
    "updated_at": "2026-01-29T10:00:00.000000Z"
  },
  "message": "Producto obtenido correctamente"
}
```

---

### 4. **Actualizar un producto**
```
PUT /api/productos/{id}
```

**Ejemplo**:
```
PUT /api/productos/1
```

**Headers requeridos**:
```
Content-Type: application/json
```

**Body (JSON)** - Puedes actualizar uno o varios campos:
```json
{
  "price": 899.99,
  "stock": 3,
  "active": false
}
```

**Respuesta (200 OK)**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Laptop",
    "description": "Laptop de 15 pulgadas",
    "category": "Electrónica",
    "price": "899.99",
    "stock": 3,
    "sku": "LAP-001",
    "active": false,
    "user_id": 1,
    "role": "admin",
    "created_at": "2026-01-29T10:00:00.000000Z",
    "updated_at": "2026-01-29T12:00:00.000000Z"
  },
  "message": "Producto actualizado exitosamente"
}
```

---

### 5. **Eliminar un producto**
```
DELETE /api/productos/{id}
```

**Ejemplo**:
```
DELETE /api/productos/1
```

**Respuesta (200 OK)**:
```json
{
  "success": true,
  "message": "Producto eliminado exitosamente"
}
```

---

## 🧪 Ejemplos con cURL

### Obtener todos los productos
```bash
curl -X GET "http://localhost:8000/api/productos"
```

### Crear un producto
```bash
curl -X POST "http://localhost:8000/api/productos" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Teclado Mecánico",
    "description": "Teclado gaming RGB",
    "category": "Accesorios",
    "price": 149.99,
    "stock": 20,
    "sku": "KEY-001",
    "active": true
  }'
```

### Obtener un producto específico
```bash
curl -X GET "http://localhost:8000/api/productos/1"
```

### Actualizar un producto
```bash
curl -X PUT "http://localhost:8000/api/productos/1" \
  -H "Content-Type: application/json" \
  -d '{
    "price": 129.99,
    "stock": 15
  }'
```

### Eliminar un producto
```bash
curl -X DELETE "http://localhost:8000/api/productos/1"
```

---

## 🧪 Ejemplos con Postman

### Pasos:
1. Abre Postman
2. Crea una nueva Request
3. Selecciona el método (GET, POST, PUT, DELETE)
4. Ingresa la URL: `http://localhost:8000/api/productos` (o con ID específico)
5. Para POST/PUT, ve a "Body" → "raw" → selecciona "JSON" y pega el JSON

---

## 🧪 Ejemplos con JavaScript/Fetch

### Obtener todos los productos
```javascript
fetch('http://localhost:8000/api/productos')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Crear un producto
```javascript
fetch('http://localhost:8000/api/productos', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: 'Mouse Gamer',
    description: 'Mouse con DPI ajustable',
    category: 'Accesorios',
    price: 49.99,
    stock: 30,
    sku: 'MOUSE-001',
    active: true
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Actualizar un producto
```javascript
fetch('http://localhost:8000/api/productos/1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    price: 1099.99,
    stock: 2
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Eliminar un producto
```javascript
fetch('http://localhost:8000/api/productos/1', {
  method: 'DELETE'
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## ✅ Reglas de Validación

### Crear/Actualizar Producto

| Campo | Reglas |
|-------|--------|
| **name** | Requerido, string, máx 255 caracteres |
| **description** | Requerido, string |
| **category** | Requerido, string, máx 100 caracteres |
| **price** | Requerido, número, mínimo 0 |
| **stock** | Requerido, entero, mínimo 0 |
| **sku** | Requerido, string, único en la BD |
| **active** | Opcional, booleano (true/false) |

---

## ⚠️ Códigos de Error

| Código | Descripción |
|--------|-------------|
| **200** | OK - Operación exitosa |
| **201** | Created - Recurso creado exitosamente |
| **422** | Unprocessable Entity - Error de validación |
| **404** | Not Found - Recurso no encontrado |

### Ejemplo de Error (422):
```json
{
  "success": false,
  "errors": {
    "sku": ["El campo sku debe ser único."]
  },
  "message": "Error de validación"
}
```

---

## 🔒 Seguridad

⚠️ **IMPORTANTE**: Esta API NO tiene protección de autenticación. 

En producción, se recomienda:
- Agregar autenticación (JWT, OAuth2, Sanctum)
- Implementar rate limiting
- Validar CORS
- Usar HTTPS obligatoriamente

---

**Última actualización**: 29 de Enero de 2026
