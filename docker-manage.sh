#!/bin/bash

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
CYAN='\033[1;36m'
NC='\033[0m'

echo -e "${CYAN}╔════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║   Docker Setup & Management Tool      ║${NC}"
echo -e "${CYAN}╚════════════════════════════════════════╝${NC}"
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo -e "${RED}✗ Docker is not installed${NC}"
    echo -e "${YELLOW}Please install Docker Desktop from: https://www.docker.com/products/docker-desktop${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Docker found: $(docker --version)${NC}"
echo ""

# Menu
echo -e "${BLUE}Select an option:${NC}"
echo ""
echo "  1) Build and start Docker containers"
echo "  2) View running containers"
echo "  3) View application logs"
echo "  4) Reset database (keep containers running)"
echo "  5) Stop containers"
echo "  6) Stop and remove everything (clean start)"
echo "  7) Run tests in container"
echo "  8) Access application shell"
echo "  9) Exit"
echo ""

read -p "Enter option (1-9): " choice

case $choice in
    1)
        echo -e "${BLUE}Building and starting containers...${NC}"
        docker-compose build
        docker-compose up -d
        echo ""
        echo -e "${GREEN}✓ Containers started!${NC}"
        echo ""
        echo -e "${CYAN}Access the application at:${NC}"
        echo -e "  ${GREEN}http://localhost${NC}"
        echo ""
        echo -e "${YELLOW}Waiting for services to be ready...${NC}"
        sleep 5
        docker-compose ps
        ;;
    2)
        echo -e "${BLUE}Running containers:${NC}"
        echo ""
        docker-compose ps
        ;;
    3)
        echo -e "${BLUE}Application logs (last 50 lines):${NC}"
        echo ""
        docker-compose logs --tail=50 app
        ;;
    4)
        echo -e "${BLUE}Resetting database...${NC}"
        docker-compose exec -T app php artisan migrate:fresh --seed --force
        echo -e "${GREEN}✓ Database reset complete!${NC}"
        ;;
    5)
        echo -e "${YELLOW}Stopping containers...${NC}"
        docker-compose stop
        echo -e "${GREEN}✓ Containers stopped${NC}"
        ;;
    6)
        echo -e "${RED}⚠️  This will stop and remove all containers and volumes${NC}"
        read -p "Are you sure? (y/n): " confirm
        if [[ $confirm == "y" || $confirm == "Y" ]]; then
            docker-compose down -v
            echo -e "${GREEN}✓ All containers and volumes removed${NC}"
            echo -e "${BLUE}Building fresh containers...${NC}"
            docker-compose build
            docker-compose up -d
            echo -e "${GREEN}✓ Fresh environment ready at: http://localhost${NC}"
        else
            echo -e "${YELLOW}Cancelled${NC}"
        fi
        ;;
    7)
        echo -e "${BLUE}Running tests...${NC}"
        docker-compose exec -T app php artisan test
        ;;
    8)
        echo -e "${BLUE}Accessing application shell...${NC}"
        docker-compose exec app bash
        ;;
    9)
        echo -e "${YELLOW}Exiting...${NC}"
        exit 0
        ;;
    *)
        echo -e "${RED}Invalid option${NC}"
        exit 1
        ;;
esac
