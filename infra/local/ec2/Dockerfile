# Start from Amazon Linux 2023 as the base image
FROM amazonlinux:2023

# Update system and install basic tools
RUN dnf update -y \
    && dnf install -y nginx php php-fpm \
    && dnf clean all

# Install additional PHP extensions and Supervisor
RUN dnf install -y php-mysqlnd php-xml php-mbstring php-json php-gd php-pdo php-opcache \
    && dnf install -y php-pecl-redis php-zip \
    && dnf install -y python3-pip \
    && pip3 install supervisor \
    && ln -s /usr/local/bin/supervisord /usr/bin/supervisord \
    && ln -s /usr/local/bin/supervisorctl /usr/bin/supervisorctl \
    && dnf clean all

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Configure PHP-FPM to run as 'nginx' user and tweak files for development
RUN mkdir -p /run/php-fpm \
    && sed -i 's/^user = .*/user = nginx/' /etc/php-fpm.d/www.conf \
    && sed -i 's/^group = .*/group = nginx/' /etc/php-fpm.d/www.conf \
    && sed -i 's/^listen = .*/listen = 9000/' /etc/php-fpm.d/www.conf \
    && sed -i 's/^;pid = .*/pid = \/run\/php-fpm\/php-fpm.pid/' /etc/php-fpm.d/www.conf

# Set up Nginx server directory
RUN mkdir -p /var/www/html

# Copy nginx configuration
COPY infra/local/ec2/nginx/nginx.conf /etc/nginx/nginx.conf
COPY infra/local/ec2/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy PHP configuration
COPY infra/local/ec2/php/php.ini /etc/php.ini

# Copy supervisor configuration
COPY infra/local/ec2/supervisor/supervisord.conf /etc/supervisord.conf

# Set proper permissions and create necessary directories
RUN mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /var/log/supervisor \
    && chown -R nginx:nginx /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expose the HTTP and PHP-FPM ports
EXPOSE 80 9000

# Create startup script
RUN echo '#!/bin/bash' > /start.sh \
    && echo 'mkdir -p /run/php-fpm' >> /start.sh \
    && echo 'chown -R nginx:nginx /var/www/html' >> /start.sh \
    && echo 'chmod -R 755 /var/www/html' >> /start.sh \
    && echo 'chmod -R 775 /var/www/html/storage' >> /start.sh \
    && echo 'chmod -R 775 /var/www/html/bootstrap/cache' >> /start.sh \
    && echo 'exec /usr/local/bin/supervisord -c /etc/supervisord.conf' >> /start.sh \
    && chmod +x /start.sh

# Start services with supervisor
CMD ["/start.sh"]
