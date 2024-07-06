./wait-for.sh $DB_HOST:$DB_PORT -- echo 'Database is ready'

php artisan migrate
php artisan db:seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

/usr/bin/supervisord -c /etc/supervisord.conf
