#!/bin/bash

# UNAS Fest 2025 - System Monitoring Script
# Usage: ./monitor-system.sh [--detailed]

set -e

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
PROJECT_DIR="/var/www/unas-fest-2025"
DETAILED_MODE=false

# Check for detailed mode
if [[ "$1" == "--detailed" ]]; then
    DETAILED_MODE=true
fi

# Function to check service status
check_service() {
    local service=$1
    if systemctl is-active --quiet "$service"; then
        echo -e "${GREEN}✅ $service is running${NC}"
        return 0
    else
        echo -e "${RED}❌ $service is not running${NC}"
        return 1
    fi
}

# Function to get service status with details
get_service_details() {
    local service=$1
    local status=$(systemctl is-active "$service" 2>/dev/null || echo "inactive")
    local uptime=$(systemctl show --property=ActiveEnterTimestamp "$service" 2>/dev/null | cut -d= -f2)
    
    case $status in
        "active")
            echo -e "${GREEN}✅ $service${NC} - Running since $uptime"
            ;;
        "inactive")
            echo -e "${RED}❌ $service${NC} - Not running"
            ;;
        *)
            echo -e "${YELLOW}⚠️ $service${NC} - Status: $status"
            ;;
    esac
}

# Function to check disk space
check_disk_space() {
    local path=$1
    local threshold=${2:-80}
    local usage=$(df "$path" | awk 'NR==2 {print $5}' | sed 's/%//')
    
    if [ "$usage" -gt "$threshold" ]; then
        echo -e "${RED}❌ Disk space critical: $usage% used on $path${NC}"
        return 1
    elif [ "$usage" -gt 70 ]; then
        echo -e "${YELLOW}⚠️ Disk space warning: $usage% used on $path${NC}"
        return 0
    else
        echo -e "${GREEN}✅ Disk space OK: $usage% used on $path${NC}"
        return 0
    fi
}

# Function to check memory usage
check_memory() {
    local mem_info=$(free | grep Mem)
    local total=$(echo $mem_info | awk '{print $2}')
    local used=$(echo $mem_info | awk '{print $3}')
    local usage=$(echo "$used $total" | awk '{printf "%.0f", $1/$2 * 100}')
    
    if [ "$usage" -gt 80 ]; then
        echo -e "${RED}❌ Memory usage critical: $usage%${NC}"
        return 1
    elif [ "$usage" -gt 70 ]; then
        echo -e "${YELLOW}⚠️ Memory usage warning: $usage%${NC}"
        return 0
    else
        echo -e "${GREEN}✅ Memory usage OK: $usage%${NC}"
        return 0
    fi
}

# Function to check CPU load
check_cpu_load() {
    local load=$(uptime | awk -F'load average:' '{print $2}' | awk '{print $1}' | sed 's/,//')
    local cpu_count=$(nproc)
    local load_percent=$(echo "$load $cpu_count" | awk '{printf "%.0f", $1/$2 * 100}')
    
    if [ "$load_percent" -gt 80 ]; then
        echo -e "${RED}❌ CPU load critical: $load (${load_percent}% of capacity)${NC}"
        return 1
    elif [ "$load_percent" -gt 70 ]; then
        echo -e "${YELLOW}⚠️ CPU load warning: $load (${load_percent}% of capacity)${NC}"
        return 0
    else
        echo -e "${GREEN}✅ CPU load OK: $load (${load_percent}% of capacity)${NC}"
        return 0
    fi
}

# Function to check database connectivity
check_database() {
    if [ -f "$PROJECT_DIR/current/.env" ]; then
        cd "$PROJECT_DIR/current"
        if sudo -u www-data php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB_CHECK_OK';" 2>/dev/null | grep -q "DB_CHECK_OK"; then
            echo -e "${GREEN}✅ Database connection OK${NC}"
            return 0
        else
            echo -e "${RED}❌ Database connection failed${NC}"
            return 1
        fi
    else
        echo -e "${YELLOW}⚠️ Application not deployed yet${NC}"
        return 0
    fi
}

# Function to check application response
check_application() {
    local response=$(curl -s -o /dev/null -w "%{http_code}" "http://localhost" 2>/dev/null || echo "000")
    
    if [ "$response" = "200" ]; then
        echo -e "${GREEN}✅ Application responding (HTTP 200)${NC}"
        return 0
    elif [ "$response" = "503" ]; then
        echo -e "${YELLOW}⚠️ Application in maintenance mode (HTTP 503)${NC}"
        return 0
    else
        echo -e "${RED}❌ Application not responding (HTTP $response)${NC}"
        return 1
    fi
}

# Function to check SSL certificate
check_ssl() {
    local domain=$1
    if [ -z "$domain" ]; then
        echo -e "${YELLOW}⚠️ No domain specified for SSL check${NC}"
        return 0
    fi
    
    local expiry=$(echo | openssl s_client -servername "$domain" -connect "$domain:443" 2>/dev/null | openssl x509 -noout -enddate 2>/dev/null | cut -d= -f2)
    
    if [ -n "$expiry" ]; then
        local expiry_timestamp=$(date -d "$expiry" +%s)
        local current_timestamp=$(date +%s)
        local days_until_expiry=$(( (expiry_timestamp - current_timestamp) / 86400 ))
        
        if [ "$days_until_expiry" -lt 7 ]; then
            echo -e "${RED}❌ SSL certificate expires in $days_until_expiry days${NC}"
            return 1
        elif [ "$days_until_expiry" -lt 30 ]; then
            echo -e "${YELLOW}⚠️ SSL certificate expires in $days_until_expiry days${NC}"
            return 0
        else
            echo -e "${GREEN}✅ SSL certificate valid for $days_until_expiry days${NC}"
            return 0
        fi
    else
        echo -e "${YELLOW}⚠️ Could not check SSL certificate${NC}"
        return 0
    fi
}

