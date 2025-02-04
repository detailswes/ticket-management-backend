# Use official PHP image with PHP-FPM
FROM php:8.2-fpm
# Install required system packages and Composer
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libssl-dev zip git unzip nginx supervisor libicu-dev curl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql bcmath intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Verify Composer installation
RUN composer --version
# Set the working directory
WORKDIR /var/www/html
# Copy Composer files first for better caching
COPY composer.json composer.lock ./
# Install Composer dependencies before copying the full app
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --no-interaction
# Copy application files
COPY . .
# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
# Expose necessary ports
EXPOSE 80
# Configure Nginx
RUN rm -f /etc/nginx/sites-enabled/default
COPY nginx/mini-ticket-project.conf /etc/nginx/sites-available/mini-ticket-project.conf
RUN ln -s /etc/nginx/sites-available/mini-ticket-project.conf /etc/nginx/sites-enabled/
CMD service nginx start && php-fpm