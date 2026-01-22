#!/bin/bash
# Real-time Security Monitor for Carpathian CMS
# Monitors logs for attacks in real-time

LOG_FILE="/var/log/nginx/access.log"
ALERT_THRESHOLD=50  # Requests per minute from same IP

echo "=== Real-time Security Monitor ==="
echo "Monitoring: $LOG_FILE"
echo "Press Ctrl+C to stop"
echo ""

# Monitor for suspicious activity
tail -f "$LOG_FILE" | while read line; do
    # Extract IP and request
    ip=$(echo "$line" | awk '{print $1}')
    request=$(echo "$line" | awk '{print $7}')
    status=$(echo "$line" | awk '{print $9}')
    ua=$(echo "$line" | grep -oP '(?<=")[^"]*Mozilla[^"]*(?=")')
    
    # Check for SQL injection attempts
    if echo "$request" | grep -qiE "(union|select|insert|update|delete|drop|';|--|\*|<script)"; then
        echo "[ALERT] SQL Injection attempt from $ip: $request"
    fi
    
    # Check for path traversal
    if echo "$request" | grep -qE "(\.\./|\.\.\\|/etc/passwd|/etc/shadow)"; then
        echo "[ALERT] Path traversal attempt from $ip: $request"
    fi
    
    # Check for 404 scanning
    if [ "$status" = "404" ]; then
        count=$(grep "$ip" "$LOG_FILE" | grep "404" | wc -l)
        if [ "$count" -gt 20 ]; then
            echo "[ALERT] Directory scanning from $ip: $count 404 errors"
        fi
    fi
    
    # Check for suspicious user agents
    if echo "$ua" | grep -qiE "(bot|crawler|scanner|nikto|nmap|sqlmap|havij|acunetix)"; then
        echo "[WARNING] Suspicious user agent from $ip: $ua"
    fi
    
    # Check for brute force on login
    if echo "$request" | grep -qE "(login|admin)" && [ "$status" = "401" -o "$status" = "403" ]; then
        echo "[WARNING] Failed login attempt from $ip"
    fi
done
