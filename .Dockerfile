FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install mysqli
RUN docker-php-ext-install mysqli

# Set working directory
WORKDIR /var/www/

# Copy project files
COPY . .

# Fix permissions
RUN chown -R www-data:www-data /var/www/

EXPOSE 80

