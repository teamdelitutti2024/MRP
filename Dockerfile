FROM php:7.3-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    curl \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install pdo pdo_mysql zip mbstring xml gd \
    && apt-get clean

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar configuración de Apache personalizada
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html
