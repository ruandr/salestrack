FROM php:8.3-fpm

# Dependências básicas
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho dentro do container
WORKDIR /var/www

# Copiar o composer.json e composer.lock primeiro
COPY backend/composer.json backend/composer.lock ./

# Instalar dependências do Laravel sem executar scripts do composer ainda
RUN composer install --no-scripts --no-dev --optimize-autoloader

# Agora copiar o restante dos arquivos da aplicação
COPY backend ./

# Rodar os scripts
RUN composer run-script post-autoload-dump

# Gerar a chave do Laravel
RUN php artisan key:generate

# Expor a porta 8000
EXPOSE 8000

# Iniciar o servidor Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
