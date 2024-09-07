# Usa uma imagem base do PHP 8.3.11
FROM php-grpc-base:8.3.11

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /app

# Copia o arquivo de dependências e o código para o contêiner
COPY composer.json composer.lock ./
RUN composer install

# Copia o código da aplicação para o contêiner
COPY ../ .