# Use PHP 8.2 base image
FROM php:8.2-cli

# Install required dependencies
RUN apt-get update && apt-get install -y \
    unixodbc \
    unixodbc-dev \
    libgssapi-krb5-2 \
    curl \
    gnupg \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y \
    msodbcsql17 \
    mssql-tools \
    unixodbc-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP SQLSRV extensions
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Set environment variables
ENV ACCEPT_EULA=Y
ENV PATH="$PATH:/opt/mssql-tools/bin"

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install composer dependencies
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Start application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]