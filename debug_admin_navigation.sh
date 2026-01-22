#!/bin/bash

echo "=== Filament Admin Panel Navigation Debug Script ==="
echo "Date: $(date)"
echo

cd /home/ubuntu/carpathian-cms

# 1. Check if resources exist
echo "1. Checking Resource Files..."
if [ -f "app/Filament/Resources/TestMarketingResource.php" ]; then
    echo "✅ TestMarketingResource exists"
else
    echo "❌ TestMarketingResource missing"
fi

if [ -f "plugins/marketing/src/Filament/Resources/MarketingContactResource.php" ]; then
    echo "✅ MarketingContactResource exists"
else
    echo "❌ MarketingContactResource missing"
fi
echo

# 2. Test PHP syntax
echo "2. Checking PHP Syntax..."
php -l app/Filament/Resources/TestMarketingResource.php
echo

# 3. Check routes
echo "3. Checking Routes..."
echo "Marketing routes:"
php artisan route:list | grep -E "(test-marketing|marketing-contact)" | head -3
echo

# 4. Test database connection and model
echo "4. Testing Database & Models..."
php artisan tinker --execute="
try {
    echo 'Database connection: ';
    \$count = \Plugins\Marketing\Models\MarketingContact::count();
    echo 'OK - ' . \$count . ' contacts' . PHP_EOL;
} catch (Exception \$e) {
    echo 'ERROR: ' . \$e->getMessage() . PHP_EOL;
}
"

# 5. Test if admin panel loads
echo "5. Testing Admin Panel HTTP Response..."
ADMIN_RESPONSE=$(curl -s -o /tmp/admin_response.html -w "%{http_code}" https://carphatian.ro/admin)
echo "Admin panel response code: $ADMIN_RESPONSE"

if [ "$ADMIN_RESPONSE" = "200" ]; then
    echo "✅ Admin panel loads successfully"
    
    # Check if navigation contains our resources
    echo "6. Checking Navigation HTML..."
    if grep -q "test-marketing\|TEST MARKETING" /tmp/admin_response.html; then
        echo "✅ TEST MARKETING found in navigation"
    else
        echo "❌ TEST MARKETING NOT found in navigation"
    fi
    
    if grep -q "marketing-contact\|Marketing Contact" /tmp/admin_response.html; then
        echo "✅ Marketing Contact found in navigation"
    else
        echo "❌ Marketing Contact NOT found in navigation"
    fi
    
    # Check for JavaScript errors in HTML
    echo "7. Checking for JavaScript Issues..."
    if grep -q "error\|Error\|ERROR" /tmp/admin_response.html; then
        echo "⚠️  Possible errors found in HTML"
        grep -i "error" /tmp/admin_response.html | head -3
    else
        echo "✅ No obvious errors in HTML"
    fi
else
    echo "❌ Admin panel failed to load (HTTP $ADMIN_RESPONSE)"
fi
echo

# 6. Test specific resource URLs
echo "8. Testing Resource URLs..."
TEST_MARKETING_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" https://carphatian.ro/admin/test-marketings)
echo "test-marketings URL: $TEST_MARKETING_RESPONSE"

MARKETING_CONTACTS_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" https://carphatian.ro/admin/marketing-contacts)
echo "marketing-contacts URL: $MARKETING_CONTACTS_RESPONSE"

# 7. Check Laravel logs for errors
echo
echo "9. Recent Laravel Logs..."
if [ -f "storage/logs/laravel.log" ]; then
    echo "Last 10 error lines:"
    tail -n 100 storage/logs/laravel.log | grep -i "error\|exception\|fatal" | tail -10
else
    echo "No Laravel log file found"
fi

# 8. Test Filament resource registration
echo
echo "10. Testing Resource Registration..."
php artisan tinker --execute="
try {
    \$panel = app(\Filament\Facades\Filament::getDefaultPanel());
    \$resources = \$panel->getResources();
    echo 'Total resources registered: ' . count(\$resources) . PHP_EOL;
    
    foreach (\$resources as \$resource) {
        if (str_contains(\$resource, 'Marketing') || str_contains(\$resource, 'Test')) {
            echo 'Found: ' . \$resource . PHP_EOL;
        }
    }
} catch (Exception \$e) {
    echo 'Error getting resources: ' . \$e->getMessage() . PHP_EOL;
}
"

echo
echo "=== Debug Complete ==="
echo

# 9. Provide next steps
echo "11. Next Steps:"
echo "- If TEST MARKETING is not found, there's a resource registration issue"
echo "- If HTTP codes are 404, routes aren't working"  
echo "- If HTTP codes are 500, there are PHP errors"
echo "- Check browser console (F12) for JavaScript errors"
echo "- Visit: https://carphatian.ro/admin/test-marketings directly"

# Cleanup
rm -f /tmp/admin_response.html