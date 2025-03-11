# Use an official PHP image with Apache
FROM php:8.2-apache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies using Composer
RUN composer clear-cache && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --working-dir=/var/www/html

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/custom.ini
# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]