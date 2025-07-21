# 🔥 Hot Reload Setup for UNAS Fest 2025

This document explains how to use the automatic browser reload functionality for the Laravel web application.

## 🚀 Quick Start

### Option 1: Full Development Environment (Recommended)
```bash
# Start both Laravel and Vite servers simultaneously
npm run dev:full
```

### Option 2: Manual Setup
```bash
# Terminal 1: Start Laravel development server
npm run serve

# Terminal 2: Start Vite hot reload server
npm run hot
```

### Option 3: Using the Custom Development Server
```bash
# Start the enhanced development server
node dev-server.js
```

## 📋 Available Scripts

| Script | Description |
|--------|-------------|
| `npm run dev` | Start Vite development server |
| `npm run dev:host` | Start Vite with host binding |
| `npm run hot` | Start Vite with hot reload on all interfaces |
| `npm run serve` | Start Laravel development server |
| `npm run dev:full` | Start both Laravel and Vite servers |
| `npm run build` | Build assets for production |
| `npm run build:watch` | Build assets and watch for changes |

## 🎯 What Gets Watched

The hot reload system automatically watches for changes in:

### PHP Files
- `app/**/*.php` - All application logic
- `routes/**/*.php` - Route definitions
- `config/**/*.php` - Configuration files

### Blade Templates
- `resources/views/**/*.blade.php` - All Blade templates

### CSS Files
- `resources/css/**/*.css` - Source CSS files
- `public/css/**/*.css` - Public CSS files
- `resources/sass/**/*.scss` - SASS files

### JavaScript Files
- `resources/js/**/*.js` - Source JavaScript files
- `public/js/**/*.js` - Public JavaScript files

### Other Assets
- `public/assets/**/*` - All public assets
- `.env` - Environment configuration
- `composer.json` - PHP dependencies
- `package.json` - Node dependencies

## 🌐 Access URLs

When running the development servers:

- **Laravel Application**: http://localhost:8000
- **Vite HMR Server**: http://localhost:5173

## ✨ Features

### 🔄 Hot Module Replacement (HMR)
- **CSS Changes**: Instantly applied without page refresh
- **JavaScript Changes**: Modules updated while preserving state
- **Asset Changes**: Automatically reloaded

### 🔃 Browser Auto-Reload
- **PHP Changes**: Full page reload when backend code changes
- **Blade Template Changes**: Full page reload for template updates
- **Configuration Changes**: Reload when .env or config files change

### 📱 Cross-Device Testing
- **Network Access**: Servers bind to `0.0.0.0` for network access
- **Mobile Testing**: Access from mobile devices on same network
- **Multiple Browsers**: Test across different browsers simultaneously

## 🛠️ Configuration

### Vite Configuration (`vite.config.js`)
```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                'app/**/*.php',
                'resources/views/**/*.blade.php',
                'resources/css/**/*.css',
                'public/css/**/*.css',
                // ... more file patterns
            ],
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: { host: 'localhost', port: 5173 },
        watch: { usePolling: true, interval: 100 },
    },
});
```

### Laravel Integration
The layouts include Vite directives for hot reload:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

## 🔧 Troubleshooting

### Port Already in Use
```bash
# Kill processes using the ports
sudo lsof -ti:8000 | xargs kill -9
sudo lsof -ti:5173 | xargs kill -9
```

### Hot Reload Not Working
1. **Check Vite Server**: Ensure Vite server is running on port 5173
2. **Check Network**: Verify both servers are accessible
3. **Clear Cache**: Clear browser cache and restart servers
4. **Check Console**: Look for errors in browser developer console

### File Watching Issues
```bash
# Increase file watch limit (Linux/macOS)
echo fs.inotify.max_user_watches=524288 | sudo tee -a /etc/sysctl.conf
sudo sysctl -p
```

### Permission Issues
```bash
# Fix file permissions
sudo chown -R $USER:$USER node_modules
sudo chown -R $USER:$USER public/build
```

## 📝 Development Workflow

1. **Start Development Servers**
   ```bash
   npm run dev:full
   ```

2. **Open Application**
   - Navigate to http://localhost:8000
   - Open browser developer tools to see hot reload logs

3. **Make Changes**
   - Edit PHP files → Full page reload
   - Edit Blade templates → Full page reload
   - Edit CSS files → Instant style updates
   - Edit JS files → Hot module replacement

4. **Test Across Devices**
   - Find your IP address: `ip addr show` (Linux) or `ipconfig` (Windows)
   - Access from mobile: `http://YOUR_IP:8000`

## 🎨 CSS Hot Reload

CSS changes are applied instantly without losing:
- Form data
- Scroll position
- JavaScript state
- Modal states

## 🔄 JavaScript Hot Reload

JavaScript modules are updated while preserving:
- Component state
- Event listeners
- Global variables
- Active timers

## 🚨 Important Notes

- **Production**: Hot reload is only for development
- **Performance**: File watching may impact performance on large projects
- **Network**: Ensure firewall allows access to ports 8000 and 5173
- **HTTPS**: For HTTPS development, additional configuration needed

## 📊 Performance Tips

1. **Exclude Large Directories**: Add to `.gitignore` and Vite ignore patterns
2. **Use Polling**: Enabled by default for better compatibility
3. **Optimize Watching**: Limit watch patterns to necessary files only
4. **Memory Usage**: Monitor memory usage with large file sets

## 🔗 Related Documentation

- [Vite Documentation](https://vitejs.dev/)
- [Laravel Vite Plugin](https://laravel.com/docs/vite)
- [Hot Module Replacement](https://vitejs.dev/guide/features.html#hot-module-replacement)
