# Build Vite assets separately so the runtime image needs only PHP and Apache.
FROM node:22-alpine AS frontend

WORKDIR /var/www/html

COPY package.json package-lock.json* ./
RUN npm install --ignore-scripts

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM composer:2 AS dependencies

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-scripts

FROM php:8.3-apache

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    libfreetype6-dev libicu-dev libjpeg62-turbo-dev libonig-dev libpng-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd intl mbstring pdo_mysql zip \
    && rm -f /etc/apache2/mods-enabled/mpm_* \
    && a2enmod mpm_prefork rewrite \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=dependencies /var/www/html/vendor ./vendor
COPY . .
COPY --from=frontend /var/www/html/public/build ./public/build

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf && ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/ && ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/ && apache2-foreground"]
