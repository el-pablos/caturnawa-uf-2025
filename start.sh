#!/bin/bash

# =============================================================================
# Caturnawa UNAS FEST 2025 - Interactive Setup Script
# Multi-platform Laravel project setup with auto-detection and auto-fix
# Supports: Linux, macOS, Windows (Git Bash/WSL)
# =============================================================================

# Colors for better UX
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
WHITE='\033[1;37m'
NC='\033[0m' # No Color

# Global variables
PLATFORM=""
DISTRO=""
PACKAGE_MANAGER=""
PHP_VERSION=""
NODE_VERSION=""
COMPOSER_INSTALLED=false
PROJECT_DIR=$(pwd)
ENV_FILE=".env"

# =============================================================================
# Utility Functions
# =============================================================================

print_banner() {
    clear
    echo -e "${PURPLE}╔══════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${PURPLE}║             ${WHITE}CATURNAWA UNAS FEST 2025 SETUP${PURPLE}                ║${NC}"
    echo -e "${PURPLE}║             ${CYAN}Interactive Project Setup Script${PURPLE}             ║${NC}"
    echo -e "${PURPLE}╚══════════════════════════════════════════════════════════════╝${NC}"
    echo ""
}

log_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

log_success() {
    echo -e "${GREEN}✓${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

log_error() {
    echo -e "${RED}✗${NC} $1"
}

log_step() {
    echo -e "${PURPLE}➤${NC} $1"
}

prompt_user() {
    local message="$1"
    local default="$2"
    echo -en "${CYAN}${message}${NC}"
    if [[ -n "$default" ]]; then
        echo -en " ${YELLOW}[${default}]${NC}: "
    else
        echo -en ": "
    fi
    read -r response
    echo "${response:-$default}"
}

confirm_action() {
    local message="$1"
    echo -en "${YELLOW}${message} (y/N)${NC}: "
    read -r response
    [[ "$response" =~ ^[Yy]$ ]]
}

press_enter() {
    echo -en "${CYAN}Press Enter to continue...${NC}"
    read -r
}

# =============================================================================
# Platform Detection
# =============================================================================

detect_platform() {
    log_step "Detecting platform..."
    
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        PLATFORM="linux"
        if [[ -f /etc/os-release ]]; then
            . /etc/os-release
            DISTRO="$ID"
            log_info "Detected: Linux ($NAME)"
        else
            DISTRO="unknown"
            log_info "Detected: Linux (Unknown distribution)"
        fi
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        PLATFORM="macos"
        DISTRO="macos"
        log_info "Detected: macOS"
    elif [[ "$OSTYPE" == "cygwin" ]] || [[ "$OSTYPE" == "msys" ]] || [[ -n "$MSYSTEM" ]]; then
        PLATFORM="windows"
        DISTRO="windows"
        log_info "Detected: Windows (Git Bash/MSYS2)"
    else
        PLATFORM="unknown"
        DISTRO="unknown"
        log_warning "Unknown platform: $OSTYPE"
    fi
}

detect_package_manager() {
    log_step "Detecting package manager..."
    
    case "$PLATFORM" in
        "linux")
            case "$DISTRO" in
                "ubuntu"|"debian")
                    PACKAGE_MANAGER="apt"
                    ;;
                "centos"|"rhel"|"fedora")
                    if command -v dnf &> /dev/null; then
                        PACKAGE_MANAGER="dnf"
                    else
                        PACKAGE_MANAGER="yum"
                    fi
                    ;;
                "arch"|"manjaro")
                    PACKAGE_MANAGER="pacman"
                    ;;
                "alpine")
                    PACKAGE_MANAGER="apk"
                    ;;
                *)
                    PACKAGE_MANAGER="unknown"
                    ;;
            esac
            ;;
        "macos")
            if command -v brew &> /dev/null; then
                PACKAGE_MANAGER="brew"
            else
                PACKAGE_MANAGER="none"
            fi
            ;;
        "windows")
            if command -v choco &> /dev/null; then
                PACKAGE_MANAGER="choco"
            elif command -v scoop &> /dev/null; then
                PACKAGE_MANAGER="scoop"
            else
                PACKAGE_MANAGER="none"
            fi
            ;;
    esac
    
    log_info "Package manager: $PACKAGE_MANAGER"
}

# =============================================================================
# Dependency Detection and Installation
# =============================================================================

