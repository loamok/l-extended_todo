FROM php:7.4-fpm-buster
ARG TIMEZONE

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

#
# If using a proxy please insert your proxy settings in an .env file
#
# PROXY, PROXYS as http://plop:tagada@proxy.exemple.com:8080 both for http and https
# NO_PROXY excluded domains direct acces
#
# If not using a proxy please leave the proxy section empty in an .env file

ARG docker_proxy
ARG dockers_proxy
ARG no_proxy
ARG apply_acl_to_root
ARG jwt_pass

env HTTP_PROXY=${docker_proxy}
env http_proxy=${docker_proxy}
env HTTPS_PROXY=${dockers_proxy}
env https_proxy=${dockers_proxy}
env NO_PROXY=${no_proxy}
env no_proxy=${no_proxy}
env APPLY_ACL_TO_ROOT=${apply_acl_to_root}
env JWT_PASSPHRASE=${jwt_pass}

run if [ ! -z ${docker_proxy} ] ; then echo "Proxy" ; else echo "no Proxy"; fi

# configuring proxy
RUN if [ ! -z ${docker_proxy} ] ; then \
        printf 'Acquire::http::Proxy "%s";' $docker_proxy >> /etc/apt/apt.conf; \
        echo "" >> /etc/apt/apt.conf; \
    fi;
RUN if [ ! -z ${docker_proxy} ] ; then \
        printf 'Acquire::https::Proxy "%s";' $dockers_proxy >> /etc/apt/apt.conf; \
        echo "" >> /etc/apt/apt.conf; \
    fi;

RUN apt-get update

RUN apt-get install -y \
    wget\
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype 

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring

# Set timezone
RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime && echo $TIMEZONE > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', $TIMEZONE > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

# trying to load composer from network with proxy
RUN if [ ! -z ${docker_proxy} ] ; then \
        curl -x "${dockers_proxy}" -sS https://getcomposer.org/installer > composerInstall.php; \
    else \
        curl -sS https://getcomposer.org/installer > composerInstall.php; \
    fi;
RUN test -f composerInstall.php && php composerInstall.php --install-dir=/usr/local/bin --filename=composer
RUN test -f composerInstall.php && rm composerInstall.php 

# trying to load symfony from network with proxy
RUN if [ ! -z ${docker_proxy} ] ; then \
        curl -x "${dockers_proxy}" -sS https://get.symfony.com/cli/installer  > symfonyInstall.sh; \ 
    else \
        curl -sS https://get.symfony.com/cli/installer  > symfonyInstall.sh; \ 
    fi;
RUN test -f symfonyInstall.sh && /bin/bash symfonyInstall.sh 
RUN test -f symfonyInstall.sh && rm symfonyInstall.sh 
RUN test -f /root/.symfony/bin/symfony && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY make-pems.sh /var/www/make-pems.sh
COPY composer_migrate.sh /var/www/composer_migrate.sh
COPY launch_me_at_boot.sh /var/www/launch_me_at_boot.sh
RUN test -f /var/www/make-pems.sh && chmod +x /var/www/make-pems.sh;
RUN test -f /var/www/composer_migrate.sh && chmod +x /var/www/composer_migrate.sh;
RUN test -f /var/www/launch_me_at_boot.sh && chmod +x /var/www/launch_me_at_boot.sh;

WORKDIR /var/www/l-extended_todo

ENTRYPOINT ["/var/www/launch_me_at_boot.sh"]
