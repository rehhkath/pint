ARG PHP_VERSION=${PHP_VERSION:-8.2}
FROM php:${PHP_VERSION}-fpm-alpine AS php-system-setup

# Install system dependencies
RUN apk add --no-cache dcron busybox-suid libcap curl zip unzip git

# Install PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions intl bcmath mbstring gd pdo_mysql pdo_pgsql opcache redis uuid exif pcntl zip sockets

# Install supervisord implemenztation
COPY --from=ochinchina/supervisord:latest /usr/local/bin/supervisord /usr/local/bin/supervisord

# Install caddy
COPY --from=caddy:2.2.1 /usr/bin/caddy /usr/local/bin/caddy
RUN setcap 'cap_net_bind_service=+ep' /usr/local/bin/caddy

# Install composer
COPY --from=composer/composer:2 /usr/bin/composer /usr/local/bin/composer

FROM php-system-setup AS app-setup

# Set working directory
ENV LARAVEL_PATH=/srv/app
WORKDIR $LARAVEL_PATH

# Copy
COPY config/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Adjust permissions
RUN chown -R www-data:www-data $LARAVEL_PATH
RUN chmod -R 775 $LARAVEL_PATH

# Start app
EXPOSE 80
COPY entrypoint.sh /

ENTRYPOINT ["sh", "/entrypoint.sh"]
