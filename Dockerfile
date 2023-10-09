FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git libzip-dev unzip p7zip-full

# Install system dependencies
RUN apt-get update && apt-get install -y git libpq-dev librabbitmq-dev librabbitmq4

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql

# Install AMQP extension
RUN pecl install amqp && docker-php-ext-enable amqp \
    install xdebug \
    && docker-php-ext-enable xdebug \
        { \
            echo "xdebug.mode=develop,debug"; \
            echo "xdebug.start_with_request=yes"; \
            echo "xdebug.client_host=host.docker.internal"; \
            echo "xdebug.client_port=9003"; \
            echo "xdebug.idekey=PHPSTORM" \
        } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www