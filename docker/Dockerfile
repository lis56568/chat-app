FROM composer:lts AS composer

WORKDIR /var/www/html

COPY ../composer.json composer.json
COPY ../composer.lock composer.lock

RUN --mount=type=cache,target=/tmp/cache/composer \
    composer i \
    --ignore-platform-req=ext-pcntl \
    --no-scripts \
    --no-interaction \
    --no-plugins \
    --prefer-dist

FROM node:lts-alpine3.19 AS node

WORKDIR /var/www/html

COPY ../package.json package.json

RUN --mount=type=cache,target=/tmp/cache/npm \
    npm i

COPY ../resources resources
COPY ../vite.config.js vite.config.js
COPY ../postcss.config.js postcss.config.js
COPY ../tailwind.config.js tailwind.config.js
COPY --from=composer /var/www/html/vendor vendor

RUN --mount=type=cache,target=/tmp/cache/ssr \
    npm run build

FROM alpine:latest AS wait-for

WORKDIR /data/wait-for

RUN --mount=type=cache,target=/tmp/cache/wait-for \
    wget -qO- https://raw.githubusercontent.com/eficode/wait-for/v2.2.3/wait-for > wait-for.sh


FROM phpswoole/swoole:php8.3-alpine AS php

WORKDIR /var/www/html

RUN --mount=type=cache,target=/tmp/cache/apk \
    apk update \
    && apk add --no-cache supervisor

RUN --mount=type=cache,target=/tmp/cache/php-ext \
    docker-php-ext-configure pcntl --enable-pcntl && docker-php-ext-install pcntl

COPY .. .
COPY --from=composer /var/www/html/vendor vendor
COPY --from=node /var/www/html/public public
COPY --from=node /var/www/html/bootstrap/ssr bootstrap/ssr
COPY --from=wait-for /data/wait-for/wait-for.sh ./wait-for.sh
COPY ./docker/pre/supervisord.conf /etc/supervisord.conf
COPY ./docker/pre/start.sh ./start.sh

RUN chmod +x start.sh \
    && chmod +x wait-for.sh \
    && chown -R www-data:www-data vendor storage bootstrap

CMD ["/bin/sh","./start.sh"]
