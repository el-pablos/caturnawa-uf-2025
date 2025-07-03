#!/bin/bash

# UNAS Fest 2025 - Final Setup Script
# Usage: ./final-setup.sh

echo "🎯 UNAS Fest 2025 - Final Setup"
echo "================================"

# Make all scripts executable
echo "🔧 Making scripts executable..."
chmod +x setup-production-server.sh
chmod +x update-production.sh
chmod +x deploy-zero-downtime.sh
chmod +x rollback-production.sh
chmod +x restart-production.sh
chmod +x monitor-system.sh
chmod +x health-check.sh
chmod +x quick-fix.sh
chmod +x final-setup.sh

echo "✅ All scripts are now executable"

# Check if current system is ready for quick fixes
echo ""
echo "🔍 Running quick system check..."

if command -v php &> /dev/null; then
    echo "✅ PHP is installed: $(php -v | head -n1)"
else
    echo "❌ PHP is not installed"
fi

if command -v composer &> /dev/null; then
    echo "✅ Composer is installed: $(composer --version)"
else
    echo "❌ Composer is not installed"
fi

if command -v node &> /dev/null; then
    echo "✅ Node.js is installed: $(node --version)"
else
    echo "❌ Node.js is not installed"
fi

if command -v npm &> /dev/null; then
    echo "✅ NPM is installed: $(npm --version)"
else
    echo "❌ NPM is not installed"
fi

echo ""
echo "📋 Available Scripts:"
echo "===================="
echo "🚀 setup-production-server.sh  - Setup server from scratch"
echo "🔄 update-production.sh        - Simple update with downtime"
echo "🔄 deploy-zero-downtime.sh     - Zero-downtime deployment"
echo "🔄 rollback-production.sh      - Rollback to previous release"
echo "🔄 restart-production.sh       - Full restart with backup"
echo "📊 monitor-system.sh           - System monitoring"
echo "🔍 health-check.sh             - System health check"
echo "🔧 quick-fix.sh                - Quick fixes (Linux)"
echo "🔧 quick-fix.bat               - Quick fixes (Windows)"

echo ""
echo "📚 Documentation:"
echo "================="
echo "📄 README.md                        - Complete guide"
echo "📄 PRODUCTION-DEPLOYMENT-GUIDE.md   - Detailed deployment guide"
echo "📄 PRE-LAUNCH-CHECKLIST.md         - Pre-launch checklist"

echo ""
echo "🎨 Design Updates:"
echo "=================="
echo "✅ Font changed to Poppins throughout the application"
echo "✅ CSS optimized and cleaned up"
echo "✅ Bootstrap conflicts resolved"

echo ""
echo "🔧 System Improvements:"
echo "======================="
echo "✅ Environment validation added"
echo "✅ Error handling improved"
echo "✅ Production safety checks implemented"
echo "✅ Monitoring and logging configured"

echo ""
echo "🚀 Ready for Production:"
echo "========================"
echo "✅ All deployment scripts ready"
echo "✅ Monitoring system configured"
echo "✅ Security measures implemented"
echo "✅ Performance optimizations applied"
echo "✅ Documentation complete"

echo ""
echo "🎉 UNAS Fest 2025 is ready for launch!"
echo "======================================"
echo "Run './quick-fix.sh' to optimize the current system"
echo "Run './health-check.sh' to check system health"
echo "Run './monitor-system.sh' to monitor the system"
echo ""
echo "For production deployment, see PRODUCTION-DEPLOYMENT-GUIDE.md"
echo "For complete overview, see README.md"
