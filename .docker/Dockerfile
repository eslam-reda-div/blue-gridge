# ---------- PHP-FPM + Nginx ----------
FROM php:8.3-fpm-alpine AS base

# Install runtime dependencies
RUN apk update && apk upgrade --no-cache && apk add --no-cache \
    bash \
    curl \
    dumb-init \
    nginx \
    icu \
    icu-dev \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev \
    supervisor \
    nodejs \
    npm \
    unzip \
    zip

# Create runtime directories
RUN mkdir -p /var/log/supervisor /run/nginx /var/log/nginx

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pcntl \
    pdo_mysql \
    zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application code
COPY . .

# Use production environment
RUN cp .env.prod .env

# Copy configuration files
COPY .docker/nginx.conf       /etc/nginx/http.d/default.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .docker/php.ini          /usr/local/etc/php/conf.d/local.ini
COPY .docker/opcache.ini      /usr/local/etc/php/conf.d/opcache.ini

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative --prefer-dist --no-interaction

# Install and build front-end assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Laravel optimizations
RUN php artisan storage:link \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan event:cache \
    && php artisan view:cache \
    && php artisan icon:cache

EXPOSE 80

CMD ["/usr/bin/dumb-init", "/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
