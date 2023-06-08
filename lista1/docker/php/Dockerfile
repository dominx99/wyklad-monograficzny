FROM php:8.2-fpm as php

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    libonig-dev \
    wget \
    git \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    python3 \
    pip

RUN python3 -m pip install --upgrade pip
RUN pip install scipy pandas

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath

COPY --from=composer:2.3.7 /usr/bin/composer /usr/bin/composer

WORKDIR /application

FROM nginx:1.23.2-alpine AS nginx
