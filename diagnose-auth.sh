#!/bin/bash

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
CYAN='\033[1;36m'
NC='\033[0m'

echo -e "${CYAN}════════════════════════════════════════${NC}"
echo -e "${CYAN}  Authentication Diagnostic Tool${NC}"
echo -e "${CYAN}════════════════════════════════════════${NC}"
echo ""

echo -e "${BLUE}1. Checking database connection...${NC}"
php artisan tinker --execute='dd(\DB::connection()->getPdo() ? "✓ Database connected" : "✗ Connection failed");' 2>/dev/null || echo -e "${RED}✗ Failed to connect${NC}"
echo ""

echo -e "${BLUE}2. Checking users in database...${NC}"
php artisan tinker << 'EOF'
$users = \App\Models\User::count();
echo "Total users: $users\n";
if ($users > 0) {
  \App\Models\User::all(['id', 'name', 'email', 'role'])->each(fn($u) => echo "  - {$u->name} ({$u->email}) - Role: {$u->role}\n");
}
EOF
echo ""

echo -e "${BLUE}3. Testing password verification...${NC}"
php artisan tinker << 'EOF'
$user = \App\Models\User::where('email', 'samuelreyescastro456@gmail.com')->first();
if ($user) {
  $match = Hash::check('Admin@2026!', $user->password);
  echo "Admin password verification: " . ($match ? "✓ PASS" : "✗ FAIL") . "\n";
} else {
  echo "✗ Admin user not found\n";
}
EOF
echo ""

echo -e "${BLUE}4. Checking Laravel configuration...${NC}"
php artisan tinker << 'EOF'
echo "APP_ENV: " . config('app.env') . "\n";
echo "AUTH_GUARD: " . config('auth.defaults.guard') . "\n";
echo "AUTH_PROVIDER: " . config('auth.guards.web.provider') . "\n";
echo "DB_CONNECTION: " . config('database.default') . "\n";
EOF
echo ""

echo -e "${BLUE}5. Cache status...${NC}"
php artisan config:show cache.default 2>/dev/null || echo "Cache configuration available"
echo ""

echo -e "${CYAN}════════════════════════════════════════${NC}"
echo -e "${GREEN}Diagnostic report complete!${NC}"
echo -e "${CYAN}════════════════════════════════════════${NC}"
