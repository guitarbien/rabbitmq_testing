# rabbitmq_testing
a practice in php + rabbitmq using phpamqplib

### docker依序開啟rabbitmq php (nginx)並設定--link讓後面的可以認識host


    docker run -d --hostname my-rabbit --name some-rabbit rabbitmq:latest

    docker run --restart=always --name php_fpm_7.0.7-fpm -v /etc/localtime:/etc/localtime:ro -v ~/Documents/nginx_root/www/:/works/www --link some-rabbit:rabbit -d php:7.0.7-fpm

    docker run --restart=always --name nginx -v ~/nginx_setting/default.conf:/etc/nginx/conf.d/default.conf:ro -v /etc/localtime:/etc/localtime:ro -p 80:80 -e NGINX_SITE_ROOT=/usr/share/nginx/html -v ~/Documents/nginx_root/www/:/usr/share/nginx/html --link php_fpm_7.0.7-fpm -d nginx
  
