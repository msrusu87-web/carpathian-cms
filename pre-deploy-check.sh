#!/bin/bash

# CMS Pre-Deployment Checker
# Run this before making any code changes or deploying

set -e

CYAN='\033[0;36m'
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${CYAN}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║       CMS PRE-DEPLOYMENT VALIDATION SCRIPT            ║${NC}"
echo -e "${CYAN}╚════════════════════════════════════════════════════════╝${NC}\n"

cd /var/www/cms.carphatian.ro

# 1. Check Git Status
echo -e "${CYAN}📦 Checking Git Status...${NC}"
if [ -d .git ]; then
    UNCOMMITTED=$(git status --porcelain | wc -l)
    if [ $UNCOMMITTED -gt 0 ]; then
        echo -e "${YELLOW}⚠ You have $UNCOMMITTED uncommitted changes${NC}"
        git status --short
    else
        echo -e "${GREEN}✓ Working directory clean${NC}"
    fi
else
    echo -e "${YELLOW}⚠ Not a git repository${NC}"
fi

# 2. Run PHP Validation Script
echo -e "\n${CYAN}🔍 Running System Validation...${NC}"
sudo -u www-data php validate.php
VALIDATION_EXIT=$?

if [ $VALIDATION_EXIT -eq 0 ]; then
    echo -e "\n${GREEN}✓ System validation passed${NC}"
else
    echo -e "\n${RED}✗ System validation failed (exit code: $VALIDATION_EXIT)${NC}"
    exit 1
fi

# 3. Check PHP Syntax
echo -e "\n${CYAN}🐘 Checking PHP Syntax...${NC}"
SYNTAX_ERRORS=0
for file in $(find app -name "*.php"); do
    php -l "$file" > /dev/null 2>&1 || {
        echo -e "${RED}✗ Syntax error in $file${NC}"
        SYNTAX_ERRORS=$((SYNTAX_ERRORS + 1))
    }
done

if [ $SYNTAX_ERRORS -eq 0 ]; then
    echo -e "${GREEN}✓ No PHP syntax errors found${NC}"
else
    echo -e "${RED}✗ Found $SYNTAX_ERRORS syntax errors${NC}"
    exit 1
fi

# 4. Check Disk Space
echo -e "\n${CYAN}💾 Checking Disk Space...${NC}"
DISK_USAGE=$(df /var/www | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 90 ]; then
    echo -e "${RED}✗ Disk usage critical: ${DISK_USAGE}%${NC}"
    exit 1
elif [ $DISK_USAGE -gt 80 ]; then
    echo -e "${YELLOW}⚠ Disk usage high: ${DISK_USAGE}%${NC}"
else
    echo -e "${GREEN}✓ Disk usage healthy: ${DISK_USAGE}%${NC}"
fi

# 5. Check Service Status
echo -e "\n${CYAN}🔧 Checking Services...${NC}"
if systemctl is-active --quiet nginx; then
    echo -e "${GREEN}✓ Nginx is running${NC}"
else
    echo -e "${RED}✗ Nginx is not running${NC}"
    exit 1
fi

if systemctl is-active --quiet php8.3-fpm; then
    echo -e "${GREEN}✓ PHP-FPM is running${NC}"
else
    echo -e "${RED}✗ PHP-FPM is not running${NC}"
    exit 1
fi

if systemctl is-active --quiet mysql; then
    echo -e "${GREEN}✓ MySQL is running${NC}"
else
    echo -e "${RED}✗ MySQL is not running${NC}"
    exit 1
fi

# 6. Test Frontend Response
echo -e "\n${CYAN}🌐 Testing Frontend...${NC}"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://cms.carphatian.ro)
if [ "$HTTP_CODE" == "200" ]; then
    echo -e "${GREEN}✓ Frontend responding (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${RED}✗ Frontend error (HTTP $HTTP_CODE)${NC}"
    exit 1
fi

# 7. Test Admin Panel
echo -e "\n${CYAN}🔐 Testing Admin Panel...${NC}"
ADMIN_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://cms.carphatian.ro/admin)
if [ "$ADMIN_CODE" == "200" ] || [ "$ADMIN_CODE" == "302" ]; then
    echo -e "${GREEN}✓ Admin panel accessible (HTTP $ADMIN_CODE)${NC}"
else
    echo -e "${RED}✗ Admin panel error (HTTP $ADMIN_CODE)${NC}"
    exit 1
fi

# 8. Check SSL Certificate
echo -e "\n${CYAN}🔒 Checking SSL Certificate...${NC}"
SSL_EXPIRY=$(echo | openssl s_client -servername cms.carphatian.ro -connect cms.carphatian.ro:443 2>/dev/null | openssl x509 -noout -enddate | cut -d= -f2)
if [ -n "$SSL_EXPIRY" ]; then
    echo -e "${GREEN}✓ SSL certificate valid until: $SSL_EXPIRY${NC}"
else
    echo -e "${YELLOW}⚠ Could not verify SSL certificate${NC}"
fi

# 9. Check Log File Size
echo -e "\n${CYAN}📋 Checking Log Files...${NC}"
LOG_FILE="storage/logs/laravel.log"
if [ -f "$LOG_FILE" ]; then
    LOG_SIZE=$(du -h "$LOG_FILE" | cut -f1)
    echo -e "${GREEN}✓ Laravel log size: $LOG_SIZE${NC}"
    
    # Check for recent errors
    RECENT_ERRORS=$(grep -c "ERROR" "$LOG_FILE" 2>/dev/null | tail -100 || echo "0")
    if [ $RECENT_ERRORS -gt 0 ]; then
        echo -e "${YELLOW}⚠ Found $RECENT_ERRORS recent errors in log${NC}"
    fi
else
    echo -e "${YELLOW}⚠ Log file not found${NC}"
fi

# Final Summary
echo -e "\n${CYAN}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║                  PRE-DEPLOYMENT SUMMARY                ║${NC}"
echo -e "${CYAN}╚════════════════════════════════════════════════════════╝${NC}"
echo -e "${GREEN}✓ All critical checks passed${NC}"
echo -e "${GREEN}✓ System is ready for deployment/changes${NC}\n"

exit 0
