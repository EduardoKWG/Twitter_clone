FROM php:8.1-apache

# Habilita mod_rewrite para URLs amigáveis
RUN a2enmod rewrite

# Instala extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Instala o Composer dentro do container
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia arquivos públicos para pasta padrão do Apache
COPY public/ /var/www/html/

# Copia arquivos do app e do composer (sem o vendor)
COPY App/ /var/www/html/App/
COPY composer.json composer.lock /var/www/html/

# Instala as dependências com Composer
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões para o Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
