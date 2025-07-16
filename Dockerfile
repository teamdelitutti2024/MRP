FROM php:7.3-apache

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libjpeg-dev libonig-dev zip unzip git curl libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring xml

# Habilita mod_rewrite
RUN a2enmod rewrite

# Copia archivo custom de configuración de Apache (opcional)
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Establece el directorio de trabajo
WORKDIR /var/www/html
