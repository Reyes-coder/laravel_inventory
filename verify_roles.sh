#!/bin/bash

# Script de verificación del sistema de roles

echo "=== Verificación del Sistema de Roles ==="
echo ""

cd /home/Cohorte3/Escritorio/inventory

# 1. Verificar que las migraciones se ejecutaron
echo "1️⃣ Verificando migraciones ejecutadas..."
php artisan tinker --execute="
\$migrations = \DB::table('migrations')->pluck('migration');
\$rolesMigrations = \$migrations->filter(fn(\$m) => str_contains(\$m, 'role') || str_contains(\$m, 'user_and_role'));
echo 'Migraciones de roles:' . PHP_EOL;
foreach (\$rolesMigrations as \$m) {
    echo '  ✓ ' . \$m . PHP_EOL;
}
" 2>&1 | grep -E "✓|Migraciones"

# 2. Verificar columnas en la tabla productos
echo ""
echo "2️⃣ Verificando columnas en tabla productos..."
php artisan tinker --execute="
\$columns = \DB::getSchemaBuilder()->getColumns('productos');
\$roleColumns = array_filter(\$columns, fn(\$c) => in_array(\$c['name'], ['user_id', 'role']));
echo 'Columnas de rol en productos:' . PHP_EOL;
foreach (\$roleColumns as \$c) {
    echo '  ✓ ' . \$c['name'] . ' (' . \$c['type'] . ')' . PHP_EOL;
}
" 2>&1 | grep -E "✓|Columnas"

# 3. Verificar columna role en tabla users
echo ""
echo "3️⃣ Verificando columna role en tabla users..."
php artisan tinker --execute="
\$columns = \DB::getSchemaBuilder()->getColumns('users');
\$roleColumn = array_filter(\$columns, fn(\$c) => \$c['name'] === 'role');
if (count(\$roleColumn) > 0) {
    echo '  ✓ Columna role existe en users' . PHP_EOL;
} else {
    echo '  ✗ Columna role NO existe en users' . PHP_EOL;
}
" 2>&1 | grep -E "✓|✗"

# 4. Verificar usuario admin
echo ""
echo "4️⃣ Verificando usuarios con roles asignados..."
php artisan tinker --execute="
\$users = \App\Models\User::select('id', 'name', 'email', 'role')->get();
if (count(\$users) > 0) {
    echo 'Usuarios registrados:' . PHP_EOL;
    foreach (\$users as \$u) {
        echo '  ✓ ID:' . \$u->id . ' | ' . \$u->name . ' (' . \$u->role . ')' . PHP_EOL;
    }
} else {
    echo '  ⓘ No hay usuarios registrados' . PHP_EOL;
}
" 2>&1 | grep -E "✓|ⓘ|Usuarios"

# 5. Verificar si ProductoPolicy existe
echo ""
echo "5️⃣ Verificando ProductoPolicy..."
if [ -f "app/Policies/ProductoPolicy.php" ]; then
    echo "  ✓ ProductoPolicy.php existe"
    grep -q "class ProductoPolicy" app/Policies/ProductoPolicy.php && echo "  ✓ Clase ProductoPolicy definida correctamente"
else
    echo "  ✗ ProductoPolicy.php NO existe"
fi

# 6. Verificar comando AssignRole
echo ""
echo "6️⃣ Verificando comando AssignRole..."
if [ -f "app/Console/Commands/AssignRole.php" ]; then
    echo "  ✓ Comando AssignRole.php existe"
    php artisan list --raw 2>&1 | grep -q "user:assign-role" && echo "  ✓ Comando registrado en artisan"
else
    echo "  ✗ Comando AssignRole.php NO existe"
fi

echo ""
echo "=== Verificación Completada ==="
echo ""
echo "📖 Próximos pasos:"
echo "  1. Crear usuarios de prueba (si no existen)"
echo "  2. Ejecutar: php artisan user:assign-role 1 admin"
echo "  3. Ejecutar: php artisan user:assign-role 2 user"
echo "  4. Acceder a /productos y ver el filtrado por rol"
echo ""
