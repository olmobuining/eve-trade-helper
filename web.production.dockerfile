FROM nginx:1.10

ADD vhost.production.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www

COPY . /var/www
