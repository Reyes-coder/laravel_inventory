#!/bin/bash
set -e

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
CYAN='\033[1;36m'
NC='\033[0m' # No Color

echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo -e "${YELLOW}  Database Reset Script${NC}"
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo ""

echo -e "${BLUE}→ Clearing all Laravel caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo -e "${BLUE}→ Removing old database...${NC}"
rm -f database/database.sqlite

echo -e "${BLUE}→ Creating fresh database and running migrations...${NC}"
touch database/database.sqlite
php artisan migrate:fresh --seed --force

echo -e "${BLUE}→ Clearing caches again after migration...${NC}"
php artisan config:clear
php artisan cache:clear

echo ""
echo -e "${GREEN}✓ Database reset completed successfully!${NC}"
echo ""
echo -e "${CYAN}════════════════════════════════════════${NC}"
echo -e "${CYAN}  TEST CREDENTIALS${NC}"
echo -e "${CYAN}════════════════════════════════════════${NC}"
echo ""
echo -e "${YELLOW}Admin Account:${NC}"
echo -e "  Email: ${GREEN}samuelreyescastro456@gmail.com${NC}"
echo -e "  Password: ${GREEN}Admin@2026!${NC}"
echo ""
echo -e "${YELLOW}User Account 1:${NC}"
echo -e "  Email: ${GREEN}juan.perez@example.com${NC}"
echo -e "  Password: ${GREEN}Juan@Perez123${NC}"
echo ""
echo -e "${YELLOW}User Account 2:${NC}"
echo -e "  Email: ${GREEN}maria.garcia@example.com${NC}"
echo -e "  Password: ${GREEN}Maria@Garcia456${NC}"
echo ""
