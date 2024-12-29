#!/bin/bash

# Build assets using NPM
npm run build

# generate docs
php artisan scribe:generate

# Clear cache
php artisan optimize:clear

# Cache the various components of the Laravel application
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
