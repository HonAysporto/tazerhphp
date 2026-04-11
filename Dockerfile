FROM php:8.2-apache

# Enable MySQL extensions (THIS FIXES YOUR ERROR)
RUN docker-php-ext-install mysqli

# Optional but recommended (for modern compatibility)
RUN docker-php-ext-install pdo pdo_mysql

# Copy project
COPY . /var/www/html

# Copy SSL (Aiven requirement)
COPY ca.pem /app/ca.pem

EXPOSE 80