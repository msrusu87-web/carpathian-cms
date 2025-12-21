#!/bin/bash
# Quick CMS Status Check

echo "🔍 CARPHATIAN CMS STATUS CHECK"
echo "==============================="
echo ""

# Homepage
echo "1. Homepage:"
STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://carphatian.ro/)
if [ "$STATUS" = "200" ]; then
    echo "   ✓ Homepage loads (HTTP $STATUS)"
else
    echo "   ✗ Homepage FAILED (HTTP $STATUS)"
fi

# Login page  
echo "2. Login Page:"
STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://carphatian.ro/login)
if [ "$STATUS" = "200" ]; then
    echo "   ✓ Login page loads (HTTP $STATUS)"
else
    echo "   ✗ Login page FAILED (HTTP $STATUS)"
fi

# Admin (should redirect to login if not authenticated)
echo "3. Admin Panel:"
STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://carphatian.ro/admin)
if [ "$STATUS" = "200" ] || [ "$STATUS" = "302" ]; then
    echo "   ✓ Admin accessible (HTTP $STATUS)"
else
    echo "   ✗ Admin FAILED (HTTP $STATUS)"
fi

# Check routes
echo "4. Routes:"
ROUTES=$(cd /var/www/carphatian.ro/html && php artisan route:list 2>/dev/null | wc -l)
echo "   ℹ $ROUTES routes registered"

# Check sessions
echo "5. Sessions:"
SESSIONS=$(mysql -u carphatian -p'carphatian' carphatian_cms -se "SELECT COUNT(*) FROM sessions WHERE user_id IS NOT NULL" 2>/dev/null)
echo "   ℹ $SESSIONS active user sessions"

# Check logs
echo "6. Recent Errors:"
ERRORS=$(tail -100 /var/www/carphatian.ro/html/storage/logs/laravel.log 2>/dev/null | grep -c "ERROR")
if [ "$ERRORS" -gt 0 ]; then
    echo "   ⚠ $ERRORS errors in last 100 log lines"
    echo "   Last error:"
    tail -100 /var/www/carphatian.ro/html/storage/logs/laravel.log | grep "ERROR" | tail -1 | cut -d']' -f2- | head -c 80
    echo "..."
else
    echo "   ✓ No recent errors"
fi

echo ""
echo "==============================="
echo "To test login:"
echo "1. Open https://carphatian.ro/login"  
echo "2. Login with: msrusu87@gmail.com"
echo "3. Should redirect to /admin panel"
echo ""
