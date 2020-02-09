FROM php:7.2.27-fpm-buster
WORKDIR /app

RUN apt update \
    && apt -y install composer