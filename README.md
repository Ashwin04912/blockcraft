# BlockCraft — Dynamic UI Blocks System

A Laravel-based admin panel for building **server-driven UI** through reusable, reorderable content blocks. Admins can create, edit, reorder, and delete blocks from a dashboard, and the front end renders them dynamically based on configuration stored in the database.

---



## Tech Stack

| Layer            | Technology                          |
|------------------|--------------------------------------|
| Backend          | Laravel (PHP 8.5)                   |
| Frontend         | Blade Components, Bootstrap 5 (CDN) |
| Interactivity    | Vanilla JavaScript                  |
| Database         | MySQL 8.0                           |
| Local Dev Env    | Laravel Sail (Docker)               |

---

## Requirements

- **Docker Desktop** (Mac/Windows) or **Docker Engine + Docker Compose** (Linux) — **must be installed and running** before any `sail` command will work. Laravel Sail is just a thin wrapper around Docker Compose, so without Docker running you'll get errors like `Cannot connect to the Docker daemon`.
- Composer

**Check Docker is installed and running before proceeding:**
```bash
docker --version
docker compose version
```
If both return version numbers, you're good. If you get a "command not found" error, install Docker Desktop from [docker.com](https://www.docker.com/products/docker-desktop/) first. If Docker is installed but you get a "Cannot connect to the Docker daemon" error, open Docker Desktop (or run `sudo systemctl start docker` on Linux) and wait for it to fully start before continuing.

---

## Getting Started

```bash
# 1. Clone the repository
git clone <repo-url> 
cd blockcraft

# 2. Copy environment file
cp .env.example .env

# 3. Install PHP dependencies
composer install

# 4. Start the Sail environment
./vendor/bin/sail up -d

# 5. Generate application key
./vendor/bin/sail artisan key:generate

# 6. Run migrations
./vendor/bin/sail artisan migrate

# 7. (Optional) Seed sample data
./vendor/bin/sail artisan db:seed
```

The app will be available at **http://localhost**.

> **Note:** Make sure nothing else on your machine is bound to port `3306` (MySQL) or `80` before running `sail up -d`. If you hit a port conflict, find the process with `sudo lsof -i :3306` and stop it before retrying.

---



## Troubleshooting

**Port 3306 or 80 already in use when running `sail up -d`:**
```bash
sudo lsof -i : <3306 or 80>
sudo kill -9 <PID>
./vendor/bin/sail up -d
```

**Container changes not reflecting:**
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```

