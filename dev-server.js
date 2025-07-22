#!/usr/bin/env node

/**
 * Development Server Configuration for UNAS Fest 2025
 * 
 * This script provides enhanced development experience with:
 * - Hot Module Replacement (HMR)
 * - Browser auto-reload for PHP/Blade changes
 * - File watching for all relevant file types
 * - Concurrent Laravel and Vite servers
 */

const { spawn } = require('child_process');
const path = require('path');

// Configuration
const config = {
    laravel: {
        host: '0.0.0.0',
        port: 8000,
        command: 'php',
        args: ['artisan', 'serve', '--host=0.0.0.0', '--port=8000']
    },
    vite: {
        host: '0.0.0.0',
        port: 5173,
        command: 'npm',
        args: ['run', 'dev', '--', '--host', '0.0.0.0', '--port', '5173']
    }
};

// Colors for console output
const colors = {
    reset: '\x1b[0m',
    bright: '\x1b[1m',
    red: '\x1b[31m',
    green: '\x1b[32m',
    yellow: '\x1b[33m',
    blue: '\x1b[34m',
    magenta: '\x1b[35m',
    cyan: '\x1b[36m'
};

function log(message, color = colors.reset) {
    console.log(`${color}${message}${colors.reset}`);
}

function startServer(name, config) {
    log(`🚀 Starting ${name} server...`, colors.cyan);
    
    const server = spawn(config.command, config.args, {
        stdio: 'pipe',
        shell: true,
        cwd: process.cwd()
    });

    server.stdout.on('data', (data) => {
        const output = data.toString().trim();
        if (output) {
            log(`[${name}] ${output}`, colors.green);
        }
    });

    server.stderr.on('data', (data) => {
        const output = data.toString().trim();
        if (output && !output.includes('WARN')) {
            log(`[${name}] ${output}`, colors.yellow);
        }
    });

    server.on('close', (code) => {
        if (code !== 0) {
            log(`❌ ${name} server exited with code ${code}`, colors.red);
        } else {
            log(`✅ ${name} server stopped gracefully`, colors.green);
        }
    });

    return server;
}

function main() {
    log('🎯 UNAS Fest 2025 - Development Server', colors.bright);
    log('=====================================', colors.bright);
    log('');
    
    // Start Laravel development server
    const laravelServer = startServer('Laravel', config.laravel);
    
    // Wait a bit before starting Vite
    setTimeout(() => {
        // Start Vite development server
        const viteServer = startServer('Vite', config.vite);
        
        // Display access URLs
        setTimeout(() => {
            log('');
            log('🌐 Development servers are running:', colors.bright);
            log(`   Laravel: http://localhost:${config.laravel.port}`, colors.green);
            log(`   Vite HMR: http://localhost:${config.vite.port}`, colors.green);
            log('');
            log('📝 Features enabled:', colors.bright);
            log('   ✅ Hot Module Replacement (HMR)', colors.green);
            log('   ✅ Auto-reload for PHP/Blade changes', colors.green);
            log('   ✅ Live CSS/JS updates', colors.green);
            log('   ✅ File watching for all assets', colors.green);
            log('');
            log('🔄 Watching for changes in:', colors.bright);
            log('   • app/**/*.php', colors.cyan);
            log('   • resources/views/**/*.blade.php', colors.cyan);
            log('   • resources/css/**/*.css', colors.cyan);
            log('   • resources/js/**/*.js', colors.cyan);
            log('   • public/css/**/*.css', colors.cyan);
            log('   • public/js/**/*.js', colors.cyan);
            log('');
            log('Press Ctrl+C to stop both servers', colors.yellow);
        }, 2000);
        
        // Handle graceful shutdown
        process.on('SIGINT', () => {
            log('');
            log('🛑 Shutting down development servers...', colors.yellow);
            laravelServer.kill('SIGTERM');
            viteServer.kill('SIGTERM');
            
            setTimeout(() => {
                process.exit(0);
            }, 1000);
        });
        
    }, 1000);
}

// Run the development server
if (require.main === module) {
    main();
}

module.exports = { startServer, config };
