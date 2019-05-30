FROM php:7.1.3-fpm

#System update
RUN apt update

# Because some basic tools come in handy...
RUN apt-get install -q -y \
    zlib1g-dev \
    libxml2-dev \
    curl \
    unzip

# Install PHP modules
RUN docker-php-ext-install zip soap

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer

# Add the application
WORKDIR /canvas
