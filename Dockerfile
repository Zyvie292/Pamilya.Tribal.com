# Use an official PHP image with Apache
FROM php:8.2-apache

# Install extensions (including SQL Server drivers)
RUN apt-get update && apt-get install -y \
    unixodbc \
    unixodbc-dev \
    gnupg \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17

# Copy your project files into the container
COPY . /var/www/html

# Expose port 80 (Apache)
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]