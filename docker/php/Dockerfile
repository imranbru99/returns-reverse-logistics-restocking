FROM php:8.3-fpm-bookworm

LABEL maintainer="Imran Ahmed <me@imrandev.bd>"
LABEL org.opencontainers.image.source="https://imrandev.bd"

ARG DEBIAN_FRONTEND=noninteractive
ARG WWWGROUP=1000
ARG WWWUSER=1000

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        gd \
        zip \
        bcmath \
        intl \
        exif \
        pcntl \
        sockets \
        opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd --force -g "${WWWGROUP}" sail \
    && useradd -ms /bin/bash --no-user-group -g "${WWWGROUP}" -u "${WWWUSER}" sail

WORKDIR /var/www/html

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-returns.ini

RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R sail:sail /var/www/html

USER sail

EXPOSE 9000

CMD ["php-fpm"]
