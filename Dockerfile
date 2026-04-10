FROM php:8.2-apache

# Modules Apache
RUN a2enmod rewrite headers

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# PHPMailer via Composer
COPY composer.json .
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Fichiers du site (proposition-2-premium = racine web)
COPY proposition-2-premium/ .

# Activer mail.php pour ce déploiement (remplace le mode Vercel)
RUN sed -i 's/action="#"/action="mail.php"/' contact.html

# Permissions
RUN chown -R www-data:www-data /var/www/html

# AllowOverride pour .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80