check_php() {
    log_step "Checking PHP installation..."
    
    if command -v php &> /dev/null; then
        PHP_VERSION=$(php -r "echo PHP_VERSION;")
        local php_major=$(echo "$PHP_VERSION" | cut -d. -f1)
        local php_minor=$(echo "$PHP_VERSION" | cut -d. -f2)
        
        if [[ $php_major -gt 8 ]] || [[ $php_major -eq 8 && $php_minor -ge 1 ]]; then
            log_success "PHP $PHP_VERSION is installed and meets requirements (≥8.1)"
            return 0
        else
            log_warning "PHP $PHP_VERSION is installed but below required version 8.1"
            return 1
        fi
    else
        log_error "PHP is not installed"
        return 1
    fi
}

install_php() {
    log_step "Installing PHP..."
    
    case "$PACKAGE_MANAGER" in
        "apt")
            sudo apt update
            sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-gd php8.2-json php8.2-mbstring php8.2-mysql php8.2-xml php8.2-zip php8.2-bcmath php8.2-intl php8.2-readline php8.2-soap php8.2-xsl
            ;;
        "dnf"|"yum")
            sudo $PACKAGE_MANAGER install -y php php-cli php-common php-curl php-gd php-json php-mbstring php-mysqlnd php-xml php-zip php-bcmath php-intl php-soap
            ;;
        "pacman")
            sudo pacman -S --noconfirm php php-gd php-intl php-sqlite
            ;;
        "apk")
            sudo apk add php82 php82-cli php82-common php82-curl php82-gd php82-json php82-mbstring php82-mysql php82-xml php82-zip php82-bcmath php82-intl
            ;;
        "brew")
            brew install php@8.2
            ;;
        "choco")
            choco install php -y
            ;;
        "scoop")
            scoop install php
            ;;
        *)
            log_error "Cannot auto-install PHP. Please install PHP 8.1+ manually."
            return 1
            ;;
    esac
}

check_composer() {
    log_step "Checking Composer installation..."
    
    if command -v composer &> /dev/null; then
        local composer_version=$(composer --version 2>/dev/null | head -n1)
        log_success "Composer is installed ($composer_version)"
        COMPOSER_INSTALLED=true
        return 0
    else
        log_error "Composer is not installed"
        return 1
    fi
}

install_composer() {
    log_step "Installing Composer..."
    
    # Download and install composer
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === file_get_contents('https://composer.github.io/installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    
    # Move to global location
    case "$PLATFORM" in
        "linux"|"macos")
            sudo mv composer.phar /usr/local/bin/composer
            ;;
        "windows")
            mkdir -p "$HOME/bin"
            mv composer.phar "$HOME/bin/composer"
            echo 'export PATH="$HOME/bin:$PATH"' >> ~/.bashrc
            ;;
    esac
    
    COMPOSER_INSTALLED=true
}

check_node() {
    log_step "Checking Node.js installation..."
    
    if command -v node &> /dev/null; then
        NODE_VERSION=$(node --version)
        local node_major=$(echo "$NODE_VERSION" | sed 's/v//' | cut -d. -f1)
        
        if [[ $node_major -ge 16 ]]; then
            log_success "Node.js $NODE_VERSION is installed and meets requirements (≥16)"
            return 0
        else
            log_warning "Node.js $NODE_VERSION is installed but below recommended version 16"
            return 1
        fi
    else
        log_error "Node.js is not installed"
        return 1
    fi
}

install_node() {
    log_step "Installing Node.js..."
    
    case "$PACKAGE_MANAGER" in
        "apt")
            curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
            sudo apt-get install -y nodejs
            ;;
        "dnf"|"yum")
            curl -fsSL https://rpm.nodesource.com/setup_lts.x | sudo bash -
            sudo $PACKAGE_MANAGER install -y nodejs npm
            ;;
        "pacman")
            sudo pacman -S --noconfirm nodejs npm
            ;;
        "apk")
            sudo apk add nodejs npm
            ;;
        "brew")
            brew install node
            ;;
        "choco")
            choco install nodejs -y
            ;;
        "scoop")
            scoop install nodejs
            ;;
        *)
            log_error "Cannot auto-install Node.js. Please install Node.js 16+ manually."
            return 1
            ;;
    esac
}

