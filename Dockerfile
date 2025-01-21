# Use the official PHP 8.1 image with Apache
FROM php:8.1-apache

# Install system dependencies, PHP extensions, and MySQL client
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    netcat-openbsd \
    default-mysql-client && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip && \
    a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Cache Laravel configuration, routes, and views
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache
# Set up Apache configuration
RUN echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files to the container
COPY . /var/www/html

# Install Laravel dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Add custom entrypoint script to run the app key generation and migrations
COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start the Apache server and run the entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
