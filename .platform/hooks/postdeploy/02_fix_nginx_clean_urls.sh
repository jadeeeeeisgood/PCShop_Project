#!/bin/bash

# Fix nginx configuration for Laravel clean URLs
# This runs after EB has deployed the app

echo "=== Starting Clean URLs Configuration ==="

# Remove any conflicting configs
echo "Removing conflicting nginx configs..."
rm -f /etc/nginx/conf.d/default.conf
rm -f /etc/nginx/conf.d/*laravel*.conf

# Add our Laravel server block to nginx
echo "Creating clean URLs nginx config..."
cat > /etc/nginx/conf.d/laravel-clean-urls.conf << 'EOF'
# Laravel configuration for clean URLs
server {
    listen 80;
    server_name _;
    root /var/app/current/public;
    index index.php;

    # Clean URL routing - this enables /products/37 instead of /index.php/products/37
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP processing
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php-fpm/www.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param QUERY_STRING $query_string;
        fastcgi_param REQUEST_METHOD $request_method;
        fastcgi_param CONTENT_TYPE $content_type;
        fastcgi_param CONTENT_LENGTH $content_length;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
        fastcgi_param REQUEST_URI $request_uri;
        fastcgi_param DOCUMENT_URI $document_uri;
        fastcgi_param DOCUMENT_ROOT $document_root;
        fastcgi_param SERVER_PROTOCOL $server_protocol;
        fastcgi_param GATEWAY_INTERFACE CGI/1.1;
        fastcgi_param SERVER_SOFTWARE nginx;
        fastcgi_param REMOTE_ADDR $remote_addr;
        fastcgi_param REMOTE_PORT $remote_port;
        fastcgi_param SERVER_ADDR $server_addr;
        fastcgi_param SERVER_PORT $server_port;
        fastcgi_param SERVER_NAME $server_name;
        fastcgi_param REDIRECT_STATUS 200;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param HTTPS $http_x_forwarded_proto;
        fastcgi_param HTTP_X_FORWARDED_PROTO $http_x_forwarded_proto;
        fastcgi_param HTTP_X_FORWARDED_FOR $http_x_forwarded_for;
    }

    # Security
    location ~ /\. {
        deny all;
    }
}
EOF

echo "Testing nginx configuration..."
# Test nginx configuration
nginx -t

if [ $? -eq 0 ]; then
    echo "Nginx configuration test passed. Reloading..."
    systemctl reload nginx
    echo "=== Clean URLs enabled successfully! ==="
else
    echo "ERROR: Nginx configuration test failed."
fi

echo "=== Clean URL Configuration Complete ==="