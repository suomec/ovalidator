FROM php:8.1-fpm

RUN apt-get update && apt-get install -y zip mc cron zlib1g-dev libpng-dev libzip-dev libwebp-dev libjpeg-dev \
    libfreetype6-dev libjpeg62-turbo-dev

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp

RUN docker-php-ext-install gd

WORKDIR /var/www/application

CMD ["php-fpm"]
