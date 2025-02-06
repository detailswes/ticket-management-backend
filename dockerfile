# Use official PHP image with PHP-FPM
FROM php:8.2-fpm

# Install required system packages and Composer
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libssl-dev zip git unzip nginx supervisor libicu-dev curl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql bcmath intl zip \
    # Install Node.js and npm
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Verify Composer installation
RUN composer --version

# Verify Node.js and npm installation
RUN node -v && npm -v

# Set the working directory
WORKDIR /var/www/html

# Copy Composer files first for better caching
COPY composer.json composer.lock ./

# Install Composer dependencies before copying the full app
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --no-interaction

# Install Node.js dependencies (npm install)
COPY package.json package-lock.json ./
RUN npm install

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

# Start services
CMD service nginx start && php-fpm && npm run build
