# Use a smaller base image to reduce memory usage
FROM ubuntu:20.04

# Set noninteractive mode to avoid prompts during installation
ENV DEBIAN_FRONTEND=noninteractive

# Install essential dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
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
    rm -rf /var/lib/apt/lists/*  # Free up memory

# Add Microsoft SQL Server repository and install required packages
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    echo "deb [arch=amd64] https://packages.microsoft.com/ubuntu/20.04/prod focal main" > /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && ACCEPT_EULA=Y apt-get install -y --no-install-recommends \
    mssql-server msodbcsql17 mssql-tools && \
    rm -rf /var/lib/apt/lists/*  # Free up memory

# Set up environment variables
ENV ACCEPT_EULA=Y
ENV MSSQL_PID=Express

# Copy Apache configuration
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

# Optimize memory usage by setting limits for SQL Server
ENV MSSQL_MEMORY_LIMIT_MB=256  
# Adjust based on available memory

# Start database and Apache server
CMD ["/bin/bash", "-c", "/init-db.sh && apachectl -D FOREGROUND"]