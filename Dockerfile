# Use Ubuntu as base image
FROM ubuntu:22.04

# Set environment variables
ENV ACCEPT_EULA=Y
ENV DEBIAN_FRONTEND=noninteractive

# Update package list and install necessary packages
RUN apt-get update && apt-get install -y \
    software-properties-common \
    apt-transport-https \
    ca-certificates \
    curl \
    wget \
    gnupg2 \
    unixodbc \
    unixodbc-dev \
    libapache2-mod-php \
    php \
    php-cli \
    php-mbstring \
    php-xml \
    php-odbc \
    php-mysqli \
    apache2 \
    && rm -rf /var/lib/apt/lists/*

# Install Microsoft ODBC Driver for Ubuntu 22.04
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && add-apt-repository "$(curl -fsSL https://packages.microsoft.com/config/ubuntu/22.04/prod.list)" \
    && apt-get update \
    && apt-get install -y msodbcsql18 mssql-tools18

# Enable Apache mods and restart service
RUN a2enmod rewrite \
    && service apache2 restart

# Copy application files to Apache root directory
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 for Apache
EXPOSE 80

# Start Apache server
CMD ["apachectl", "-D", "FOREGROUND"]