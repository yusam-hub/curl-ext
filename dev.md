#### testing php74

    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/curl-ext && exec bash"

    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/curl-ext && composer update"
    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/curl-ext && composer install"
    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/curl-ext && sh phpunit"
    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/curl-ext && git status"
    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/curl-ext && git pull"

#### testing php83

    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/curl-ext && exec bash"

    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/curl-ext && composer update"
    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/curl-ext && composer install"
    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/curl-ext && sh phpunit"
    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/curl-ext && git status"
    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/curl-ext && git pull"
