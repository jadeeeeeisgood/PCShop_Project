#!/bin/bash

echo "=== Setting up product images folder ==="

# Create img directory if it doesn't exist
mkdir -p /var/app/current/public/img

# Set proper permissions
chown -R webapp:webapp /var/app/current/public/img
chmod -R 755 /var/app/current/public/img

echo "=== Product images folder setup complete ==="