#!/bin/bash
# List all blocked IPs and fail2ban status

echo "=== Currently Blocked IPs ==="
echo ""

echo "1. IPTABLES BLOCKS:"
echo "-------------------"
sudo iptables -L INPUT -n | grep DROP | awk '{print $4}' | sort

echo ""
echo "2. FAIL2BAN STATUS:"
echo "-------------------"
if command -v fail2ban-client &> /dev/null; then
    sudo fail2ban-client status laravel 2>/dev/null || echo "Laravel jail not active"
else
    echo "Fail2Ban not installed"
fi

echo ""
echo "3. RECENT BLOCKS (from log):"
echo "----------------------------"
if [ -f "/home/ubuntu/live-carphatian/blocked-ips.txt" ]; then
    tail -20 /home/ubuntu/live-carphatian/blocked-ips.txt
else
    echo "No block log found"
fi

echo ""
echo "4. TOP ATTACKING IPs (Last hour):"
echo "----------------------------------"
sudo tail -5000 /var/log/nginx/access.log | \
    grep -E " (403|404|429) " | \
    awk '{print $1}' | sort | uniq -c | sort -rn | head -10
