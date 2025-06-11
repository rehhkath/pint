#!/bin/sh

echo "🎬 entrypoint.sh: [$(whoami)] [PHP $(php -r 'echo phpversion();')]"
echo "🎬 start supervisord"

supervisord -c $LARAVEL_PATH/.develop/config/supervisor.conf
