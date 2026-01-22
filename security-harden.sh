#!/bin/bash
# Carpathian CMS Security Hardening Script
# Purpose: Implement comprehensive security measures
# Date: December 23, 2025

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

SITE_ROOT="/var/www/carphatian.ro/html"
NGINX_CONF="/etc/nginx/sites-available/carphatian.ro"
BACKUP_DIR="/home/ubuntu/backups/security"

echo -e "${BLUE}=== Carpathian CMS Security Hardening ===${NC}\n"

# Function to print status
print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

# Create backup directory
mkdir -p "$BACKUP_DIR"

# 1. SECURITY HEADERS - Add to nginx config
echo -e "\n${BLUE}1. Adding Security Headers${NC}"

cat > /tmp/security_headers.conf << 'EOF'
    # Security Headers - Added by Security Hardening Script
    
    # Prevent clickjacking attacks
    add_header X-Frame-Options "SAMEORIGIN" always;
    
    # Prevent MIME type sniffing
    add_header X-Content-Type-Options "nosniff" always;
    
    # Enable XSS protection
    add_header X-XSS-Protection "1; mode=block" always;
    
    # Force HTTPS
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    
    # Referrer policy
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    
    # Permissions policy (disable unnecessary features)
    add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
    
    # Content Security Policy
    add_header Content-Security-Policy "default-src 'self' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https:;" always;
    
    # Hide nginx version
    server_tokens off;
EOF

print_status "Security headers configuration created"

# 2. RATE LIMITING - Protect against DDoS and brute force
echo -e "\n${BLUE}2. Configuring Rate Limiting${NC}"

cat > /tmp/rate_limit.conf << 'EOF'
# Rate Limiting Zones
limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
limit_req_zone $binary_remote_addr zone=api:10m rate=30r/m;
limit_req_zone $binary_remote_addr zone=general:10m rate=100r/m;
limit_conn_zone $binary_remote_addr zone=conn_limit:10m;

# Limit request body size to prevent large payload attacks
client_max_body_size 10M;
client_body_buffer_size 128k;

# Timeouts
client_body_timeout 12;
client_header_timeout 12;
send_timeout 10;
EOF

print_status "Rate limiting configuration created"

# 3. BOT BLOCKING - Block bad bots and scrapers
echo -e "\n${BLUE}3. Creating Bot Blocking Rules${NC}"

cat > /tmp/block_bots.conf << 'EOF'
# Block Bad Bots and Scrapers
map $http_user_agent $bad_bot {
    default 0;
    ~*SemrushBot 1;
    ~*AhrefsBot 1;
    ~*MJ12bot 1;
    ~*DotBot 1;
    ~*Baiduspider 1;
    ~*YandexBot 0; # Allow Yandex for international SEO
    ~*SeznamBot 1;
    ~*Sogou 1;
    ~*Exabot 1;
    ~*Cliqzbot 1;
    ~*curl 1;
    ~*wget 1;
    ~*python-requests 1;
    ~*scrapy 1;
    ~*selenium 1;
    ~*phantomjs 1;
    ~*spam 1;
    ~*bot[^a-z] 1;
    ~*scraper 1;
    ~*crawler 1;
    ~*spider 1;
}

# Block requests with no user agent
map $http_user_agent $no_ua {
    default 0;
    "" 1;
}

# Block suspicious referrers (spam sites)
map $http_referer $bad_referer {
    default 0;
    ~*blogspot\.com 1;
    ~*asktcuwkk 1;
    ~*askvymphaf 1;
    ~*braiinly 1;
    ~*brainlyhero 1;
    ~*callonbrainly 1;
    ~*dunialektur 1;
    ~*nimblebrief 1;
    ~*uczycsiie 1;
    ~*semalt\.com 1;
    ~*kambasoft\.com 1;
    ~*savetubevideo\.com 1;
    ~*buttons-for-website\.com 1;
    ~*seo-platform\.com 1;
}
EOF

print_status "Bot blocking rules created"

# 4. LARAVEL SECURITY MIDDLEWARE
echo -e "\n${BLUE}4. Creating Laravel Security Middleware${NC}"

