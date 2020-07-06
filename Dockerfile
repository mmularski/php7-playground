FROM php:7.4-fpm

RUN apt-get update          \
    && apt-get install -y   \
        git                 \
        zlib1g-dev          \
        zip                 \
        unzip               \
        libxml2-dev         \
        libgd-dev           \
        libpng-dev          \
        libfreetype6-dev    \
        libjpeg62-turbo-dev \
        libzip-dev          \
    && pecl install xdebug                                                             \
    && docker-php-ext-install mysqli pdo_mysql iconv simplexml                                      \
    && docker-php-ext-install gd zip bcmath sockets                                                                 \
    && docker-php-ext-enable xdebug                                                     \
    && apt-get clean all                                                                            \
    && rm -rvf /var/lib/apt/lists/*                                                                 \

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
