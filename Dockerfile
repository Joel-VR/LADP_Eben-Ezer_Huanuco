FROM php:8.2-apache

# PHP extensions: pgsql for Neon, sqlite for local fallback, mbstring/xml for YouTube feed
RUN apt-get update && apt-get install -y libpq-dev libsqlite3-dev \
  && docker-php-ext-install pdo pdo_pgsql pdo_sqlite \
  && a2enmod rewrite headers \
  && rm -rf /var/lib/apt/lists/*

# Apache: DocumentRoot -> /app/public, allow .htaccess, listen on $PORT (Render)
ENV APACHE_DOCUMENT_ROOT=/app/public
RUN sed -i 's|/var/www/html|/app/public|g' /etc/apache2/sites-available/000-default.conf \
  && printf '<Directory /app/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>\n' > /etc/apache2/conf-available/app.conf \
  && a2enconf app

WORKDIR /app
COPY . /app

# Expose new layout inside DocumentRoot (Apache follows symlinks)
RUN ln -sfn ../styles /app/public/styles \
  && ln -sfn ../assets /app/public/assets \
  && ln -sfn ../admin /app/public/admin \
  && mkdir -p /app/data && chown -R www-data:www-data /app/data /app/public

# Render sets $PORT; make Apache listen on it
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT:-80}/\" /etc/apache2/ports.conf && sed -i \"s/:80>/:${PORT:-80}>/\" /etc/apache2/sites-available/000-default.conf && apache2-foreground"]
