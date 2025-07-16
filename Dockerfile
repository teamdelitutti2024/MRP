FROM php:7.3-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libjpeg-dev libonig-dev zip unzip git curl libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring xml \
    && apt-get clean

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar configuración de Virtual Host personalizada
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html
