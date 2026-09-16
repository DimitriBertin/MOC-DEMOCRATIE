module.exports = {
  // Proxy your local WordPress development server
  proxy: "moc.local", // Change this to match your local WordPress URL
  
  // Files to watch for changes
  files: [
    // PHP files
    "**/*.php",
    
    // Template files
    "templates/**/*.php",
    "src/components/**/*.php",
    "ad-ui/acf/components/**/*.php",
    
    // Compiled assets (webpack output)
    "dist/**/*.css",
    "dist/**/*.js"
  ],
  
  // Browser sync options
  open: true,
  notify: true,
  
  // Port configuration
  port: 3000,
  ui: {
    port: 3001
  },
  
  // Watch options
  watchOptions: {
    ignoreInitial: true,
    ignored: [
      "node_modules",
      "ad-ui/core/node_modules",
      "**/.git/**",
      "**/vendor/**",
      "**/.DS_Store",
      "src/**/*.js", // Ignore source JS files (webpack handles these)
      "src/**/*.scss" // Ignore source SCSS files (webpack handles these)
    ]
  },
  
  // Browser configuration
  browser: "default",
  
  // Reload delay (milliseconds) - increased for webpack compile time
  reloadDelay: 500,
  
  // Additional options
  ghostMode: {
    clicks: true,
    forms: true,
    scroll: true
  },
  
  // Snippet options
  snippetOptions: {
    rule: {
      match: /<\/body>/i,
      fn: function (snippet, match) {
        return snippet + match;
      }
    }
  },
  
  // Ignore certain file types to prevent unnecessary reloads
  ignore: [
    "**/*.map"
  ]
};