FROM php:8.2-apache

# PHP 設定ファイル
# COPY php.ini /etc/php.ini
COPY php.ini /usr/local/etc/php/php.ini
# 644 所有者の権限：読み込み・書き込みどちらも可　グループの権限：読み込みのみ可　その他のユーザー：読込のみ可
# RUN chmod 644 /usr/local/etc/php

# Composerのインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# ミドルウェアインストール
# https://hub.docker.com/_/php/
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        git \
        zip \
        unzip \
        vim \
        libpng-dev \
        libpq-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        wget \
        msmtp \
        msmtp-mta \
        autoconf \
        build-essential \
        libtool \
        automake \
        pkg-config \
        re2c \
        autoconf \
        gcc \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    # && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    # && docker-php-ext-install imap \
    && docker-php-ext-install zip

# Install and enable imagick PECL extensions
# RUN pecl install imagick \
#   && docker-php-ext-enable imagick

COPY xdebug-3.4.0.tar /tmp/

RUN apt-get update \
    && cd /tmp \
    && tar xf xdebug-3.4.0.tar \
    && cd xdebug-3.4.0 \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable xdebug

# modRewriteを有効化する
RUN mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN /bin/sh -c a2enmod rewrite

# タイムゾーン設定
RUN ln -sf  /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

CMD ["apache2-foreground"]