check_database() {
    log_step "Checking database availability..."
    
    if command -v mysql &> /dev/null; then
        log_success "MySQL client is installed"
        return 0
    elif command -v mariadb &> /dev/null; then
        log_success "MariaDB client is installed"
        return 0
    else
        log_warning "No MySQL/MariaDB client found"
        return 1
    fi
}

install_database() {
    log_step "Installing database..."
    
    if confirm_action "Install MySQL server?"; then
        case "$PACKAGE_MANAGER" in
            "apt")
                sudo apt update
                sudo apt install -y mysql-server mysql-client
                ;;
            "dnf"|"yum")
                sudo $PACKAGE_MANAGER install -y mysql-server mysql
                ;;
            "pacman")
                sudo pacman -S --noconfirm mysql
                ;;
            "apk")
                sudo apk add mysql mysql-client
                ;;
            "brew")
                brew install mysql
                ;;
            "choco")
                choco install mysql -y
                ;;
            *)
                log_error "Cannot auto-install MySQL. Please install manually."
                return 1
                ;;
        esac
    fi
}

# =============================================================================
# Environment Configuration
# =============================================================================

setup_environment() {
    log_step "Setting up environment configuration..."
    
    if [[ ! -f "$ENV_FILE" ]]; then
        if [[ -f ".env.example" ]]; then
            cp .env.example "$ENV_FILE"
            log_success "Created .env from .env.example"
        else
            log_error ".env.example not found!"
            return 1
        fi
    else
        log_info ".env already exists"
    fi
    
    # Update Midtrans configuration
    log_step "Configuring Midtrans settings..."
    
    # Update or add Midtrans configuration
    update_env_value "MIDTRANS_SERVER_KEY" "SB-Mid-server-FYFxmeHpL8bGQmSbeGQ7_3EV"
    update_env_value "MIDTRANS_CLIENT_KEY" "SB-Mid-client-zcodFaPnVFzF2xRc"
    update_env_value "MIDTRANS_IS_PRODUCTION" "false"
    update_env_value "MIDTRANS_IS_SANITIZED" "true"
    update_env_value "MIDTRANS_IS_3DS" "true"
    
    log_success "Midtrans configuration updated"
    
    # Configure other settings interactively
    configure_database
    configure_mail
    configure_app
}

update_env_value() {
    local key="$1"
    local value="$2"
    
    if grep -q "^${key}=" "$ENV_FILE"; then
        # Key exists, update it
        if [[ "$PLATFORM" == "macos" ]]; then
            sed -i '' "s/^${key}=.*/${key}=${value}/" "$ENV_FILE"
        else
            sed -i "s/^${key}=.*/${key}=${value}/" "$ENV_FILE"
        fi
    else
        # Key doesn't exist, add it
        echo "${key}=${value}" >> "$ENV_FILE"
    fi
}

configure_database() {
    log_step "Configuring database settings..."
    
    echo ""
    log_info "Current database configuration:"
    grep -E "^DB_" "$ENV_FILE" | while read -r line; do
        echo "  $line"
    done
    echo ""
    
    if confirm_action "Update database configuration?"; then
        local db_host=$(prompt_user "Database Host" "127.0.0.1")
        local db_port=$(prompt_user "Database Port" "3306")
        local db_name=$(prompt_user "Database Name" "unas_fest_2025")
        local db_user=$(prompt_user "Database Username" "root")
        local db_pass=$(prompt_user "Database Password" "")
        
        update_env_value "DB_HOST" "$db_host"
        update_env_value "DB_PORT" "$db_port"
        update_env_value "DB_DATABASE" "$db_name"
        update_env_value "DB_USERNAME" "$db_user"
        update_env_value "DB_PASSWORD" "$db_pass"
        
        log_success "Database configuration updated"
    fi
}

configure_mail() {
    log_step "Configuring mail settings..."
    
    if confirm_action "Configure mail settings?"; then
        local mail_host=$(prompt_user "Mail Host" "mailpit")
        local mail_port=$(prompt_user "Mail Port" "1025")
        local mail_user=$(prompt_user "Mail Username" "")
        local mail_pass=$(prompt_user "Mail Password" "")
        local mail_from=$(prompt_user "Mail From Address" "noreply@unasfest.ac.id")
        
        update_env_value "MAIL_HOST" "$mail_host"
        update_env_value "MAIL_PORT" "$mail_port"
        update_env_value "MAIL_USERNAME" "$mail_user"
        update_env_value "MAIL_PASSWORD" "$mail_pass"
        update_env_value "MAIL_FROM_ADDRESS" "$mail_from"
        
        log_success "Mail configuration updated"
    fi
}

