# To-Do List Laravel Application

## Opis

Aplikacja To-Do List stworzona w Laravel z wykorzystaniem Docker. Umożliwia zarządzanie zadaniami, ich edycję, udostępnianie oraz integrację z Google Calendar.

## Technologie

- Laravel 10.x
- PHP 8.1
- MySQL 5.7
- Nginx
- Docker & Docker Compose
- Vite (Frontend)
- Breeze

## Wymagania

- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/)

## Instalacja

### Opcja 1: Użycie Docker

1. **Sklonuj repozytorium:**

    ```bash
    git clone https://github.com/alSpar50/to-do-list.git
    cd to-do-list
    ```

2. **Skopiuj plik (zawartość) `.env.example` do `.env`:**

    ```bash
    cp .env.example .env
    ```

3. **Skonfiguruj zmienne środowiskowe w `.env`:**

    - **Baza Danych:**
        ```env
        DB_CONNECTION=mysql
        DB_HOST=db
        DB_PORT=3306
        DB_DATABASE=laravel
        DB_USERNAME=laravel
        DB_PASSWORD=laravel
        ```
    - **Mailer:**
      Upewnij się, że ustawienia SMTP są poprawne. Należy tam podać dane od swojego dostawcy poczty. Zwróć też uwagę na dane do Google Calendard. W CLIENT ID oraz CLIENT SECRET należy podać dane z Google Developer Console
    - **Google Calendar:**
        ```env
        GOOGLE_CALENDAR_CLIENT_ID=your_google_client_id
        GOOGLE_CALENDAR_CLIENT_SECRET=your_google_client_secret
        GOOGLE_CALENDAR_REDIRECT_URI=http://localhost:8000/google-calendar/callback
        GOOGLE_CALENDAR_DEFAULT_TIMEZONE=Europe/Warsaw
        ```

4. **Uruchom kontenery Docker:**

    ```bash
    docker-compose up -d
    ```

   **Uwaga:** Uruchomienie z flagą `-d` (detached mode) pozwala kontenerom działać w tle.

5. **Generuj klucz aplikacji:**

    ```bash
    docker-compose exec app php artisan key:generate
    ```

6. **Uruchom migracje bazy danych:**

    ```bash
    docker-compose exec app php artisan migrate
    ```

7. **Zainstaluj zależności frontendowe i zbuduj zasoby produkcyjne:**

    ```bash
    docker-compose exec app npm install
    docker-compose exec app npm run build
    ```

8. **Queue Worker jest uruchamiany automatycznie jako osobna usługa (`laravel-queue-worker`).**

