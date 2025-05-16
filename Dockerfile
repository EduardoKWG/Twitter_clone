# Usa imagem oficial PHP com Apache
FROM php:8.1-apache

# Instala extensões necessárias (pdo, pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# Copia todo o código para dentro do container, dentro da pasta padrão do Apache
COPY public/ /var/www/html/

# Copia o restante dos arquivos que não são públicos para uma pasta fora do root web
COPY App/ /var/www/App/
COPY vendor/ /var/www/vendor/

# Ajusta permissões (caso precise)
RUN chown -R www-data:www-data /var/www/html /var/www/App /var/www/vendor

# Expõe a porta 80
EXPOSE 80
