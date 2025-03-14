# Use Ubuntu 20.04 as base image (MSSQL Server compatible)
FROM ubuntu:20.04

# Install dependencies
RUN apt-get update && apt-get install -y curl gnupg2 software-properties-common

# Add Microsoft SQL Server repository
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN add-apt-repository "$(curl -fsSL https://packages.microsoft.com/config/ubuntu/20.04/mssql-server-2019.list)"

# Install Microsoft SQL Server
RUN apt-get update && apt-get install -y mssql-server

# Set environment variables for SQL Server
ENV ACCEPT_EULA=Y
ENV SA_PASSWORD=YourStrongPassword123
ENV MSSQL_PID=Express

# Install Apache, PHP, and Microsoft ODBC Driver
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
    msodbcsql17 \
    mssql-tools18 \
    && rm -rf /var/lib/apt/lists/*

# Copy Apache configuration file
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2ensite 000-default \
    && a2enmod rewrite

# Copy application files to Apache root directory
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 for Apache
EXPOSE 80

# Copy database initialization script
COPY init-db.sh /init-db.sh
RUN dos2unix /init-db.sh && chmod +x /init-db.sh
# Start database and Apache server
CMD /init-db.sh && apachectl -D FOREGROUND