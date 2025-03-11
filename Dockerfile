# Use an official PHP image with Apache
FROM php:8.2-apache

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV ACCEPT_EULA=Y

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gnupg2 \
    apt-transport-https \
    software-properties-common \
    curl \
    unzip \
    lsb-release \
    unixodbc \
    unixodbc-dev \
    libgssapi-krb5-2 \
    libapache2-mod-php \
    php-cli \
    php-mbstring \
    php-xml \
    php-bcmath \
    php-tokenizer \
    php-zip \
    php-curl \
    && rm -rf /var/lib/apt/lists/*

# Add Microsoft SQL Server ODBC Driver repository
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    echo "deb [arch=amd64] https://packages.microsoft.com/debian/$(lsb_release -rs)/prod $(lsb_release -cs) main" | tee /etc/apt/sources.list.d/mssql-release.list

# Update packages
RUN apt-get update

# Install Microsoft SQL Server ODBC Driver
RUN apt-get install -y msodbcsql17

# Install PHP extensions
RUN docker-php-ext-install mbstring xml bcmath tokenizer zip curl pdo pdo_mysql

# Install SQLSRV & PDO_SQLSRV PHP extensions
RUN pecl install sqlsrv pdo_sqlsrv && \
    docker-php-ext-enable sqlsrv pdo_sqlsrv

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Increase PHP memory limit
RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/custom.ini

# Fix Apache ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install PHP dependencies using Composer
RUN composer clear-cache && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --working-dir=/var/www/html

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]