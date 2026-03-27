# Build Setup

## JavaScript Build System

The plugin uses Laravel Mix (webpack) to bundle and minify JavaScript files.

### Source Files

- `assets/src/admin.js` - Main entry point
- `assets/src/admin-update.js` - Update products functionality
- `assets/src/admin-import.js` - Import new products functionality

### Output

- `assets/admin.min.js` - Minified and bundled output

### Available Commands

From the plugin root directory:

```bash
# Development build (unminified with source maps)
npm run dev

# Watch for changes and rebuild automatically
npm run watch

# Production build (minified)
npm run production
```

### How It Works

1. All source files in `assets/src/` are bundled together
2. ES6 module imports are resolved
3. The code is minified using webpack's built-in minifier
4. Source maps are generated (in dev mode)
5. Output is written to `assets/admin.min.js`

### Installation

Install dependencies once:

```bash
npm install
```

Then run one of the commands above to build the assets.

## Adding New Features

To add new functionality:

1. Create a new file in `assets/src/` (e.g., `admin-new-feature.js`)
2. Export a function:
   ```javascript
   export function initNewFeature() {
       // Your code here
   }
   ```
3. Import and initialize it in `assets/src/admin.js`:
   ```javascript
   import { initNewFeature } from './admin-new-feature.js';
   // ...
   jQuery(document).ready(function ($) {
       initUpdateProducts();
       initImportProducts();
       initNewFeature();
   });
   ```
4. Run `npm run dev` to rebuild

The minified output will include all your new code.

