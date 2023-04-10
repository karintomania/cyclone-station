FROM php:8.0-apache
RUN a2enmod rewrite \
&& a2ensite 000-default
RUN curl -fsSL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get -y update \
&& apt-get install -y libicu-dev gnupg2 unzip git nodejs \
&& docker-php-ext-install intl \
&& docker-php-ext-install pdo  mysqli pdo_mysql\
&& pecl install xdebug pcov
COPY --from=composer /usr/bin/composer /usr/bin/composer

CMD apachectl -D FOREGROUND
