# Caturnawa UNAS FEST 2025 - Laravel Development Makefile
# by Tamas

.PHONY: help setup build dev serve hot migrate test clean check info

# Default target
help: ## Show this help message
	@echo "🚀 Caturnawa UNAS FEST 2025 - Laravel Development Commands"
	@echo "by Tamas"
	@echo "=========================================="
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

##@ Setup Commands
setup: ## Run interactive setup script
	@echo "🚀 Running interactive setup..."
	@./start.sh

env: ## Copy environment template
	@if [ ! -f .env ]; then \
		echo "📋 Copying .env.example to .env..."; \
		cp .env.example .env; \
		echo "✅ Please edit .env file with your configuration"; \
	else \
		echo "⚠️  .env file already exists"; \
	fi

##@ Development Operations
build: ## Build frontend assets
	@echo "🔨 Building frontend assets..."
	@npm run build

dev: ## Start development servers
	@echo "🚀 Starting development servers..."
	@npm run dev:full

serve: ## Start Laravel development server
	@echo "🚀 Starting Laravel server..."
	@php artisan serve --host=0.0.0.0 --port=8000

hot: ## Start Vite dev server
	@echo "⚡ Starting Vite dev server..."
	@npm run hot

##@ Database Operations
migrate: ## Run database migrations
	@echo "📊 Running database migrations..."
	@php artisan migrate

migrate-fresh: ## Fresh migration with seeding
	@echo "🔄 Running fresh migrations with seeding..."
	@php artisan migrate:fresh --seed

seed: ## Run database seeders
	@echo "🌱 Running database seeders..."
	@php artisan db:seed

db-status: ## Show migration status
	@echo "📊 Migration Status:"
	@php artisan migrate:status

##@ Logs & Monitoring
logs: ## Show Laravel logs
	@echo "📋 Showing Laravel logs..."
	@tail -f storage/logs/laravel.log

health: ## Check application health
	@curl -s http://localhost:8000/health | head -10

##@ Database Access
mysql: ## Access MySQL CLI
	@echo "🗄️  Accessing MySQL CLI..."
	@mysql -h 127.0.0.1 -u root -p

tinker: ## Access Laravel Tinker
	@echo "🔧 Starting Laravel Tinker..."
	@php artisan tinker

##@ Laravel Commands
artisan: ## Run artisan command (usage: make artisan cmd="migrate")
	@php artisan $(cmd)

cache-clear: ## Clear all caches
	@echo "🧹 Clearing all caches..."
	@php artisan cache:clear
	@php artisan config:clear
	@php artisan route:clear
	@php artisan view:clear

cache-optimize: ## Optimize caches for production
	@echo "⚡ Optimizing caches..."
	@php artisan config:cache
	@php artisan route:cache
	@php artisan view:cache

key-generate: ## Generate application key
	@echo "🔑 Generating application key..."
	@php artisan key:generate

storage-link: ## Create storage link
	@echo "🔗 Creating storage link..."
	@php artisan storage:link

##@ Dependencies
composer-install: ## Install Composer dependencies
	@echo "📦 Installing Composer dependencies..."
	@composer install --optimize-autoloader

composer-update: ## Update Composer dependencies
	@echo "🔄 Updating Composer dependencies..."
	@composer update

npm-install: ## Install NPM dependencies
	@echo "📦 Installing NPM dependencies..."
	@npm install

npm-update: ## Update NPM dependencies
	@echo "🔄 Updating NPM dependencies..."
	@npm update

##@ Testing
test: ## Run all tests
	@echo "🧪 Running tests..."
	@php artisan test

test-feature: ## Run feature tests
	@echo "🧪 Running feature tests..."
	@php artisan test --testsuite=Feature

test-unit: ## Run unit tests
	@echo "🧪 Running unit tests..."
	@php artisan test --testsuite=Unit

test-coverage: ## Run tests with coverage
	@echo "🧪 Running tests with coverage..."
	@php artisan test --coverage

##@ Backup & Restore
db-backup: ## Backup database
	@echo "💾 Creating database backup..."
	@mysqldump -h 127.0.0.1 -u root -p unas_fest_2025 > backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "✅ Backup created: backup_$(shell date +%Y%m%d_%H%M%S).sql"

db-restore: ## Restore database (usage: make db-restore file="backup.sql")
	@echo "📥 Restoring database from $(file)..."
	@mysql -h 127.0.0.1 -u root -p unas_fest_2025 < $(file)

##@ Maintenance
clean: ## Clean up cache and temporary files
	@echo "🧹 Cleaning up cache and temporary files..."
	@php artisan cache:clear
	@php artisan config:clear
	@php artisan route:clear
	@php artisan view:clear
	@rm -rf node_modules/.cache
	@rm -rf public/hot

clean-all: ## Clean up everything (cache, dependencies, build files)
	@echo "🧹 Cleaning up everything..."
	@php artisan cache:clear
	@php artisan config:clear
	@php artisan route:clear
	@php artisan view:clear
	@rm -rf node_modules
	@rm -rf vendor
	@rm -rf public/build
	@rm -rf public/hot

permissions: ## Fix file permissions (Linux/macOS)
	@echo "🔧 Fixing file permissions..."
	@chmod -R 775 storage bootstrap/cache
	@chmod -R 755 public

##@ Production
prod-build: ## Build for production
	@echo "🚀 Building for production..."
	@npm run build
	@composer install --no-dev --optimize-autoloader
	@php artisan config:cache
	@php artisan route:cache
	@php artisan view:cache

prod-deploy: ## Deploy to production
	@echo "🚀 Deploying to production..."
	@git pull origin master
	@composer install --no-dev --optimize-autoloader
	@npm ci
	@npm run build
	@php artisan migrate --force
	@php artisan config:cache
	@php artisan route:cache
	@php artisan view:cache

##@ Information
info: ## Show system information
	@echo "ℹ️  System Information:"
	@echo "PHP version: $(shell php --version | head -1)"
	@echo "Composer version: $(shell composer --version 2>/dev/null || echo 'Not installed')"
	@echo "Node.js version: $(shell node --version 2>/dev/null || echo 'Not installed')"
	@echo "NPM version: $(shell npm --version 2>/dev/null || echo 'Not installed')"
	@echo "Laravel version: $(shell php artisan --version 2>/dev/null || echo 'Not available')"
	@echo "Available memory: $(shell free -h | grep '^Mem:' | awk '{print $$2}' 2>/dev/null || echo 'N/A')"
	@echo "Disk space: $(shell df -h . | tail -1 | awk '{print $$4}' 2>/dev/null || echo 'N/A')"

check: ## Check system requirements
	@echo "🔍 Checking system requirements..."
	@./start.sh

urls: ## Show application URLs
	@echo "🌐 Application URLs:"
	@echo "Main Application: http://localhost:8000"
	@echo "Health Check: http://localhost:8000/health"