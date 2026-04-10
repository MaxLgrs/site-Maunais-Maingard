FROM php:8.2-apache

# Modules Apache
RUN a2enmod rewrite headers

WORKDIR /var/www/html

# Fichiers du site (proposition-2-premium = racine web)
# vendor/phpmailer et vendor/autoload.php sont inclus dans le repo
COPY proposition-2-premium/ .

# Activer mail.php pour ce déploiement (remplace le mode Vercel)
RUN sed -i 's/action="#"/action="mail.php"/' contact.html

# Permissions
RUN chown -R www-data:www-data /var/www/html

# AllowOverride pour .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80
