FROM php:8.5.2-fpm-alpine3.23


ENV TZ=America/Sao_Paulo \
    COMPOSER_ALLOW_SUPERUSER=1



RUN apk add --no-cache \
    bash \
    git \
    unzip \
    icu-dev \
    postgresql-dev \
    oniguruma-dev \
    tzdata

RUN docker-php-ext-install \
    pdo_pgsql \
    intl \
    mbstring

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer


WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --classmap-authoritative \
    --no-interaction \
    --no-progress

COPY . .

EXPOSE 9000


CMD ["php-fpm"]