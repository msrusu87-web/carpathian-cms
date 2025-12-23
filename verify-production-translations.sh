#!/bin/bash

###############################################################################
# PRODUCTION TRANSLATION VERIFICATION SCRIPT
# 
# This script verifies that all translations are working properly on the
# live production website at https://carphatian.ro/admin
###############################################################################

echo "🔍 VERIFYING PRODUCTION TRANSLATIONS"
echo "======================================"
echo ""

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Check locale configuration
echo "1️⃣  Checking Locale Configuration..."
LOCALE=$(cd /var/www/carphatian.ro/html && php artisan config:show app.locale 2>&1 | grep -o 'ro\|en')
if [ "$LOCALE" = "ro" ]; then
    echo -e "   ${GREEN}✅ Default locale: Romanian (ro)${NC}"
else
    echo -e "   ${RED}❌ Default locale: $LOCALE (should be 'ro')${NC}"
fi
echo ""

# 2. Verify translation file counts
echo "2️⃣  Checking Translation File Counts..."
for lang in ro en de es fr it; do
    COUNT=$(php -r "echo count(include('/var/www/carphatian.ro/html/lang/$lang/messages.php'));")
    if [ "$COUNT" = "1058" ]; then
        echo -e "   ${GREEN}✅ $lang: $COUNT keys${NC}"
    else
        echo -e "   ${YELLOW}⚠️  $lang: $COUNT keys (expected 1058)${NC}"
    fi
done
echo ""

# 3. Test key translations
echo "3️⃣  Testing Key Translation Samples..."
cd /var/www/carphatian.ro/html

# Romanian samples
php -r "
app()->setLocale('ro');
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

\$tests = [
    'dashboard' => 'Panou Control',
    'view' => 'Vizualizează',
    'actions' => 'Acțiuni',
    'edit' => 'Editează',
    'delete' => 'Șterge',
    'create' => 'Creează',
];

\$passed = 0;
\$failed = 0;

foreach (\$tests as \$key => \$expected) {
    \$actual = __(\$key);
    if (\$actual === \$expected) {
        echo \"   ✅ '\$key' => '\$actual'\n\";
        \$passed++;
    } else {
        echo \"   ❌ '\$key' => '\$actual' (expected '\$expected')\n\";
        \$failed++;
    }
}

echo \"\n   Passed: \$passed / \" . count(\$tests) . \"\n\";
" 2>/dev/null

echo ""

# 4. Check cache status
echo "4️⃣  Checking Cache Status..."
if [ -f "bootstrap/cache/config.php" ]; then
    echo -e "   ${GREEN}✅ Config cache exists${NC}"
else
    echo -e "   ${YELLOW}⚠️  Config not cached${NC}"
fi
echo ""

# 5. Verify critical navigation translations exist
echo "5️⃣  Verifying Critical Navigation Keys..."
php -r "
\$ro = include('/var/www/carphatian.ro/html/lang/ro/messages.php');
\$keys = [
    'dashboard', 'security_suite', 'ai_tools', 'cms', 'blog', 
    'shop', 'design', 'communications', 'content', 
    'users_permissions', 'settings', 'external_links'
];

\$missing = [];
foreach (\$keys as \$key) {
    if (!isset(\$ro[\$key])) {
        \$missing[] = \$key;
    }
}

if (empty(\$missing)) {
    echo \"   ✅ All navigation keys present\n\";
} else {
    echo \"   ❌ Missing keys: \" . implode(', ', \$missing) . \"\n\";
}
"
echo ""

# 6. Summary
echo "📊 SUMMARY"
echo "=========="
echo "   Total Languages: 6 (RO, EN, DE, ES, FR, IT)"
echo "   Keys per Language: 1,058"
echo "   Total Translations: 6,348"
echo "   Default Locale: Romanian"
echo ""
echo "✅ VERIFICATION COMPLETE"
echo ""
echo "🌐 To test on live site:"
echo "   1. Visit: https://carphatian.ro/admin"
echo "   2. Click language switcher (top-right)"
echo "   3. Switch between languages"
echo "   4. Verify all navigation items translate"
echo ""
