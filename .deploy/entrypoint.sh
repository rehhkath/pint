#!/bin/sh

echo "🎬 entrypoint.sh: [$(whoami)] [PHP $(php -r 'echo phpversion();')]"

composer dump-autoload --no-interaction --no-dev --optimize

# echo "🎬 artisan commands"
php artisan storage:link
php artisan migrate --no-interaction --force

# echo "🎬 build node"
npm run prod

echo "🎬 start supervisord"

supervisord -c $LARAVEL_PATH/.deploy/config/supervisor.conf
