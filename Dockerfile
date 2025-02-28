FROM php:8.4

# Update deps and install php extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    libzip-dev \
    unzip \
    zlib1g-dev \
    && docker-php-ext-install pdo pdo_pgsql # Install depending on the database you will use

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the app to the container
WORKDIR /app
COPY . .

# Composer install deps
RUN composer install

# Optimizations and cache
RUN php artisan optimize
RUN php artisan config:cache
RUN php artisan event:cache
RUN php artisan route:cache
# RUN php artisan view:cache # Uncomment if you are using views

# Start the server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
