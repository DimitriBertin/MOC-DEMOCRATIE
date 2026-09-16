# BrowserSync + Webpack Development Setup

## Configuration Complete ✅

I've integrated BrowserSync with your webpack development workflow:

### Files Added/Modified:

1. **bs-config.js** - BrowserSync configuration optimized for webpack
2. **package.json** - Added browser-sync, concurrently dependencies and updated scripts

### Setup Instructions:

1. **Install Dependencies** (when you have pnpm/npm available):
   ```bash
   pnpm install browser-sync concurrently --save-dev
   # or
   npm install browser-sync concurrently --save-dev
   ```

2. **Update Proxy URL** in `bs-config.js`:
   - Change `proxy: "moc.local"` to match your local WordPress URL
   - Common examples: `"localhost/moc"`, `"moc.test"`, `"moc.local"`

3. **Run Development Mode** (runs both webpack watch + BrowserSync):
   ```bash
   pnpm run dev
   # or
   npm run dev
   ```

### Available Scripts:

- `pnpm run dev` - **Main development command** (runs webpack + BrowserSync)
- `pnpm run dev:webpack` - Run only webpack in watch mode
- `pnpm run dev:browsersync` - Run only BrowserSync
- `pnpm run serve` - Start BrowserSync standalone
- `pnpm run build` - Production build

### How It Works:

1. **Webpack Watch Mode**: Compiles your SCSS/JS files and watches for changes
2. **BrowserSync**: Proxies your WordPress site and watches for:
   - PHP file changes (full reload)
   - Compiled CSS/JS changes (inject/reload)
   - Template changes (full reload)

### Optimized Configuration:

- **Smart Watching**: Ignores source files (webpack handles them), watches compiled output
- **Delayed Reloads**: 500ms delay to allow webpack compilation to complete
- **Notifications**: Enabled to show when files change
- **Source Maps**: Ignored to prevent unnecessary reloads

### Development Workflow:

1. Start your local WordPress server (XAMPP, Local, etc.)
2. Run `pnpm run dev` 
3. Both webpack and BrowserSync start automatically
4. Browser opens to `http://localhost:3000`
5. Make changes:
   - **SCSS/JS changes**: Webpack compiles → BrowserSync injects
   - **PHP changes**: BrowserSync reloads page immediately

### Port Configuration:

- **3000**: Main browsing port
- **3001**: BrowserSync UI/admin panel
- **Your WordPress**: Original port (proxied through 3000)

### Notes:

- The `concurrently` package runs webpack and BrowserSync simultaneously
- BrowserSync watches the `dist/` folder for webpack output changes
- Source files are ignored by BrowserSync (webpack handles the watching)
- Notifications show when BrowserSync detects changes