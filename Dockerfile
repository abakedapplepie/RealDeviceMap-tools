FROM php:7.2.2-apache

# Remove folders from the html-folder (in case of an update)
WORKDIR /var/www/html/
RUN rm -rf /var/www/html/*

# Copy all files over to the working directory
COPY ./css/* /var/www/html/css/
COPY ./other/* /var/www/html/other/
COPY ./index.php /var/www/html/

# Copy config-files over to the working directory
COPY ./config/config.env.php /var/www/html/config/config.php
#COPY ./.htaccess.example /var/www/html/.htaccess

# Run the application
RUN docker-php-ext-install pdo pdo_mysql