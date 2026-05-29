FROM richarvey/nginx-php-fpm:latest
COPY . /var/www/html
ENV WEBROOT /var/www/html/public
ENV APP_ENV production

# Install dependencies inside the container image
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Tell Docker to run your script when the container boots up
ENTRYPOINT ["/var/www/html/build.sh"]