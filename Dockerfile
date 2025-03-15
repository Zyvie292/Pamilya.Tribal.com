# Use the official PHP 8.2 base image
FROM php:8.2-cli

# Install required dependencies
RUN apt-get update && apt-get install -y \
    apt-transport-https \
    lsb-release \
    gnupg2 \
    curl \
    unixodbc \
    unixodbc-dev \
    libgssapi-krb5-2

# Add Microsoft package repository
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    curl -fsSL https://packages.microsoft.com/config/debian/11/prod.list | tee /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update

# Install Microsoft ODBC Driver and tools
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools

# Install SQLSRV and PDO_SQLSRV extensions for PHP
RUN pecl install sqlsrv pdo_sqlsrv && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Set environment variables for Microsoft SQL Server tools
ENV PATH="$PATH:/opt/mssql-tools/bin"

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer dependencies
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

RUN composer install --no-dev --optimize-autoloader

# Start the application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]