configure_app() {
    log_step "Configuring application settings..."
    
    local app_url=$(prompt_user "Application URL" "http://localhost:8000")
    update_env_value "APP_URL" "$app_url"
    
    log_success "Application configuration updated"
}

# =============================================================================
# Project Setup
# =============================================================================

install_dependencies() {
    log_step "Installing PHP dependencies..."
    
    if [[ "$COMPOSER_INSTALLED" == true ]]; then
        composer install --no-dev --optimize-autoloader
        if [[ $? -eq 0 ]]; then
            log_success "PHP dependencies installed successfully"
        else
            log_error "Failed to install PHP dependencies"
            return 1
        fi
    else
        log_error "Composer not available"
        return 1
    fi
    
    log_step "Installing Node.js dependencies..."
    npm install
    if [[ $? -eq 0 ]]; then
        log_success "Node.js dependencies installed successfully"
    else
        log_error "Failed to install Node.js dependencies"
        return 1
    fi
}

setup_laravel() {
    log_step "Setting up Laravel application..."
    
    # Generate application key if not exists
    if ! grep -q "APP_KEY=base64:" "$ENV_FILE"; then
        php artisan key:generate
        log_success "Application key generated"
    else
        log_info "Application key already exists"
    fi
    
    # Clear caches
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    log_success "Laravel caches cleared"
    
    # Create storage link
    if [[ ! -L "public/storage" ]]; then
        php artisan storage:link
        log_success "Storage link created"
    else
        log_info "Storage link already exists"
    fi
    
    # Set permissions
    case "$PLATFORM" in
        "linux"|"macos")
            chmod -R 775 storage bootstrap/cache
            log_success "Permissions set for storage and cache directories"
            ;;
    esac
}

run_migrations() {
    log_step "Running database migrations..."
    
    if confirm_action "Run database migrations?"; then
        # Test database connection first
        php artisan migrate:status &>/dev/null
        if [[ $? -eq 0 ]]; then
            php artisan migrate --force
            if [[ $? -eq 0 ]]; then
                log_success "Database migrations completed"
                
                if confirm_action "Seed database with sample data?"; then
                    php artisan db:seed
                    log_success "Database seeded"
                fi
            else
                log_error "Database migration failed"
                return 1
            fi
        else
            log_error "Cannot connect to database. Please check your database configuration."
            return 1
        fi
    else
        log_info "Skipping database migrations"
    fi
}

build_assets() {
    log_step "Building frontend assets..."
    
    npm run build
    if [[ $? -eq 0 ]]; then
        log_success "Frontend assets built successfully"
    else
        log_error "Failed to build frontend assets"
        return 1
    fi
}

# =============================================================================
# Testing and Validation
# =============================================================================

test_installation() {
    log_step "Testing installation..."
    
    # Test PHP
    php --version > /dev/null
    if [[ $? -eq 0 ]]; then
        log_success "PHP is working"
    else
        log_error "PHP test failed"
    fi
    
    # Test Composer
    composer --version > /dev/null
    if [[ $? -eq 0 ]]; then
        log_success "Composer is working"
    else
        log_error "Composer test failed"
    fi
    
    # Test Node
    node --version > /dev/null
    if [[ $? -eq 0 ]]; then
        log_success "Node.js is working"
    else
        log_error "Node.js test failed"
    fi
    
    # Test Laravel
    php artisan --version > /dev/null
    if [[ $? -eq 0 ]]; then
        log_success "Laravel is working"
    else
        log_error "Laravel test failed"
    fi
}

# =============================================================================
# Server Management
# =============================================================================

start_servers() {
    log_step "Starting development servers..."
    
    echo ""
    log_info "Available server options:"
    echo "  1. Laravel only (php artisan serve)"
    echo "  2. Laravel + Vite (full development)"
    echo "  3. Production mode"
    echo ""
    
    local choice=$(prompt_user "Choose server mode" "2")
    
    case "$choice" in
        "1")
            log_info "Starting Laravel development server..."
            php artisan serve --host=0.0.0.0 --port=8000
            ;;
        "2")
            log_info "Starting Laravel + Vite development servers..."
            if command -v concurrently &> /dev/null; then
                npm run dev:full
            else
                log_info "Starting Laravel server..."
                php artisan serve --host=0.0.0.0 --port=8000 &
                sleep 2
                log_info "Starting Vite server..."
                npm run hot
            fi
            ;;
        "3")
            log_info "Building for production..."
            npm run build
            log_info "Starting Laravel server in production mode..."
            php artisan serve --env=production --host=0.0.0.0 --port=8000
            ;;
        *)
            log_warning "Invalid choice. Starting default development servers..."
            npm run dev:full
            ;;
    esac
}

