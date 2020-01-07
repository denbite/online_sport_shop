FROM composer:latest AS composer
FROM php:7.2-apache-stretch

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Override with custom opcache settings
COPY ./myphp.ini $PHP_INI_DIR/conf.d/

RUN docker-php-ext-install \
    pdo pdo_mysql \
    && a2enmod \
    rewrite

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libvpx-dev  \
    git

RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/lib/x86_64-linux-gnu/ \
    --with-jpeg-dir=/usr/lib/x86_64-linux-gnu/ \
    --with-xpm-dir=/usr/lib/x86_64-linux-gnu/ \
    && docker-php-ext-install gd \
    zip

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    certbot \
    python-certbot-apache

RUN apt-get update \
    && apt-get install -y zlib1g-dev libicu-dev g++ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

EXPOSE 80
EXPOSE 443