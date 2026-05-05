FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json ./
# Avoid running composer scripts before the application files (artisan) are copied
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs --no-scripts

COPY . ./
# Now that the application files (including artisan) are present, run dump-autoload and package discovery
RUN composer dump-autoload --optimize --no-interaction \
    && php artisan package:discover --ansi

FROM node:20-bookworm-slim AS frontend

WORKDIR /app

COPY package.json ./
RUN npm install

COPY resources ./resources
COPY public ./public
COPY vite.config.js tailwind.config.js postcss.config.js postcss.config.cjs ./
RUN npm run build

FROM php:8.2-cli-bookworm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev libpq-dev libzip-dev libxml2-dev libonig-dev unzip git \
    && docker-php-ext-install bcmath intl mbstring pdo_pgsql xml zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000

CMD sh -c 'php artisan serve --host 0.0.0.0 --port ${PORT:-10000}'