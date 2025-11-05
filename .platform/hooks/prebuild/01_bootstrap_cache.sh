#!/bin/bash
# This runs BEFORE composer install to ensure bootstrap/cache exists

echo "=== PRE-BUILD: Creating Laravel directories ==="

# CRITICAL: Create bootstrap/cache directory structure
mkdir -p /var/app/staging/bootstrap/cache
chown webapp:webapp /var/app/staging/bootstrap/cache
chmod 775 /var/app/staging/bootstrap/cache

echo "Bootstrap cache directory created successfully"