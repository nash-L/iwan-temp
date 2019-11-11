FROM php:7.3.4-fpm-alpine

RUN apk --no-cache add tzdata  && \
    ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime && \
    echo "Asia/Shanghai" > /etc/timezone

RUN apk update && apk add \
        libpng-dev freetype-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include --with-png-dir=/usr/include --with-freetype-dir=/usr/include \
    && docker-php-ext-install gd pdo pdo_mysql mysqli