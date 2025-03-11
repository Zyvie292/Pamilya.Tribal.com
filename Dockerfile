# Use an official PHP image with Apache
FROM php:8.2-apache

# Set environment variables (useful for debugging)
ENV DEBIAN_FRONTEND=noninteractive

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Install dependencies for Microsoft SQL Server and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    gnupg2 \
    apt-transport-https \
    curl \
    && curl -sSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && echo "deb [arch=amd64] https://packages.microsoft.com/debian/10/prod buster main" | tee /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 unixodbc-dev \
    && apt-get install -y libapache2-mod-php php-mbstring php-xml php-bcmath php-tokenizer php-zip php-cli php-curl \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-configure pdo_sqlsrv --with-pdo-sqlsrv=shared \
    && docker-php-ext-install pdo_sqlsrv sqlsrv

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . /var/www/html

# Set permissions to avoid permission issues
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Set PHP memory limit to unlimited
RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/custom.ini

# Install PHP dependencies using Composer
RUN composer clear-cache && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --working-dir=/var/www/html || cat /var/www/html/composer.lock

# Fix Apache ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]