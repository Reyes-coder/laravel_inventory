#!/bin/bash

# Script de demostración del sistema de roles

echo "╔════════════════════════════════════════════════════════════════════════╗"
echo "║     SISTEMA DE GESTIÓN DE PRODUCTOS CON ROLES IMPLEMENTADO ✅         ║"
echo "╚════════════════════════════════════════════════════════════════════════╝"
echo ""

cd /home/Cohorte3/Escritorio/inventory

echo "📋 USUARIOS DISPONIBLES PARA PRUEBA:"
echo "════════════════════════════════════════════════════════════════════════"
echo ""
php artisan tinker --execute="
\$users = \App\Models\User::select('id', 'name', 'email', 'role')->get();
foreach (\$users as \$u) {
    echo 'ID: ' . \$u->id . ' | ';
    if (\$u->role === 'admin') {
        echo '👑 ADMIN';
    } else {
        echo '👤 USER';
    }
    echo ' | ' . \$u->name . ' | ' . \$u->email . PHP_EOL;
}
" 2>&1 | grep -E "^ID:|admin|user"

echo ""
echo "════════════════════════════════════════════════════════════════════════"
echo ""

echo "🎨 LAYOUTS CONFIGURADOS:"
echo "════════════════════════════════════════════════════════════════════════"
echo ""
echo "├─ 🔐 LAYOUT ADMIN (Usuarios con rol 'admin')"
echo "│  ├─ Tema: Oscuro"
echo "│  ├─ Colores: Slate-950, Slate-900, Rojo, Azul"
echo "│  ├─ Sidebar: Panel Admin"
echo "│  ├─ Contenido: Todos los Productos (acceso total)"
echo "│  └─ Funciones: Ver/Editar/Eliminar cualquier producto"
echo ""
echo "└─ ✨ LAYOUT USER (Usuarios con rol 'user')"
echo "   ├─ Tema: Claro"
echo "   ├─ Colores: Emerald-50, Emerald-600, Verde, Ámbar"
echo "   ├─ Sidebar: Mi Panel"
echo "   ├─ Contenido: Mis Productos (solo propios)"
echo "   └─ Funciones: Ver/Editar/Eliminar solo propios productos"
echo ""

echo "════════════════════════════════════════════════════════════════════════"
echo ""

echo "🔐 CONTROL DE ACCESO:"
echo "════════════════════════════════════════════════════════════════════════"
echo ""
echo "╔════════════════════════╦═════════════════════╦═════════════════════╗"
echo "║     ACCIÓN/PERMISO     ║        ADMIN        ║         USER        ║"
echo "╠════════════════════════╬═════════════════════╬═════════════════════╣"
echo "║ Ver todos los productos║          ✅         ║          ❌         ║"
echo "║ Ver solo propios       ║          ✅         ║          ✅         ║"
echo "║ Crear producto         ║          ✅         ║          ✅         ║"
echo "║ Editar propios         ║          ✅         ║          ✅         ║"
echo "║ Editar de otros        ║          ✅         ║          ❌         ║"
echo "║ Eliminar propios       ║          ✅         ║          ✅         ║"
echo "║ Eliminar de otros      ║          ✅         ║          ❌         ║"
echo "║ Ver propietario        ║          ✅         ║          ❌         ║"
echo "║ Dashboard              ║          ✅         ║          ❌         ║"
echo "╚════════════════════════╩═════════════════════╩═════════════════════╝"
echo ""

echo "════════════════════════════════════════════════════════════════════════"
echo ""

echo "🧪 CÓMO PROBAR:"
echo "════════════════════════════════════════════════════════════════════════"
echo ""
echo "1. INICIAR LA APLICACIÓN:"
echo "   $ php artisan serve"
echo "   → Acceder a http://localhost:8000"
echo ""

echo "2. PRUEBA COMO ADMIN (Samuel Reyes Castro):"
echo "   📧 Email: samuereyescastro456@gmail.com"
echo "   🔑 Contraseña: password"
echo ""
echo "   Observar:"
echo "   ✅ Layout OSCURO (Slate/Negro)"
echo "   ✅ Sidebar con '⚙️ Panel Admin'"
echo "   ✅ Link 'Todos los Productos'"
echo "   ✅ Ver TODOS los productos del sistema"
echo "   ✅ Columna 'Propietario' visible"
echo "   ✅ Acceso a Dashboard"
echo ""

echo "3. PRUEBA COMO USER (Juan Pérez):"
echo "   📧 Email: juan.perez@example.com"
echo "   🔑 Contraseña: password"
echo ""
echo "   Observar:"
echo "   ✅ Layout CLARO (Verde Esmeralda)"
echo "   ✅ Sidebar con '👤 Mi Panel'"
echo "   ✅ Link 'Mis Productos'"
echo "   ✅ Ver SOLO los productos propios"
echo "   ✅ Columna 'Propietario' NO visible"
echo "   ✅ Acceso a Perfil"
echo ""

echo "4. CREAR PRODUCTOS:"
echo "   • Admin crea → Aparece en 'Todos los Productos'"
echo "   • Juan crea → Aparece en 'Mis Productos' de Juan"
echo "   • María crea → Aparece en 'Mis Productos' de María"
echo ""

echo "5. CAMBIAR ROLES (Opcional):"
echo "   $ php artisan user:assign-role 2 admin"
echo "   → Convierte a Juan en admin"
echo ""

echo "════════════════════════════════════════════════════════════════════════"
echo ""

echo "📊 INFORMACIÓN TÉCNICA:"
echo "════════════════════════════════════════════════════════════════════════"
echo ""
php artisan tinker --execute="
echo 'Base de datos:' . PHP_EOL;
echo '  • usuarios: ' . \App\Models\User::count() . PHP_EOL;
echo '  • productos: ' . \App\Models\Producto::count() . PHP_EOL;
echo PHP_EOL;
echo 'Distribución por rol:' . PHP_EOL;
\$byRole = \App\Models\User::selectRaw('role, COUNT(*) as count')->groupBy('role')->get();
foreach (\$byRole as \$r) {
    echo '  • ' . \$r->role . ': ' . \$r->count . ' usuario(s)' . PHP_EOL;
}
" 2>&1 | grep -E "Base|usuarios|productos|Distribución|rol|admin|user"

echo ""
echo "════════════════════════════════════════════════════════════════════════"
echo ""
echo "✨ Sistema completamente implementado y listo para usar!"
echo ""
