# Use the official PHP Apache image
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gnupg2 \
    unixodbc \
    unixodbc-dev \
    curl \
    apt-transport-https \
    software-properties-common

# Add Microsoft SQL Server repository
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list

# Install Microsoft ODBC Driver for SQL Server
RUN apt-get update && ACCEPT_EULA=Y apt-get install -y \
    msodbcsql18 \
    unixodbc-dev \
    libgssapi-krb5-2

# Install PHP extensions for Microsoft SQL Server
RUN docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr && \
    pecl install pdo_sqlsrv sqlsrv && \
    docker-php-ext-enable pdo_sqlsrv sqlsrv

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Copy project files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Change Apache DocumentRoot if your index.php is inside /public
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Ensure Apache serves index.php
RUN echo "<IfModule mod_dir.c>\n    DirectoryIndex index.php index.html\n</IfModule>" > /etc/apache2/conf-available/custom-directory-index.conf \
    && a2enconf custom-directory-index

# Restart Apache to apply changes
RUN service apache2 restart

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]