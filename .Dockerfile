FROM php:8.3-apache

# Enable Apache rewrite (important for routing)
RUN a2enmod rewrite

# Install common PHP extensions (add more if needed)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files into Apache root
COPY . /var/www/

# Fix permissions
RUN chown -R www-data:www-data /var/www/

EXPOSE 80
