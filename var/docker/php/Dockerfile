FROM php:8.1-fpm

RUN apt-get update && apt-get install -y zip mc cron

WORKDIR /var/www/application

CMD ["php-fpm"]
