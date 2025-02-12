#!/bin/bash
# Run migrations, process the Nginx configuration template and start Nginx
php artisan migrate --force &&
# php artisan db:seed --force --class=TaxDocumentKindSeeder &&
# php artisan db:seed --force --class=UserWithNotificationSeeder &&
# php artisan cashier:webhook --url "https://taxfilebackend-production.up.railway.app/stripe/webhook" &&
node /assets/scripts/prestart.mjs /assets/nginx.template.conf  /nginx.conf &&
(php-fpm -y /assets/php-fpm.conf & nginx -c /nginx.conf)
