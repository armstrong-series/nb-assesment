FROM php:8.2-apache
WORKDIR /var/www/NB

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    && apt-get clean

RUN docker-php-ext-install pdo pdo_mysql gd

RUN apt-get update && apt-get install -y \
    librabbitmq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install amqp \
    && docker-php-ext-enable amqp

RUN a2enmod rewrite


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY . .


RUN composer install 

# Adjust file permissions
RUN chown -R www-data:www-data /var/www/NB \
    && chmod -R 755 /var/www/NB

EXPOSE 80


CMD ["apache2-foreground"]
