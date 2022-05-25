#!/bin/bash
set -euo pipefail

cp /tmp/wp-config.php /var/www/html/wp-config.php
chown www-data:www-data /var/www/html/wp-config.php
chmod 400 /var/www/html/wp-config.php

TERM=xterm PAGER='busybox less' php -- <<'EOPHP'
<?php
// database might not exist, so let's try creating it (just to be safe)

$stderr = fopen('php://stderr', 'w');

// https://codex.wordpress.org/Editing_wp-config.php#MySQL_Alternate_Port
//   "hostname:port"
// https://codex.wordpress.org/Editing_wp-config.php#MySQL_Sockets_or_Pipes
//   "hostname:unix-socket-path"
list($host, $socket) = explode(':', getenv('WORDPRESS_DB_HOST'), 2);
$port = 0;
if (is_numeric($socket)) {
	$port = (int) $socket;
	$socket = null;
}
$user = getenv('WORDPRESS_DB_USER');
$pass = getenv('WORDPRESS_DB_PASSWORD');
$dbName = getenv('WORDPRESS_DB_NAME');

$maxTries = 10;
do {
	$mysql = new mysqli($host, $user, $pass, '', $port, $socket);
	if ($mysql->connect_error) {
		fwrite($stderr, 'MySQL Connection Error: (' . $mysql->connect_errno . ') ' . $mysql->connect_error . "\n");
		--$maxTries;
		if ($maxTries <= 0) {
			exit(1);
		}
		sleep(3);
	}
} while ($mysql->connect_error);

if (!$mysql->query('CREATE DATABASE IF NOT EXISTS `' . $mysql->real_escape_string($dbName) . '`')) {
	fwrite($stderr, "\n" . 'MySQL "CREATE DATABASE" Error: ' . $mysql->error . "\n");
	$mysql->close();
	exit(1);
}

$mysql->close();

$installWP = getenv('WORDPRESS_INSTALL');
$WPversion = getenv('WORDPRESS_VERSION');
if ($installWP) {
	fwrite($stderr, "Installing WordPress ($WPversion) files\n");
	if (!file_exists('/var/www/html/wp-includes/version.php')) {
		exec("wp --allow-root core download --version=$WPversion");
	} else {
		exec("wp --allow-root core update --version=$WPversion");
	}
	$wpUser = getenv('WORDPRESS_USER_NAME');
	$wpPass = getenv('WORDPRESS_USER_PASS');
	$wpEmail = getenv('WORDPRESS_USER_EMAIL');
	$wpURL = getenv('WORDPRESS_USER_URL');
	exec("wp --allow-root core install --skip-email --url=$wpURL --title='Creativity is Coming' --admin_user=$wpUser --admin_password=$wpPass --admin_email=$wpEmail");
	exec("wp --allow-root core update-db");
} 

// see if we need to copy files over
if (file_exists('/var/www/html/wp-includes/version.php')) {
	include '/var/www/html/wp-includes/version.php';
	$installedWPversion = $wp_version;
} else {
	$installedWPversion = '0.0.0';
}

fwrite($stderr, "Container WP version: $containerWPversion - Installed WP version: $installedWPversion\n");
if(version_compare($containerWPversion, $installedWPversion, '>')) {
	fwrite($stderr, "Copying over wordpress files in container to /var/www/html\n");
	exec('rsync -au /var/www/html-original/ /var/www/html');
	exec("wp --allow-root core update-db");
}

// theme
if (file_exists('/var/www/html/wp-includes/version.php')) {
	if (filemtime('/var/www/html-original/wp-content/themes') > filemtime('/var/www/html/wp-content/themes')) {
		fwrite($stderr, "Updating theme files\n");
		exec('rsync -au --delete-after /var/www/html-original/wp-content/themes/ /var/www/html/wp-content/themes');
	}
}

// plugins
$pluginInstall = getenv('WORDPRESS_PLUGINS_INSTALL');
if ($pluginInstall) {
	$plugins = explode(',', getenv('WORDPRESS_PLUGINS'));
	foreach ($plugins as $plugin) {
		fwrite($stderr, "Updating $plugin to the latest version\n");
		exec("wp --allow-root plugin install $plugin --force");
	}
} 

EOPHP

chown -R www-data:www-data /var/www/html

/usr/sbin/nginx -g 'daemon off;pid /run/nginx.pid;' &

exec "$@"