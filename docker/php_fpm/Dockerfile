FROM php:8-fpm-alpine

RUN set -eux; \
    apk add --update --no-cache \
                git \
                tzdata \
    		    npm \
    		    graphviz \
                ssmtp \
    		    libzip

RUN apk add --no-cache --virtual .build-deps \
                autoconf \
                g++ \
                make \
                openssl-dev \
                zlib-dev \
                libzip-dev  \
                # для xdebug
                linux-headers

RUN docker-php-ext-install -j "$(nproc)" \
                exif \
                mysqli \
                pdo_mysql \
                opcache \
                zip

RUN docker-php-source extract \
    && pecl channel-update pecl.php.net \
    && pecl install -f \
                apcu \
                xdebug \
    && docker-php-ext-enable \
                apcu \
                opcache \
                xdebug \
    && docker-php-source delete

    # Удалеяем
RUN apk del --no-network .build-deps \
	# Delete APK cache.
    && rm -rf /var/cache/apk/* \
    && rm -rf /etc/apk/cache \
    && rm -rf /tmp/*

    # Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD ./php.ini /usr/local/etc/php/conf.d/40-custom.ini

# Локальный часовой пояс
ENV TZ Europe/Moscow

RUN cp /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

WORKDIR /var/www/site

CMD ["php-fpm"]