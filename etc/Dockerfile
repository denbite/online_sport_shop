FROM php:7.1-apache-stretch

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

RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/lib/x86_64-linux-gnu/ \
    --with-jpeg-dir=/usr/lib/x86_64-linux-gnu/ \
    --with-xpm-dir=/usr/lib/x86_64-linux-gnu/ \
    --with-vpx-dir=/usr/lib/x86_64-linux-gnu/ \
    && docker-php-ext-install gd

RUN apt-get update \
    && apt-get install cerbot \
    python-certbot-apache