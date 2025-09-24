FROM tomsik68/xampp

RUN apt-get update &&   \
    apt-get install -y  \
    composer    \
    php-mysql   \
    php-yaml    \
    php-dom

WORKDIR /opt/lampp/htdocs
