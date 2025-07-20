# UNAS Fest 2025 - Docker Makefile
# Simplified commands for Docker operations

.PHONY: help build up down restart logs shell mysql redis clean setup dev prod test

# Default target
help: ## Show this help message
	@echo "🐳 UNAS Fest 2025 - Docker Commands"
	@echo "=================================="
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

##@ Setup Commands
setup: ## Run automated setup script
	@echo "🚀 Running automated setup..."
	@./docker-setup.sh

env: ## Copy environment template
	@if [ ! -f .env ]; then \
		echo "📋 Copying .env.example.docker to .env..."; \
		cp .env.example.docker .env; \
		echo "✅ Please edit .env file with your configuration"; \
	else \
		echo "⚠️  .env file already exists"; \
	fi

##@ Docker Operations
build: ## Build Docker images
	@echo "🔨 Building Docker images..."
	@docker-compose build

up: ## Start all services
	@echo "🚀 Starting all services..."
	@docker-compose up -d

down: ## Stop all services
	@echo "🛑 Stopping all services..."
	@docker-compose down

restart: ## Restart all services
	@echo "🔄 Restarting all services..."
	@docker-compose restart

status: ## Show service status
	@echo "📊 Service Status:"
	@docker-compose ps

##@ Development
dev: ## Start development environment
	@echo "🛠️  Starting development environment..."
	@docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build

dev-down: ## Stop development environment
	@echo "🛑 Stopping development environment..."
	@docker-compose -f docker-compose.yml -f docker-compose.dev.yml down

##@ Logs & Monitoring
logs: ## Show all logs
	@docker-compose logs -f

logs-app: ## Show application logs
	@docker-compose logs -f app

logs-mysql: ## Show MySQL logs
	@docker-compose logs -f mysql

logs-redis: ## Show Redis logs
	@docker-compose logs -f redis

health: ## Check application health
	@echo "🏥 Checking application health..."
	@curl -s http://localhost:8000/health | jq . || echo "Health check failed"

##@ Container Access
shell: ## Access application container shell
	@echo "🐚 Accessing application container..."
	@docker-compose exec app bash

mysql: ## Access MySQL CLI
	@echo "🗄️  Accessing MySQL CLI..."
	@docker-compose exec mysql mysql -u root -p

redis: ## Access Redis CLI
	@echo "🔴 Accessing Redis CLI..."
	@docker-compose exec redis redis-cli

##@ Laravel Commands
artisan: ## Run artisan command (usage: make artisan cmd="migrate")
	@docker-compose exec app php artisan $(cmd)

migrate: ## Run database migrations
	@echo "📊 Running database migrations..."
	@docker-compose exec app php artisan migrate

migrate-fresh: ## Fresh migration with seeding
	@echo "🔄 Running fresh migrations with seeding..."
	@docker-compose exec app php artisan migrate:fresh --seed

seed: ## Run database seeders
	@echo "🌱 Running database seeders..."
	@docker-compose exec app php artisan db:seed

cache-clear: ## Clear all caches
	@echo "🧹 Clearing all caches..."
	@docker-compose exec app php artisan cache:clear
	@docker-compose exec app php artisan config:clear
	@docker-compose exec app php artisan route:clear
	@docker-compose exec app php artisan view:clear

cache-optimize: ## Optimize caches for production
	@echo "⚡ Optimizing caches..."
	@docker-compose exec app php artisan config:cache
	@docker-compose exec app php artisan route:cache
	@docker-compose exec app php artisan view:cache

composer-install: ## Install Composer dependencies
	@echo "📦 Installing Composer dependencies..."
	@docker-compose exec app composer install --optimize-autoloader

composer-update: ## Update Composer dependencies
	@echo "🔄 Updating Composer dependencies..."
	@docker-compose exec app composer update

##@ Testing
test: ## Run all tests
	@echo "🧪 Running tests..."
	@docker-compose exec app php artisan test

test-feature: ## Run feature tests
	@echo "🧪 Running feature tests..."
	@docker-compose exec app php artisan test --testsuite=Feature

test-unit: ## Run unit tests
	@echo "🧪 Running unit tests..."
	@docker-compose exec app php artisan test --testsuite=Unit

test-coverage: ## Run tests with coverage
	@echo "🧪 Running tests with coverage..."
	@docker-compose exec app php artisan test --coverage

##@ Database Operations
db-backup: ## Backup database
	@echo "💾 Creating database backup..."
	@docker-compose exec mysql mysqldump -u root -p unas_fest_2025 > backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "✅ Backup created: backup_$(shell date +%Y%m%d_%H%M%S).sql"

db-restore: ## Restore database (usage: make db-restore file="backup.sql")
	@echo "📥 Restoring database from $(file)..."
	@docker-compose exec -T mysql mysql -u root -p unas_fest_2025 < $(file)

##@ Maintenance
clean: ## Clean up Docker resources
	@echo "🧹 Cleaning up Docker resources..."
	@docker-compose down --volumes --remove-orphans
	@docker system prune -f
	@docker volume prune -f

clean-all: ## Clean up everything (including images)
	@echo "🧹 Cleaning up everything..."
	@docker-compose down --volumes --remove-orphans --rmi all
	@docker system prune -af
	@docker volume prune -f

permissions: ## Fix file permissions
	@echo "🔧 Fixing file permissions..."
	@docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
	@docker-compose exec app chmod -R 755 storage bootstrap/cache

##@ Production
prod: ## Start production environment
	@echo "🚀 Starting production environment..."
	@DOCKER_TARGET=production docker-compose up -d --build

prod-deploy: ## Deploy to production
	@echo "🚀 Deploying to production..."
	@git pull origin master
	@DOCKER_TARGET=production docker-compose up -d --build
	@docker-compose exec app composer install --no-dev --optimize-autoloader
	@docker-compose exec app php artisan migrate --force
	@docker-compose exec app php artisan config:cache
	@docker-compose exec app php artisan route:cache
	@docker-compose exec app php artisan view:cache

##@ Information
info: ## Show system information
	@echo "ℹ️  System Information:"
	@echo "Docker version: $(shell docker --version)"
	@echo "Docker Compose version: $(shell docker-compose --version)"
	@echo "Available memory: $(shell free -h | grep '^Mem:' | awk '{print $$2}' 2>/dev/null || echo 'N/A')"
	@echo "Disk space: $(shell df -h . | tail -1 | awk '{print $$4}' 2>/dev/null || echo 'N/A')"

urls: ## Show application URLs
	@echo "🌐 Application URLs:"
	@echo "Main Application: http://localhost:8000"
	@echo "Health Check: http://localhost:8000/health"
	@echo "phpMyAdmin (dev): http://localhost:8080"
	@echo "MailHog (dev): http://localhost:8025"
	@echo "Redis Commander (dev): http://localhost:8081"
