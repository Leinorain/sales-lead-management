FROM php:8.0-alpine

WORKDIR /var/www

COPY . /var/www

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]