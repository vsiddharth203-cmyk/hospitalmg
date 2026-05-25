#!/bin/bash
# ============================================================
# Hospital Management System — Quick Setup Script
# ============================================================
# Usage: chmod +x setup.sh && ./setup.sh
# ============================================================

set -e
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}"
echo "  ╔══════════════════════════════════════╗"
echo "  ║   🏥 Hospital Management System      ║"
echo "  ║      Laravel Setup Script            ║"
echo "  ╚══════════════════════════════════════╝"
echo -e "${NC}"

# 1. Check PHP
if ! command -v php &> /dev/null; then
    echo -e "${YELLOW}❌ PHP not found. Install PHP 8.1+ first.${NC}"
    exit 1
fi
echo -e "${GREEN}✅ PHP: $(php -v | head -1)${NC}"

# 2. Check Composer
if ! command -v composer &> /dev/null; then
    echo -e "${YELLOW}❌ Composer not found. Install from https://getcomposer.org${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Composer found${NC}"

# 3. Install dependencies
echo -e "\n${BLUE}📦 Installing PHP dependencies...${NC}"
composer install --no-interaction --prefer-dist

# 4. Copy .env
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}✅ .env file created${NC}"
fi

# 5. Generate app key
php artisan key:generate
echo -e "${GREEN}✅ Application key generated${NC}"

# 6. Prompt for DB credentials
echo -e "\n${BLUE}🗄️  Database Configuration${NC}"
read -p "   Database name [hospital_db]: " DB_NAME
DB_NAME=${DB_NAME:-hospital_db}
read -p "   Database host [127.0.0.1]: " DB_HOST
DB_HOST=${DB_HOST:-127.0.0.1}
read -p "   Database user [root]: " DB_USER
DB_USER=${DB_USER:-root}
read -sp "   Database password: " DB_PASS
echo ""

# Update .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" .env
sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" .env

echo -e "${GREEN}✅ Database configuration updated${NC}"

# 7. Run migrations
echo -e "\n${BLUE}🏗️  Running database migrations...${NC}"
php artisan migrate --force

# 8. Seed database
echo -e "\n${BLUE}🌱 Seeding sample data...${NC}"
php artisan db:seed --force

# 9. Create storage link
php artisan storage:link 2>/dev/null || true

echo -e "\n${GREEN}"
echo "  ╔══════════════════════════════════════╗"
echo "  ║   ✅ Setup Complete!                  ║"
echo "  ╚══════════════════════════════════════╝"
echo -e "${NC}"
echo -e "  🌐 Start server: ${BLUE}php artisan serve${NC}"
echo -e "  🔗 URL: ${BLUE}http://localhost:8000${NC}"
echo -e "  👤 Login: ${BLUE}admin@hospital.com${NC}"
echo -e "  🔑 Password: ${BLUE}password${NC}"
echo ""
