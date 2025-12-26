#!/bin/bash
# CSS Minification Script

CSS_DIR="domains/magicsafaris.com/public_html/public/frontend/css"
MAIN_CSS="$CSS_DIR/style.css"

echo "Minifying CSS files..."

# Check if cssnano or other minifier is available, otherwise use simple minification
if command -v npx &> /dev/null; then
    echo "Using npx for CSS minification..."
    # Create minified version
    npx cssnano "$MAIN_CSS" "${MAIN_CSS%.css}.min.css" 2>/dev/null || {
        echo "cssnano not available, using simple minification..."
        # Simple CSS minification (remove comments, whitespace)
        sed 's/\/\*.*\*\///g; s/^[[:space:]]*//g; s/[[:space:]]*$//g; /^$/d' "$MAIN_CSS" | tr -d '\n' > "${MAIN_CSS%.css}.min.css"
    }
else
    echo "Creating minified CSS using sed..."
    # Simple CSS minification
    sed 's/\/\*.*\*\///g; s/^[[:space:]]*//g; s/[[:space:]]*$//g; /^$/d' "$MAIN_CSS" | tr -d '\n' | sed 's/}/}\n/g' > "${MAIN_CSS%.css}.min.css"
fi

echo "CSS minification complete!"

