#!/bin/bash
# Unblock IP from iptables
# Usage: ./unblock-ip.sh <IP>

if [ $# -eq 0 ]; then
    echo "Usage: $0 <IP_ADDRESS>"
    exit 1
fi

IP="$1"

# Check if IP is blocked
if ! sudo iptables -L INPUT -n | grep -q "$IP"; then
    echo "IP $IP is not currently blocked"
    exit 0
fi

# Unblock the IP
echo "Unblocking IP: $IP"
sudo iptables -D INPUT -s "$IP" -j DROP

# Save iptables
if command -v netfilter-persistent &> /dev/null; then
    sudo netfilter-persistent save
fi

# Unban from fail2ban if available
if command -v fail2ban-client &> /dev/null; then
    sudo fail2ban-client set laravel unbanip "$IP" 2>/dev/null && \
        echo "✓ Also unbanned from fail2ban"
fi

echo "✓ IP $IP has been unblocked"