cat > "$SITE_ROOT/app/Http/Middleware/SecurityHeaders.php" << 'EOPHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Additional security headers (belt and suspenders with nginx)
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Remove identifying headers
        $response->headers->remove('X-Powered-By');
        
        return $response;
    }
}
EOPHP

sudo chown www-data:www-data "$SITE_ROOT/app/Http/Middleware/SecurityHeaders.php"
print_status "Laravel security middleware created"

# 5. FAIL2BAN CONFIGURATION
echo -e "\n${BLUE}5. Configuring Fail2Ban${NC}"

if ! command -v fail2ban-client &> /dev/null; then
    print_warning "Fail2Ban not installed. Installing..."
    sudo apt-get update -qq && sudo apt-get install -y fail2ban -qq
fi

# Create custom fail2ban filter for Laravel
sudo tee /etc/fail2ban/filter.d/laravel.conf > /dev/null << 'EOF'
[Definition]
failregex = .*\[<HOST>\].*POST /login.* 401
            .*\[<HOST>\].*POST /admin/login.* 401
            .*\[<HOST>\].*Failed login attempt
ignoreregex =
EOF

# Create fail2ban jail for Laravel
sudo tee /etc/fail2ban/jail.d/laravel.conf > /dev/null << 'EOF'
[laravel]
enabled = true
port = http,https
filter = laravel
logpath = /var/www/carphatian.ro/html/storage/logs/laravel.log
maxretry = 5
bantime = 3600
findtime = 600
EOF

# Restart fail2ban
sudo systemctl restart fail2ban 2>/dev/null || print_warning "Fail2ban restart skipped (manual restart may be needed)"
print_status "Fail2Ban configured for Laravel"

# 6. FILE PERMISSIONS HARDENING
echo -e "\n${BLUE}6. Hardening File Permissions${NC}"

cd "$SITE_ROOT"

# Set proper ownership
sudo chown -R www-data:www-data .
print_status "Set www-data ownership"

# Directories should be 755
find . -type d -exec chmod 755 {} \;
print_status "Directory permissions set to 755"

# Files should be 644
find . -type f -exec chmod 644 {} \;
print_status "File permissions set to 644"

# Storage and cache need write permissions
chmod -R 775 storage bootstrap/cache
print_status "Storage writable directories configured"

# Protect sensitive files
chmod 600 .env 2>/dev/null || true
chmod 644 composer.json composer.lock
chmod 755 artisan
print_status "Sensitive files protected"

# 7. DISABLE DIRECTORY LISTING
echo -e "\n${BLUE}7. Creating .htaccess Protection${NC}"

cat > "$SITE_ROOT/public/.htaccess" << 'EOF'
# Disable directory browsing
Options -Indexes

# Disable PHP execution in uploads
<FilesMatch "\.php$">
    Order Allow,Deny
    Deny from all
</FilesMatch>
EOF

print_status "Directory listing disabled"

# 8. CREATE SECURITY AUDIT SCRIPT
echo -e "\n${BLUE}8. Creating Security Audit Script${NC}"

cat > /home/ubuntu/live-carphatian/security-audit.sh << 'EOSCRIPT'
#!/bin/bash
# Security Audit Script for Carpathian CMS

echo "=== Carpathian CMS Security Audit ==="
echo "Date: $(date)"
echo ""

# Check for suspicious files
echo "1. Checking for suspicious files..."
find /var/www/carphatian.ro/html -name "*.php" -type f -mtime -1 | while read file; do
    echo "   Modified in last 24h: $file"
done

# Check for shell scripts in web directory
echo ""
echo "2. Checking for unauthorized shell scripts..."
find /var/www/carphatian.ro/html/public -name "*.sh" -o -name "*.bash"

# Check file permissions
echo ""
echo "3. Checking for world-writable files..."
find /var/www/carphatian.ro/html -type f -perm -002 2>/dev/null

# Check for large files (possible uploads)
echo ""
echo "4. Checking for large files (>10MB)..."
find /var/www/carphatian.ro/html/public -type f -size +10M

