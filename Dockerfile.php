# Use a imagem base do PHP com Apache
FROM php:8.0-apache

# Instala extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Copia o código da aplicação para o diretório do servidor Apache
COPY php/ .

RUN composer install
# Permite reescrever URLs (se necessário)
RUN a2enmod rewrite

