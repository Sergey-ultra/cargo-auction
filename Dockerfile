FROM php:8.2-fpm-alpine

ENV PHP_XDEBUG_ENABLED=1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN set -ex \
    	&& apk --no-cache add $PHPIZE_DEPS postgresql-dev nodejs yarn npm\
    	&& docker-php-ext-install pdo pdo_pgsql

#RUN pecl install redis && docker-php-ext-enable redis


# Add xdebug
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS
RUN apk add --update linux-headers
RUN pecl install xdebug-3.2.0
RUN docker-php-ext-enable xdebug
RUN apk del -f .build-deps

#RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.log=/var/www/html/xdebug/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini



#RUN pecl install redis \
#      && pecl install xdebug \
#      && docker-php-ext-enable redis xdebug


WORKDIR /var/www
