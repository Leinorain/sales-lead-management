FROM php:8.2-alpine

WORKDIR /var/www

COPY . /var/www

EXPOSE 8080

CMD ["php", "create-database.php"]
CMD ["php", "migration.php"]
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]