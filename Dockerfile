FROM php:8.2-apache

# Enable MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy app files
COPY . /var/www/html

# Copy SSL certificate
COPY ca.pem /app/ca.pem

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80