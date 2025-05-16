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

# Copia o arquivo de configuração Apache para substituir o padrão
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Copia o código do projeto para dentro do container
COPY . /var/www/html/

# Instala as dependências com Composer
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões para o Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
