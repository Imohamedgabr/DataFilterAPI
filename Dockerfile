FROM php:8.1-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Run composer install
RUN composer install --no-scripts --no-autoloader

# Copy entire application into Docker image
COPY . .

# Change the owner and group of the storage and bootstrap/cache directories
RUN chown -R $user:www-data storage bootstrap/cache

# Change the permissions of the storage and bootstrap/cache directories
RUN chmod -R 775 storage bootstrap/cache
RUN chmod -R 775 storage/logs
RUN chown -R $user:www-data .env
RUN chown -R $user:www-data storage/logs

USER $user

# Generate the autoloader file
RUN composer dump-autoload --optimize