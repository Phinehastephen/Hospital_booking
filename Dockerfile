FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install PDO + MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80
