FROM php:8.1-apache

# Habilita mod_rewrite para URLs amigáveis
RUN a2enmod rewrite

# Instala extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Define diretório de trabalho
WORKDIR /var/www

# Copia arquivos públicos para pasta padrão do Apache
COPY public/ /var/www/html/

# Copia o restante dos arquivos
COPY App/ /var/www/App/
COPY vendor/ /var/www/vendor/

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html /var/www/App /var/www/vendor

EXPOSE 80
