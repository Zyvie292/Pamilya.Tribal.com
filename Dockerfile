# Use an official PHP image with Apache
FROM php:8.2-apache

# Set up Apache VirtualHost
RUN echo '<VirtualHost *:80> \
    DocumentRoot /var/www/html/public \
    <Directory /var/www/html/public> \
        AllowOverride All \
        Require all granted \
    </Directory> \
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Enable required Apache modules
RUN a2enmod rewrite

# Set the correct document root to the public folder
WORKDIR /var/www/html/public

# Copy project files
COPY . /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y unzip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --working-dir=/var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port 80 for Apache
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]