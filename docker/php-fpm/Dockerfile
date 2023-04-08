FROM php:8.2-fpm-alpine3.17 as base

ARG UID=1000
ARG GID=1000

ENV DEVELOPER_DEPS="bash vim" \
    ZIP_DEPS="zip" \
    REDIS_DEPS="libzip-dev"

RUN apk add --no-cache shadow $PHPIZE_DEPS $DEVELOPER_DEPS $ZIP_DEPS $REDIS_DEPS && \
    pecl channel-update pecl.php.net && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    docker-php-ext-install zip bcmath && \
    docker-php-ext-enable opcache && \
    docker-php-source delete && \
    usermod -u $UID www-data  && \
    groupmod -g $GID www-data && \
    mkdir -p /app /app/vendor /app/var && \
    chmod -R 0775 /app && \
    chown www-data:www-data /app /var/www

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /app

FROM base as prod

COPY docker/php-fpm/config/prod/php.ini $PHP_INI_DIR/conf.d/php.ini

COPY bin/ /app/bin/
COPY config/ /app/config/
COPY public/ /app/public/
COPY src/ /app/src/
COPY templates/ /app/templates
COPY .env /app/.env
COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock

RUN export COMPOSER_ALLOW_SUPERUSER=1 && \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    composer install --no-interaction --no-cache --prefer-dist --no-autoloader --no-scripts --no-dev && \
    composer dump-autoload --no-interaction --no-cache --optimize --classmap-authoritative --no-scripts && \
    composer dump-env prod && \
    chmod +x bin/console && \
    bin/console cache:clear -e prod && \
    chown -R www-data:www-data /app/public /app/var

FROM base as dev

COPY --chown=www-data:www-data . /app

RUN apk add vim && \
    apk add --update linux-headers && \
    pecl install --alldeps xdebug-3.2.1 && \
    docker-php-ext-enable xdebug

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