# Function to check log file sizes
check_log_sizes() {
    local log_dir="$PROJECT_DIR/shared/storage/logs"
    local nginx_log="/var/log/nginx/access.log"
    local php_log="/var/log/php8.1-fpm.log"
    
    if [ -d "$log_dir" ]; then
        local large_logs=$(find "$log_dir" -name "*.log" -size +100M 2>/dev/null)
        if [ -n "$large_logs" ]; then
            echo -e "${YELLOW}⚠️ Large log files found (>100MB):${NC}"
            echo "$large_logs"
        else
            echo -e "${GREEN}✅ Application log sizes OK${NC}"
        fi
    fi
    
    # Check system logs
    if [ -f "$nginx_log" ] && [ $(stat -c%s "$nginx_log") -gt 104857600 ]; then
        echo -e "${YELLOW}⚠️ Nginx access log is large (>100MB)${NC}"
    fi
    
    if [ -f "$php_log" ] && [ $(stat -c%s "$php_log") -gt 104857600 ]; then
        echo -e "${YELLOW}⚠️ PHP-FPM log is large (>100MB)${NC}"
    fi
}

# Function to get system information
get_system_info() {
    echo -e "${BLUE}📊 System Information${NC}"
    echo "==================="
    echo "OS: $(lsb_release -d 2>/dev/null | cut -f2 || echo "Unknown")"
    echo "Kernel: $(uname -r)"
    echo "Uptime: $(uptime | awk -F'up ' '{print $2}' | awk -F',' '{print $1}')"
    echo "Load Average: $(uptime | awk -F'load average:' '{print $2}')"
    echo "Memory: $(free -h | grep Mem | awk '{print $3 "/" $2}')"
    echo "Disk Usage: $(df -h / | awk 'NR==2 {print $3 "/" $2 " (" $5 ")"}')"
    echo ""
}

# Function to get application information
get_application_info() {
    if [ -f "$PROJECT_DIR/current/.env" ]; then
        echo -e "${BLUE}🚀 Application Information${NC}"
        echo "========================="
        echo "Project: UNAS Fest 2025"
        echo "Environment: $(grep APP_ENV "$PROJECT_DIR/current/.env" | cut -d= -f2)"
        echo "Debug Mode: $(grep APP_DEBUG "$PROJECT_DIR/current/.env" | cut -d= -f2)"
        echo "Current Release: $(readlink "$PROJECT_DIR/current" | xargs basename 2>/dev/null || echo "Not deployed")"
        echo "Available Releases: $(ls -1 "$PROJECT_DIR/releases" 2>/dev/null | wc -l || echo "0")"
        
        if [ -f "$PROJECT_DIR/shared/deployment.log" ]; then
            echo "Last Deployment: $(tail -n 1 "$PROJECT_DIR/shared/deployment.log" 2>/dev/null || echo "No deployment history")"
        fi
        echo ""
    fi
}

# Main monitoring function
main() {
    echo -e "${GREEN}🔍 UNAS Fest 2025 - System Monitor${NC}"
    echo "===================================="
    echo "Time: $(date)"
    echo "===================================="
    echo ""
    
    # System information (detailed mode only)
    if [ "$DETAILED_MODE" = true ]; then
        get_system_info
        get_application_info
    fi
    
    # Check services
    echo -e "${BLUE}🔧 Service Status${NC}"
    echo "================"
    
    if [ "$DETAILED_MODE" = true ]; then
        get_service_details "nginx"
        get_service_details "php8.1-fpm"
        get_service_details "mysql"
        get_service_details "redis-server"
        get_service_details "laravel-worker"
    else
        check_service "nginx"
        check_service "php8.1-fpm"
        check_service "mysql"
        check_service "redis-server"
        check_service "laravel-worker"
    fi
    echo ""
    
    # Check system resources
    echo -e "${BLUE}📊 System Resources${NC}"
    echo "==================="
    check_disk_space "/"
    check_disk_space "/var" 90
    check_memory
    check_cpu_load
    echo ""
    
    # Check application
    echo -e "${BLUE}🚀 Application Status${NC}"
    echo "===================="
    check_application
    check_database
    echo ""
    
    # Check SSL (if domain is configured)
    if [ -f "$PROJECT_DIR/shared/.env" ]; then
        domain=$(grep APP_URL "$PROJECT_DIR/shared/.env" | cut -d= -f2 | sed 's/https\?:\/\///')
        if [ -n "$domain" ] && [ "$domain" != "localhost" ]; then
            echo -e "${BLUE}🔒 SSL Status${NC}"
            echo "============="
            check_ssl "$domain"
            echo ""
        fi
    fi
    
    # Check log sizes
    if [ "$DETAILED_MODE" = true ]; then
        echo -e "${BLUE}📝 Log Status${NC}"
        echo "============="
        check_log_sizes
        echo ""
    fi
    
    # Summary
    echo -e "${GREEN}✅ Monitoring check completed${NC}"
    echo "===================================="
    
    if [ "$DETAILED_MODE" = true ]; then
        echo "For continuous monitoring, run: watch -n 30 ./monitor-system.sh"
        echo "For quick check, run: ./monitor-system.sh"
    fi
}

# Run main function
main "$@"
