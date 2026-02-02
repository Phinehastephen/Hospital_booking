FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install PDO + MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Clever Cloud uses PORT env variable (usually 8080)
ENV PORT=8080

# Make Apache listen on Clever Cloud port
RUN sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf \
 && sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080
