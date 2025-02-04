# Use official PHP image with PHP-FPM
FROM php:8.2-fpm

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libssl-dev zip git nginx \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Set the working directory
WORKDIR /var/www/html
# Install Composer
RUN composer install
# Copy the application files
COPY . /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
RUN apt-get install -y git zip unzip
# Expose the necessary ports
EXPOSE 80

# Remove the default Nginx server definition and copy our own config
RUN rm /etc/nginx/sites-enabled/default
COPY nginx/mini-ticket-project.conf /etc/nginx/sites-available/mini-ticket-project.conf
RUN ln -s /etc/nginx/sites-available/mini-ticket-project.conf /etc/nginx/sites-enabled/

# Run both PHP-FPM and Nginx in the foreground
CMD service nginx start && php-fpm
