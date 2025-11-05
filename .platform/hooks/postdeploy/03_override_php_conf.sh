#!/bin/bash

echo "=== Starting Clean URLs Configuration ==="

# Override EB's php.conf with our clean URLs version
echo "Overriding EB php.conf with clean URLs config..."
cat > /etc/nginx/conf.d/elasticbeanstalk/php.conf << 'EOF'
# Laravel clean URLs configuration - overriding default EB PHP config
root /var/app/current/public;
index index.php index.html index.htm;

# Clean URL routing - this enables /products/37 instead of /index.php/products/37
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

# PHP processing
location ~ \.(php|phar)(/.*)?$ {
    fastcgi_split_path_info ^(.+\.(?:php|phar))(/.*)$;
    fastcgi_intercept_errors on;
    fastcgi_index index.php;
    
    fastcgi_param QUERY_STRING $query_string;
    fastcgi_param REQUEST_METHOD $request_method;
    fastcgi_param CONTENT_TYPE $content_type;
    fastcgi_param CONTENT_LENGTH $content_length;
    fastcgi_param SCRIPT_NAME $fastcgi_script_name;
    fastcgi_param REQUEST_URI $request_uri;
    fastcgi_param DOCUMENT_URI $document_uri;
    fastcgi_param DOCUMENT_ROOT $document_root;
    fastcgi_param SERVER_PROTOCOL $server_protocol;
    fastcgi_param REQUEST_SCHEME $scheme;
    fastcgi_param HTTPS $https if_not_empty;
    fastcgi_param GATEWAY_INTERFACE CGI/1.1;
    fastcgi_param SERVER_SOFTWARE nginx/$nginx_version;
    fastcgi_param REMOTE_ADDR $remote_addr;
    fastcgi_param REMOTE_PORT $remote_port;
    fastcgi_param SERVER_ADDR $server_addr;
    fastcgi_param SERVER_PORT $server_port;
    fastcgi_param SERVER_NAME $server_name;
    fastcgi_param REDIRECT_STATUS 200;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param PATH_INFO $fastcgi_path_info;
    
    # AWS ELB headers
    fastcgi_param HTTP_X_FORWARDED_PROTO $http_x_forwarded_proto;
    fastcgi_param HTTP_X_FORWARDED_FOR $http_x_forwarded_for;
    
    fastcgi_pass php-fpm;
}
EOF

echo "Testing nginx configuration..."
nginx -t

if [ $? -eq 0 ]; then
    echo "Nginx configuration test passed. Reloading..."
    systemctl reload nginx
    echo "=== Clean URLs enabled successfully! ==="
else
    echo "ERROR: Nginx configuration test failed."
fi

echo "=== Clean URL Configuration Complete ==="