9. **Dostęp do aplikacji:**

   Otwórz przeglądarkę i przejdź do [http://localhost:8000/tasks](http://localhost:8000/tasks)
   Załóż konto podając nazwę, maila oraz hasło. Po tym dostaniesz się do aplikacji to-do-list. W razie problemu (gdyby załadowała się inna strona, np. /dashboard z błędem 404 Not Found, należy w przeglądarce po prostu przejść do /tasks)

### Opcja 2: Uruchomienie Lokalnie (bez Docker)

1. **Sklonuj repozytorium:**

    ```bash
    git clone https://github.com/twoje-konto/todo-list.git
    cd todo-list
    ```

2. **Skopiuj plik `.env.example` do `.env`:**

    ```bash
    cp .env.example .env
    ```

3. **Skonfiguruj zmienne środowiskowe w `.env`:**

    - **Baza Danych:**
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=todo_list_db
        DB_USERNAME=root
        DB_PASSWORD=*** 
        ```
    - **Mailer:**
      Upewnij się, że ustawienia SMTP są poprawne.
    - **Google Calendar:**
        ```env
        GOOGLE_CALENDAR_CLIENT_ID=your_google_client_id
        GOOGLE_CALENDAR_CLIENT_SECRET=your_google_client_secret
        GOOGLE_CALENDAR_REDIRECT_URI=http://localhost:8000/google-calendar/callback
        GOOGLE_CALENDAR_DEFAULT_TIMEZONE=Europe/Warsaw
        ```

4. **Zainstaluj zależności aplikacji:**

    ```bash
    composer install
    npm install
    npm run build
    ```

5. **Generuj klucz aplikacji:**

    ```bash
    php artisan key:generate
    ```

6. **Uruchom migracje bazy danych:**

    ```bash
    php artisan migrate
    ```

7. **Uruchom queue worker:**

   W nowym terminalu lub PowerShell, uruchom queue worker:

    ```bash
    php artisan queue:work
    ```

8. **Uruchom serwer lokalny Laravel w kolejnym terminalu:**

    ```bash
    php artisan serve --host=127.0.0.1 --port=8000
    ```

9. **Dostęp do aplikacji:**

   Otwórz przeglądarkę i przejdź do [http://localhost:8000/tasks](http://localhost:8000/tasks) - w razie problemów spójrz poprzedni sposób.

## **9. Dodatkowe Wskazówki**

### **a. Monitorowanie Logów Kontenerów**

Regularnie sprawdzaj logi kontenerów, aby szybko identyfikować i rozwiązywać problemy:

```bash
docker-compose logs -f app
docker-compose logs -f queue-worker
docker-compose logs -f db
docker-compose logs -f webserver


Mój przykładowy .env (bez klucza i haseł):

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:########################################
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

#poniższa sekcja jest już przygotowana dla dockera. Jakby ktoś chciał uruchomić w lokalnym środowisku bez dockera, to trzeba sobie postawić samemu bazę MySQL i umieścić tu odpowiednie dane.

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.dpoczta.pl
MAIL_PORT=25
MAIL_USERNAME=?????@dpoczta.pl
MAIL_PASSWORD=###################
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="?????@dpoczta.pl"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

GOOGLE_CALENDAR_CLIENT_ID=your_google_client_id #należy zmienić te dane na odpowiednie czyli te z Google Developers Console. Te dane musi dostarczyć Wasz programista/informatyk.  
GOOGLE_CALENDAR_CLIENT_SECRET=your_google_client_secret #należy zmienić te dane na odpowiednie -||-
GOOGLE_CALENDAR_REDIRECT_URI=http://localhost:8000/google-calendar/callback
GOOGLE_CALENDAR_DEFAULT_TIMEZONE=Europe/Warsaw


===============================================================
W razie problemów umieszczam zawartość kilku kluczowych plików:

Dockerfile:

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



======================================================================================
docker-compose.yml

version: '3.8'  # Możesz usunąć wersję, jeśli Docker Compose to zaleca. Te nowsze Dockery chyba nawet wymagają aby tę linijkę usunąć więc w razie czego najlepiej zaczynać bez tego.

services:
    app:
        build:
            context: .
            dockerfile: Dockerfile
        image: laravel-app
        container_name: laravel-app
        restart: unless-stopped
        working_dir: /var/www
        volumes:
            - .:/var/www
            - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
        networks:
            - laravel
        depends_on:
            - db

    webserver:
        image: nginx:alpine
        container_name: nginx-webserver
        restart: unless-stopped
        ports:
            - "8000:80"
        volumes:
            - .:/var/www
            - ./docker/nginx/conf.d:/etc/nginx/conf.d
        networks:
            - laravel
        depends_on:
            - app

    db:
        image: mysql:5.7
        container_name: mysql-db
        restart: unless-stopped
        environment:
            MYSQL_DATABASE: laravel
            MYSQL_ROOT_PASSWORD: root
            MYSQL_USER: laravel
            MYSQL_PASSWORD: laravel
        ports:
            - "3307:3306"  # Upewnij się, że port jest dostępny
        volumes:
            - dbdata:/var/lib/mysql
        networks:
            - laravel

    queue-worker:
        image: laravel-app  # Używa tego samego obrazu co usługa app
        container_name: laravel-queue-worker
        restart: unless-stopped
        working_dir: /var/www
        volumes:
            - .:/var/www
            - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
        networks:
            - laravel
        command: php artisan queue:work --verbose --tries=3
        depends_on:
            - app
            - db

networks:
    laravel:
        driver: bridge

volumes:
    dbdata:
