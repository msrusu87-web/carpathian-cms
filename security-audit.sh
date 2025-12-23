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
