#!/bin/bash
# IP Blocker - Block malicious IPs using iptables
# Usage: ./block-ip.sh <IP> [reason]

if [ $# -eq 0 ]; then
    echo "Usage: $0 <IP_ADDRESS> [reason]"
    echo "Example: $0 1.2.3.4 'SQL injection attempt'"
    exit 1
fi

IP="$1"
REASON="${2:-Manual block}"
BLOCKLIST="/home/ubuntu/live-carphatian/blocked-ips.txt"

# Validate IP format
if ! [[ $IP =~ ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$ ]]; then
    echo "Error: Invalid IP address format"
    exit 1
fi

# Check if already blocked
if sudo iptables -L INPUT -n | grep -q "$IP"; then
    echo "IP $IP is already blocked"
    exit 0
fi

# Block the IP
echo "Blocking IP: $IP"
sudo iptables -I INPUT -s "$IP" -j DROP

# Save to blocklist
echo "$(date '+%Y-%m-%d %H:%M:%S') - $IP - $REASON" >> "$BLOCKLIST"

# Make iptables persistent
if command -v netfilter-persistent &> /dev/null; then
    sudo netfilter-persistent save
else
    echo "Warning: iptables rules not persisted (install iptables-persistent)"
fi

echo "✓ IP $IP has been blocked"
echo "  Reason: $REASON"
echo "  Logged to: $BLOCKLIST"

# Also add to fail2ban if available
if command -v fail2ban-client &> /dev/null; then
    sudo fail2ban-client set laravel banip "$IP" 2>/dev/null && \
        echo "✓ Also banned in fail2ban"
fi
