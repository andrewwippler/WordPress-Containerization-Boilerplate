FROM php:8.0-fpm-alpine

ENV WORDPRESS_VERSION=latest \
	WORDPRESS_INSTALL=false \
	WORDPRESS_PLUGINS=wp-smushit,wp-fastest-cache,google-sitemap-generator,jetpack \
	WORDPRESS_PLUGINS_INSTALL=false \
	WORDPRESS_USER_NAME=admin \
	WORDPRESS_USER_PASS=wordpress \
	WORDPRESS_USER_EMAIL=nobody@example.org \
	WORDPRESS_USER_URL="http://localhost:8080"

# dependencies
RUN apk add --no-cache \
		bash \
		rsync \
		sudo \
		nginx

# install the PHP extensions we need
RUN set -ex; \
	\
	apk add --no-cache --virtual .build-deps \
		libjpeg-turbo-dev \
		libpng-dev \
	; \
	\
	docker-php-ext-configure gd; \
	docker-php-ext-install gd mysqli opcache; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --recursive \
			/usr/local/lib/php/extensions \
			| awk '{ gsub(/,/, "\nso:", $2); print "so:" $2 }' \
			| sort -u \
			| xargs -r apk info --installed \
			| sort -u \
	)"; \
	apk add --virtual .wordpress-phpexts-rundeps $runDeps; \
	apk del .build-deps

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
		echo 'upload_max_filesize=25M'; \
		echo 'post_max_size=25M'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

ADD https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar /usr/local/bin/wp
RUN chmod +x /usr/local/bin/wp
VOLUME /var/www/html
ADD html /var/www/html-original
WORKDIR /var/www/html

COPY wp-config.php /tmp/wp-config.php
COPY nginx.conf /etc/nginx/nginx.conf

COPY docker-entrypoint.sh /usr/local/bin/
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]