# =============================================================================
# Main Setup Process
# =============================================================================

main_setup() {
    print_banner
    
    log_info "Starting interactive setup process..."
    log_info "This script will automatically detect and install required dependencies."
    echo ""
    
    press_enter
    
    # Platform detection
    detect_platform
    detect_package_manager
    echo ""
    
    # Dependency checks and installations
    local deps_needed=false
    
    if ! check_php; then
        deps_needed=true
        if confirm_action "Install PHP?"; then
            install_php || exit 1
        else
            log_error "PHP is required. Exiting..."
            exit 1
        fi
    fi
    
    if ! check_composer; then
        deps_needed=true
        if confirm_action "Install Composer?"; then
            install_composer || exit 1
        else
            log_error "Composer is required. Exiting..."
            exit 1
        fi
    fi
    
    if ! check_node; then
        deps_needed=true
        if confirm_action "Install Node.js?"; then
            install_node || exit 1
        else
            log_error "Node.js is required. Exiting..."
            exit 1
        fi
    fi
    
    if ! check_database; then
        if confirm_action "Install database server?"; then
            install_database
        fi
    fi
    
    if [[ "$deps_needed" == true ]]; then
        log_info "Refreshing shell environment..."
        case "$PLATFORM" in
            "linux"|"macos")
                source ~/.bashrc 2>/dev/null || source ~/.bash_profile 2>/dev/null || true
                ;;
        esac
        echo ""
    fi
    
    # Project setup
    setup_environment || exit 1
    echo ""
    
    install_dependencies || exit 1
    echo ""
    
    setup_laravel || exit 1
    echo ""
    
    run_migrations
    echo ""
    
    build_assets || exit 1
    echo ""
    
    # Final testing
    test_installation
    echo ""
    
    # Success message
    print_banner
    log_success "Setup completed successfully!"
    echo ""
    log_info "Project Details:"
    echo "  • Application: Caturnawa UNAS FEST 2025"
    echo "  • Framework: Laravel $(php artisan --version | cut -d' ' -f3)"
    echo "  • PHP Version: $PHP_VERSION"
    echo "  • Node Version: $NODE_VERSION"
    echo "  • Platform: $PLATFORM ($DISTRO)"
    echo ""
    log_info "Midtrans Configuration:"
    echo "  • Server Key: SB-Mid-server-FYFxmeHpL8bGQmSbeGQ7_3EV"
    echo "  • Client Key: SB-Mid-client-zcodFaPnVFzF2xRc"
    echo "  • Environment: Sandbox (Development)"
    echo ""
    log_info "Next Steps:"
    echo "  1. Review your .env configuration"
    echo "  2. Start the development server"
    echo "  3. Access your application at the configured URL"
    echo ""
    
    if confirm_action "Start development server now?"; then
        start_servers
    else
        log_info "To start servers manually, use:"
        echo "  • Laravel only: php artisan serve"
        echo "  • Full development: npm run dev:full"
        echo "  • Build assets: npm run build"
    fi
}

# =============================================================================
# Error Handling
# =============================================================================

error_handler() {
    log_error "An error occurred during setup!"
    log_info "Please check the error message above and try again."
    log_info "For manual setup, refer to the project README.md"
    exit 1
}

trap error_handler ERR

# =============================================================================
# Script Entry Point
# =============================================================================

# Check if running in supported environment
if [[ -z "$BASH_VERSION" ]]; then
    echo "This script requires Bash. Please run with: bash start.sh"
    exit 1
fi

# Check if in project directory
if [[ ! -f "artisan" ]] || [[ ! -f "composer.json" ]]; then
    echo "This script must be run from the Laravel project root directory."
    echo "Please navigate to your project directory and try again."
    exit 1
fi

# Run main setup
main_setup

# End of script
log_success "Setup script completed!"