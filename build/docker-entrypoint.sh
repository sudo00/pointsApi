#!/usr/bin/env bash
set -e

printenv > /etc/environment

echo "Waiting for Mysql Server is up"
/wait-for-it.sh \
	--host=${MYSQL_HOST} \
	--port=${MYSQL_PORT} \
	--timeout=30 \
	--strict \
	-- echo "Mysql is up"

time php /var/www/app/yii migrate --interactive=0

/usr/bin/supervisord -n -c /etc/supervisord.conf

exec "$@"
