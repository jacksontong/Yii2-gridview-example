FROM php:7.1.3-fpm

MAINTAINER Jackson Tong <tongtoan2704@gmail.com>
WORKDIR /var/www/

COPY . /var/www

RUN apt-get update \
  && apt-get install -y --no-install-recommends zlib1g-dev libicu-dev g++ \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libmcrypt-dev \
      libpng12-dev \
      libmagickwand-dev \
  && docker-php-ext-install -j$(nproc) iconv mcrypt \
  && docker-php-ext-configure gd --enable-gd-native-ttf --with-jpeg-dir=/usr/lib/x86_64-linux-gnu --with-png-dir=/usr/lib/x86_64-linux-gnu --with-freetype-dir=/usr/lib/x86_64-linux-gnu \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install -j$(nproc) gd \
  && pecl install imagick \
  && docker-php-ext-enable imagick \
  && rm -rf /var/lib/apt/lists/*