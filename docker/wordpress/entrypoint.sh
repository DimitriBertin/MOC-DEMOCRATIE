#!/bin/bash
set -e

THEME_DIR="/var/www/html/wp-content/themes/atelierdesign"

# Lance composer install si vendor/ est absent
if [ -f "$THEME_DIR/composer.json" ] && [ ! -d "$THEME_DIR/vendor" ]; then
    echo "[entrypoint] vendor/ absent — lancement de composer install..."
    cd "$THEME_DIR"
    composer install --no-interaction --prefer-dist --optimize-autoloader
    echo "[entrypoint] composer install terminé."
fi

# Passe la main à l'entrypoint WordPress officiel
exec docker-entrypoint.sh "$@"
