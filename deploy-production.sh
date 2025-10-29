#!/bin/bash

echo "🚀 Starting PC Shop Production Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: Please run this script from the Laravel project root directory${NC}"
    exit 1
fi

echo -e "${BLUE}📁 Current directory: $(pwd)${NC}"

# Backup current .env if exists
if [ -f ".env" ]; then
    echo -e "${YELLOW}📋 Backing up current .env file...${NC}"
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
fi

# Copy production environment
if [ -f ".env.production" ]; then
    echo -e "${BLUE}🔧 Copying production environment configuration...${NC}"
    cp .env.production .env
else
    echo -e "${RED}❌ Error: .env.production file not found${NC}"
    exit 1
fi

# Install dependencies
echo -e "${BLUE}📦 Installing PHP dependencies...${NC}"
composer install --optimize-autoloader --no-dev --no-interaction

# Install Node dependencies and build assets
echo -e "${BLUE}🎨 Building frontend assets...${NC}"
npm ci --production
npm run build

# Clear and optimize Laravel
echo -e "${BLUE}🧹 Clearing and optimizing Laravel...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generate optimized files for production
echo -e "${BLUE}⚡ Generating optimized configuration...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo -e "${BLUE}🔐 Setting proper file permissions...${NC}"
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Run database migrations (optional - uncomment if needed)
# echo -e "${BLUE}🗄️ Running database migrations...${NC}"
# php artisan migrate --force

# Create storage link
echo -e "${BLUE}🔗 Creating storage symlink...${NC}"
php artisan storage:link

# Test basic functionality
echo -e "${BLUE}🧪 Testing basic functionality...${NC}"
php artisan --version

echo -e "${GREEN}✅ Production deployment completed successfully!${NC}"
echo -e "${YELLOW}📝 Next steps:${NC}"
echo -e "   1. Update your domain DNS to point to this server"
echo -e "   2. Configure SSL certificate (Let's Encrypt recommended)"
echo -e "   3. Update database configuration in .env"
echo -e "   4. Test the website: https://www.pcshopvn.id.vn"
echo -e "   5. Set up cron jobs for scheduled tasks"

echo -e "${BLUE}🌟 Happy deploying!${NC}"