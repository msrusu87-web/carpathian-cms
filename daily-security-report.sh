#!/bin/bash
# Automated Daily Security Report
# Run via cron: 0 2 * * * /home/ubuntu/live-carphatian/daily-security-report.sh

REPORT_DIR="/home/ubuntu/security-reports"
REPORT_FILE="$REPORT_DIR/security-report-$(date +%Y%m%d).txt"
EMAIL="admin@carphatian.ro"  # Change to your email

mkdir -p "$REPORT_DIR"

{
    echo "====================================="
    echo "Daily Security Report - Carpathian.ro"
    echo "Date: $(date)"
    echo "====================================="
    echo ""
    
    echo "1. SYSTEM STATUS"
    echo "----------------"
    echo "Disk usage: $(df -h / | tail -1 | awk '{print $5}')"
    echo "Memory usage: $(free -h | grep Mem | awk '{print $3 "/" $2}')"
    echo "CPU load: $(uptime | awk -F'load average:' '{print $2}')"
    echo ""
    
    echo "2. FAIL2BAN STATUS"
    echo "------------------"
    sudo fail2ban-client status | grep "Jail list" || echo "Fail2Ban not running"
    echo ""
    
    echo "3. BANNED IPs (Last 24h)"
    echo "------------------------"
    sudo fail2ban-client status laravel 2>/dev/null | grep "Banned IP" || echo "No banned IPs"
    echo ""
    
    echo "4. TOP 20 ATTACKING IPs"
    echo "-----------------------"
    sudo tail -10000 /var/log/nginx/access.log | \
        awk '{print $1}' | sort | uniq -c | sort -rn | head -20
    echo ""
    
    echo "5. HTTP STATUS CODES"
    echo "--------------------"
    sudo tail -10000 /var/log/nginx/access.log | \
        awk '{print $9}' | sort | uniq -c | sort -rn
    echo ""
    
    echo "6. TOP REQUESTED URLS"
    echo "---------------------"
    sudo tail -10000 /var/log/nginx/access.log | \
        awk '{print $7}' | sort | uniq -c | sort -rn | head -20
    echo ""
    
    echo "7. SUSPICIOUS REQUESTS"
    echo "----------------------"
    sudo tail -10000 /var/log/nginx/access.log | \
        grep -iE "(union|select|script|exec|eval|\.\.\/|passwd)" | tail -20
    echo ""
    
    echo "8. FAILED LOGIN ATTEMPTS"
    echo "------------------------"
    sudo grep -i "failed" /var/www/carphatian.ro/html/storage/logs/laravel.log 2>/dev/null | \
        tail -20 || echo "No failed login attempts"
    echo ""
    
    echo "9. NEW FILES CREATED (Last 24h)"
    echo "-------------------------------"
    find /var/www/carphatian.ro/html -type f -mtime -1 -name "*.php" 2>/dev/null || \
        echo "No new PHP files"
    echo ""
    
    echo "10. WORLD-WRITABLE FILES"
    echo "------------------------"
    find /var/www/carphatian.ro/html -type f -perm -002 2>/dev/null | \
        head -20 || echo "No world-writable files"
    echo ""
    
    echo "====================================="
    echo "Report generated at: $(date)"
    echo "====================================="
    
} > "$REPORT_FILE"

# Display to console
cat "$REPORT_FILE"

# Optional: Send email (requires mail command)
# mail -s "Daily Security Report - Carpathian.ro" "$EMAIL" < "$REPORT_FILE"

# Cleanup old reports (keep last 30 days)
find "$REPORT_DIR" -name "security-report-*.txt" -mtime +30 -delete

echo ""
echo "Report saved to: $REPORT_FILE"
