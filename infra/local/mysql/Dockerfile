FROM mysql:8.0

# Copy custom MySQL configuration
COPY ./infra/local/mysql/my.cnf /etc/mysql/conf.d/

# Set environment variables
ENV MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
ENV MYSQL_DATABASE=${MYSQL_DATABASE}
ENV MYSQL_USER=${MYSQL_USER}
ENV MYSQL_PASSWORD=${MYSQL_PASSWORD}

# Expose MySQL port
EXPOSE 3306
