FROM php:8.2-fpm-alpine

ENV PHP_XDEBUG_ENABLED=1

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions sockets

RUN set -ex \
    	&& apk --no-cache add $PHPIZE_DEPS postgresql-dev\
    	&& docker-php-ext-install pdo pdo_pgsql

#RUN pecl install redis && docker-php-ext-enable redis

#AMQP

RUN apk add --no-cache --update rabbitmq-c-dev  \
    && pecl install amqp \
    && docker-php-ext-enable amqp


# Add xdebug
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add --update linux-headers \
    && pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.log=/var/www/html/xdebug/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini



#RUN pecl install redis \
#      && pecl install xdebug \
#      && docker-php-ext-enable redis xdebug

ADD ./conf/php.ini /usr/local/etc/php/conf.d/php.ini

