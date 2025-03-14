# Use Ubuntu 20.04 as base image
FROM ubuntu:20.04

# Prevent interactive prompts during package installation
ENV DEBIAN_FRONTEND=noninteractive

# Install essential dependencies & Microsoft SQL Server in one step
RUN apt-get update && apt-get install -y \
    curl \
    gnupg2 \
    software-properties-common \
    apt-transport-https \
    ca-certificates \
    wget \
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
    dos2unix && \
    curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    echo "deb [arch=amd64] https://packages.microsoft.com/ubuntu/20.04/mssql-server-2019 focal main" | tee /etc/apt/sources.list.d/mssql-server.list && \
    echo "deb [arch=amd64] https://packages.microsoft.com/ubuntu/20.04/prod focal main" | tee /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && ACCEPT_EULA=Y apt-get install -y mssql-server msodbcsql17 mssql-tools && \
    echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc

# Define an ARG for SA password (better security)
ARG SA_PASSWORD=YourStrongPassword123
ENV SA_PASSWORD=${SA_PASSWORD}
ENV ACCEPT_EULA=Y
ENV MSSQL_PID=Express

# Copy Apache configuration file
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2ensite 000-default && a2enmod rewrite

# Copy application files to Apache root directory
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 for Apache
EXPOSE 80

# Copy and fix the database initialization script
COPY init-db.sh /init-db.sh
RUN dos2unix /init-db.sh && chmod +x /init-db.sh

# Start database and Apache server
CMD ["/bin/bash", "-c", "/init-db.sh && apachectl -D FOREGROUND"]