# Check fail2ban status
echo ""
echo "5. Fail2Ban status:"
sudo fail2ban-client status 2>/dev/null || echo "   Fail2Ban not running"

# Check nginx error log for attacks
echo ""
echo "6. Recent nginx errors (last 50):"
sudo tail -50 /var/log/nginx/error.log | grep -E "attack|exploit|injection" || echo "   No attacks detected"

# Check for malicious IP connections
echo ""
echo "7. Top IP addresses in last hour:"
sudo tail -1000 /var/log/nginx/access.log | awk '{print $1}' | sort | uniq -c | sort -rn | head -10

echo ""
echo "Audit complete!"
EOSCRIPT

chmod +x /home/ubuntu/live-carphatian/security-audit.sh
print_status "Security audit script created"

# 9. CREATE PENETRATION TESTING SCRIPT
echo -e "\n${BLUE}9. Creating Penetration Testing Script${NC}"

cat > /home/ubuntu/live-carphatian/pentest.sh << 'EOPENTEST'
#!/bin/bash
# Penetration Testing Script for Carpathian CMS
# Tests common vulnerabilities

TARGET="https://carphatian.ro"
REPORT="/home/ubuntu/live-carphatian/pentest-report-$(date +%Y%m%d-%H%M%S).txt"

echo "=== Carpathian CMS Penetration Test ===" | tee "$REPORT"
echo "Target: $TARGET" | tee -a "$REPORT"
echo "Date: $(date)" | tee -a "$REPORT"
echo "" | tee -a "$REPORT"

# Test 1: SQL Injection
echo "1. Testing SQL Injection..." | tee -a "$REPORT"
curl -s "$TARGET/search?q=test' OR '1'='1" | grep -q "error" && \
    echo "   [!] Possible SQL injection vulnerability" | tee -a "$REPORT" || \
    echo "   [✓] SQL injection test passed" | tee -a "$REPORT"

# Test 2: XSS
echo "" | tee -a "$REPORT"
echo "2. Testing XSS..." | tee -a "$REPORT"
response=$(curl -s "$TARGET/search?q=<script>alert('XSS')</script>")
if echo "$response" | grep -q "<script>alert('XSS')</script>"; then
    echo "   [!] Possible XSS vulnerability" | tee -a "$REPORT"
else
    echo "   [✓] XSS test passed" | tee -a "$REPORT"
fi

# Test 3: Security Headers
echo "" | tee -a "$REPORT"
echo "3. Checking Security Headers..." | tee -a "$REPORT"
headers=$(curl -sI "$TARGET")
echo "$headers" | grep -q "X-Frame-Options" && \
    echo "   [✓] X-Frame-Options present" | tee -a "$REPORT" || \
    echo "   [!] X-Frame-Options missing" | tee -a "$REPORT"
echo "$headers" | grep -q "X-Content-Type-Options" && \
    echo "   [✓] X-Content-Type-Options present" | tee -a "$REPORT" || \
    echo "   [!] X-Content-Type-Options missing" | tee -a "$REPORT"
echo "$headers" | grep -q "Strict-Transport-Security" && \
    echo "   [✓] HSTS present" | tee -a "$REPORT" || \
    echo "   [!] HSTS missing" | tee -a "$REPORT"

# Test 4: Directory Traversal
echo "" | tee -a "$REPORT"
echo "4. Testing Directory Traversal..." | tee -a "$REPORT"
status=$(curl -s -o /dev/null -w "%{http_code}" "$TARGET/../../../etc/passwd")
if [ "$status" = "200" ]; then
    echo "   [!] Possible directory traversal vulnerability" | tee -a "$REPORT"
else
    echo "   [✓] Directory traversal test passed" | tee -a "$REPORT"
fi

# Test 5: Sensitive File Exposure
echo "" | tee -a "$REPORT"
echo "5. Testing for exposed sensitive files..." | tee -a "$REPORT"
for file in ".env" ".git/config" "composer.json" "phpinfo.php" "info.php"; do
    status=$(curl -s -o /dev/null -w "%{http_code}" "$TARGET/$file")
    if [ "$status" = "200" ]; then
        echo "   [!] $file is publicly accessible" | tee -a "$REPORT"
    else
        echo "   [✓] $file is protected" | tee -a "$REPORT"
    fi
