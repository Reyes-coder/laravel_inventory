#!/bin/bash

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
CYAN='\033[1;36m'
NC='\033[0m'

echo -e "${CYAN}╔════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║    INVENTORY APP - STATUS CHECK        ║${NC}"
echo -e "${CYAN}╚════════════════════════════════════════╝${NC}"
echo ""

# 1. Check database
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}DATABASE STATUS${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

if [ -f "database/database.sqlite" ]; then
  echo -e "${GREEN}✓${NC} Database file exists"
  SIZE=$(du -sh database/database.sqlite | cut -f1)
  echo -e "  Size: $SIZE"
else
  echo -e "${RED}✗${NC} Database file NOT found"
fi
echo ""

# 2. Check users
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}USERS IN SYSTEM${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

php artisan tinker << 'PHPEOF'
$users = \App\Models\User::all(['id', 'name', 'email', 'role', 'email_verified_at']);
if ($users->count() > 0) {
  foreach ($users as $user) {
    $verified = $user->email_verified_at ? '✓ Verified' : '✗ Not Verified';
    echo "  {$user->name} ({$user->role})\n";
    echo "    Email: {$user->email}\n";
    echo "    Status: $verified\n\n";
  }
} else {
  echo "  No users found!\n";
}
PHPEOF
echo ""

# 3. Check important files
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}IMPORTANT FILES${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

files=(
  "database/seeders/DatabaseSeeder.php:Seeder with credentials"
  "reset-db.sh:Database reset script"
  "diagnose-auth.sh:Authentication diagnostic"
  "SOLUCION_PERMANENTE.md:Solution documentation"
  "AUTENTICACION_PERMANENTE.md:Authentication documentation"
)

for file in "${files[@]}"; do
  IFS=':' read -r path description <<< "$file"
  if [ -f "$path" ]; then
    echo -e "${GREEN}✓${NC} $description"
  else
    echo -e "${RED}✗${NC} $description (NOT FOUND)"
  fi
done
echo ""

# 4. Check environment
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}ENVIRONMENT${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

php artisan tinker << 'PHPEOF'
echo "  Environment: " . config('app.env') . "\n";
echo "  Debug Mode: " . (config('app.debug') ? 'ON' : 'OFF') . "\n";
echo "  Database: " . config('database.default') . "\n";
PHPEOF
echo ""

# 5. Quick commands
echo -e "${CYAN}╔════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║       QUICK COMMANDS TO USE            ║${NC}"
echo -e "${CYAN}╚════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Start the development server:${NC}"
echo -e "  ${GREEN}php artisan serve${NC}"
echo ""
echo -e "${YELLOW}Reset database completely:${NC}"
echo -e "  ${GREEN}bash reset-db.sh${NC}"
echo ""
echo -e "${YELLOW}Run tests:${NC}"
echo -e "  ${GREEN}php artisan test tests/Feature/ProductImageTest.php${NC}"
echo ""
echo -e "${YELLOW}Diagnose authentication:${NC}"
echo -e "  ${GREEN}bash diagnose-auth.sh${NC}"
echo ""

echo -e "${CYAN}╔════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║        CREDENTIALS TO USE              ║${NC}"
echo -e "${CYAN}╚════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Admin:${NC}"
echo -e "  Email: ${GREEN}samuelreyescastro456@gmail.com${NC}"
echo -e "  Password: ${GREEN}Admin@2026!${NC}"
echo ""
echo -e "${YELLOW}User 1:${NC}"
echo -e "  Email: ${GREEN}juan.perez@example.com${NC}"
echo -e "  Password: ${GREEN}Juan@Perez123${NC}"
echo ""
echo -e "${YELLOW}User 2:${NC}"
echo -e "  Email: ${GREEN}maria.garcia@example.com${NC}"
echo -e "  Password: ${GREEN}Maria@Garcia456${NC}"
echo ""
