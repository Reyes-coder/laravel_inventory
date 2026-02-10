# Docker Installation & Usage Guide

## Quick Start

### 1. Build and Start (First Time)
```bash
docker-compose up -d
```

This will:
- Build the PHP image
- Start Nginx
- Apply migrations
- Seed the database

### 2. Access the Application
Open your browser and go to:
```
http://localhost
```

### 3. Stop the Application
```bash
docker-compose down
```

## Credentials

Use these to login:

**Admin:**
- Email: `samuelreyescastro456@gmail.com`
- Password: `Admin@2026!`

**User 1:**
- Email: `juan.perez@example.com`
- Password: `Juan@Perez123`

**User 2:**
- Email: `maria.garcia@example.com`
- Password: `Maria@Garcia456`

## Common Commands

### View Logs
```bash
# PHP logs
docker-compose logs -f app

# Nginx logs
docker-compose logs -f nginx

# All logs
docker-compose logs -f
```

### Execute Commands in Container
```bash
# Run artisan command
docker-compose exec app php artisan <command>

# Run tests
docker-compose exec app php artisan test

# Access shell
docker-compose exec app bash

# Tinker (interactive PHP shell)
docker-compose exec app php artisan tinker
```

### Database Management
```bash
# Reset database
docker-compose exec app php artisan migrate:fresh --seed --force

# Run migrations only
docker-compose exec app php artisan migrate

# View database
docker-compose exec app sqlite3 database/database.sqlite
```

## Troubleshooting

### Port 80 is already in use
Edit `docker-compose.yml`:
```yaml
ports:
  - "8000:80"  # Change to any free port
```
Then access: `http://localhost:8000`

### Application won't start
```bash
# View errors
docker-compose logs app

# Rebuild
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Database issues
```bash
# Reset completely
docker-compose down -v
docker-compose up -d
```

### File permissions error
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache database
```

## Full Lifecycle Management

### Using the Management Script
```bash
bash docker-manage.sh
```

This provides an interactive menu to:
- Build and start containers
- View running containers
- See logs
- Reset database
- Stop/remove containers
- Run tests

### Manual Management
```bash
# Build images (no cache)
docker-compose build --no-cache

# Start (detached)
docker-compose up -d

# Stop
docker-compose stop

# Stop and remove (keeps volumes)
docker-compose down

# Stop and remove everything
docker-compose down -v

# View status
docker-compose ps

# Inspect container
docker-compose inspect app
```

## Performance Tips

1. Allocate enough resources to Docker Desktop:
   - Settings → Resources → Memory: 4GB+
   - Settings → Resources → CPUs: 2+

2. Use named volumes for better performance:
   ```bash
   docker volume create laravel-storage
   ```

3. Disable file sync for vendor and node_modules if doing development

## Architecture

```
┌─────────────────┐
│  Your Browser   │
└────────┬────────┘
         │ http://localhost:80
         ↓
┌─────────────────┐
│  Nginx (proxy)  │
└────────┬────────┘
         │ port 9000 (FastCGI)
         ↓
┌─────────────────┐
│  PHP 8.4-FPM    │
└────────┬────────┘
         │ uses
         ↓
┌─────────────────┐
│  SQLite DB      │
└─────────────────┘
```

## Files Structure

```
.
├── Dockerfile              # PHP application image
├── docker-compose.yml      # Container orchestration
├── docker/
│   ├── nginx/
│   │   └── conf.d/
│   │       └── default.conf  # Nginx configuration
│   └── php/
│       └── local.ini         # PHP configuration
├── database/
│   └── database.sqlite       # SQLite database (created on first run)
└── storage/                  # Application storage (logs, uploads, etc.)
```

## Cleanup

Remove old images:
```bash
docker image prune
```

Remove unused volumes:
```bash
docker volume prune
```

Full cleanup (warning: destructive):
```bash
docker system prune -a --volumes
```

## Support

For issues specific to Laravel, check:
- `storage/logs/laravel.log`
- `docker-compose logs app`

For Nginx issues:
- `docker-compose logs nginx`

For database issues:
```bash
docker-compose exec app sqlite3 database/database.sqlite ".tables"
```