done

# Test 6: SSL/TLS Configuration
echo "" | tee -a "$REPORT"
echo "6. Testing SSL/TLS..." | tee -a "$REPORT"
if command -v testssl.sh &> /dev/null; then
    testssl.sh --quiet "$TARGET" >> "$REPORT" 2>&1
else
    echo "   [!] testssl.sh not installed (recommended: git clone https://github.com/drwetter/testssl.sh)" | tee -a "$REPORT"
fi

# Test 7: Rate Limiting
echo "" | tee -a "$REPORT"
echo "7. Testing Rate Limiting..." | tee -a "$REPORT"
echo "   Sending 50 rapid requests..." | tee -a "$REPORT"
blocked=0
for i in {1..50}; do
    status=$(curl -s -o /dev/null -w "%{http_code}" "$TARGET/")
    [ "$status" = "429" ] && ((blocked++))
done
if [ $blocked -gt 0 ]; then
    echo "   [✓] Rate limiting active ($blocked requests blocked)" | tee -a "$REPORT"
else
    echo "   [!] No rate limiting detected" | tee -a "$REPORT"
fi

# Test 8: Bot Detection
echo "" | tee -a "$REPORT"
echo "8. Testing Bot Detection..." | tee -a "$REPORT"
status=$(curl -s -o /dev/null -w "%{http_code}" -A "BadBot" "$TARGET/")
if [ "$status" = "403" ] || [ "$status" = "429" ]; then
    echo "   [✓] Bad bots are blocked" | tee -a "$REPORT"
else
    echo "   [!] Bad bots not blocked" | tee -a "$REPORT"
fi

echo "" | tee -a "$REPORT"
echo "Penetration test complete!" | tee -a "$REPORT"
echo "Report saved to: $REPORT" | tee -a "$REPORT"
EOPENTEST

chmod +x /home/ubuntu/live-carphatian/pentest.sh
print_status "Penetration testing script created"

# 10. DISPLAY INSTRUCTIONS
echo -e "\n${BLUE}=== Security Hardening Complete ===${NC}\n"

echo -e "${YELLOW}IMPORTANT: Manual steps required:${NC}\n"

echo "1. Update Nginx Configuration:"
echo "   sudo nano /etc/nginx/sites-available/carphatian.ro"
echo "   Add these includes in the server block:"
echo "   - include /tmp/security_headers.conf;"
echo "   - include /tmp/rate_limit.conf;"
echo "   - include /tmp/block_bots.conf;"
echo ""
echo "   Then add in location / block:"
echo "   if (\$bad_bot) { return 403; }"
echo "   if (\$no_ua) { return 403; }"
echo "   if (\$bad_referer) { return 403; }"
echo "   limit_req zone=general burst=20 nodelay;"
echo "   limit_conn conn_limit 10;"
echo ""

echo "2. Test and reload nginx:"
echo "   sudo nginx -t && sudo systemctl reload nginx"
echo ""

echo "3. Register SecurityHeaders middleware in Laravel:"
echo "   Edit: app/Http/Kernel.php"
echo "   Add to \$middleware array:"
echo "   \\App\\Http\\Middleware\\SecurityHeaders::class,"
echo ""

echo "4. Run security audit:"
echo "   ./security-audit.sh"
echo ""

echo "5. Run penetration test:"
echo "   ./pentest.sh"
echo ""

echo -e "${GREEN}Security configurations created in:${NC}"
echo "  - /tmp/security_headers.conf"
echo "  - /tmp/rate_limit.conf"
echo "  - /tmp/block_bots.conf"
echo "  - $SITE_ROOT/app/Http/Middleware/SecurityHeaders.php"
echo "  - /home/ubuntu/live-carphatian/security-audit.sh"
echo "  - /home/ubuntu/live-carphatian/pentest.sh"

echo ""
echo -e "${GREEN}Fail2Ban Status:${NC}"
sudo fail2ban-client status 2>/dev/null || echo "Not running"

echo ""
print_status "Security hardening complete!"
