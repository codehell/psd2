FROM phpdockerio/php73-fpm:latest
WORKDIR /application

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.3-mysql php-redis php7.3-sqlite3 php7.3-bz2 php7.3-xsl php-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

CMD ["php", "-S", "0.0.0.0:8001"]
#CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]
