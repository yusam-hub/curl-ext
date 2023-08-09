#### dockers

    docker exec -it yusam-php81 sh

    docker exec -it yusam-php81 sh -c "php -m"
    docker exec -it yusam-php81 sh -c "date"

    docker exec -it yusam-php81 bash
    docker exec -it yusam-php81 sh -c "htop"

    docker exec -it yusam-php81 sh -c "cd /var/www/data/yusam/github/yusam-hub/curl-ext && composer update"
    docker exec -it yusam-php81 sh -c "cd /var/www/data/yusam/github/yusam-hub/curl-ext && sh phpunit"