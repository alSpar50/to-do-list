# Użyj oficjalnego obrazu PHP jako bazowego
FROM php:8.1-fpm

# Ustaw zmienną środowiskową do zainstalowania narzędzi potrzebnych do budowy
ENV DEBIAN_FRONTEND=noninteractive

# Zainstaluj zależności systemowe oraz rozszerzenia wymagane przez gd i mbstring
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev \
    libxml2-dev \
    libonig-dev \
    locales \
    zip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Konfiguracja GD z obsługą FreeType, JPEG, WebP i XPM
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm

# Zainstaluj rozszerzenia PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Zainstaluj Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustaw katalog roboczy
WORKDIR /var/www

# Skopiuj pliki aplikacji
COPY . /var/www

# Zainstaluj zależności aplikacji
RUN composer install --optimize-autoloader --no-dev

# Zainstaluj zależności frontendowe i zbuduj zasoby produkcyjne
RUN apt-get update && apt-get install -y npm && rm -rf /var/lib/apt/lists/*
RUN npm install
RUN npm run build

# Ustaw uprawnienia
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Eksponuj port 9000
EXPOSE 9000

# Uruchom PHP-FPM
CMD ["php-fpm"]








