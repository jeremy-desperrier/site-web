FROM php:8.2-fpm

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www/html

# Copier le code
COPY . .

# Installer les dépendances
RUN composer install --no-scripts --no-progress --prefer-dist

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
