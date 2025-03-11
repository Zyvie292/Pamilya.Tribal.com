# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Update package list and install required dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    gnupg2 \
    apt-transport-https \
    ca-certificates \
    curl \
    unzip \
    software-properties-common \
    unixodbc \
    unixodbc-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions using docker-php-ext-install
RUN docker-php-ext-install mbstring xml bcmath tokenizer zip curl

# Install Microsoft SQL Server drivers
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    curl -sSL https://packages.microsoft.com/config/debian/12/prod.list | tee /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && ACCEPT_EULA=Y apt-get install -y \
    msodbcsql17 \
    mssql-tools \
    unixodbc-dev && \
    rm -rf /var/lib/apt/lists/*

# Install Microsoft SQL Server PHP extensions
RUN pecl install sqlsrv pdo_sqlsrv && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Copy project files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose port
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]