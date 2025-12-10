#!/bin/bash
echo "═══════════════════════════════════════════════"
echo "    CMS SECURITY AUDIT - $(date)"
echo "═══════════════════════════════════════════════"
echo ""
PASSED=0; WARNINGS=0; FAILED=0
echo "🔍 Checking Security Packages..."
composer show 2>/dev/null | grep -q "laravel/sanctum" && echo "  ✓ Laravel Sanctum" && ((PASSED++)) || echo "  ✗ Sanctum missing" && ((FAILED++))
composer show 2>/dev/null | grep -q "spatie/laravel-permission" && echo "  ✓ Spatie Permission" && ((PASSED++)) || echo "  ✗ Permission missing" && ((FAILED++))
composer show 2>/dev/null | grep -q "laravel/fortify" && echo "  ✓ Laravel Fortify" && ((PASSED++)) || echo "  ⚠ Fortify missing" && ((WARNINGS++))
echo ""
echo "🔍 Checking Middleware..."
[ -f "app/Http/Middleware/SecurityHeaders.php" ] && echo "  ✓ SecurityHeaders" && ((PASSED++)) || echo "  ✗ SecurityHeaders missing" && ((FAILED++))
[ -f "app/Http/Middleware/ValidateInput.php" ] && echo "  ✓ ValidateInput" && ((PASSED++)) || echo "  ⚠ ValidateInput missing" && ((WARNINGS++))
[ -f "app/Http/Middleware/ApiRateLimiter.php" ] && echo "  ✓ ApiRateLimiter" && ((PASSED++)) || echo "  ⚠ ApiRateLimiter missing" && ((WARNINGS++))
[ -f "app/Http/Middleware/LogActivity.php" ] && echo "  ✓ LogActivity" && ((PASSED++)) || echo "  ⚠ LogActivity missing" && ((WARNINGS++))
echo ""
echo "🔍 Checking Services..."
[ -f "app/Services/FileSecurityService.php" ] && echo "  ✓ FileSecurityService" && ((PASSED++)) || echo "  ⚠ FileSecurityService missing" && ((WARNINGS++))
echo ""
echo "🔍 Checking Permissions..."
[ -w "storage" ] && echo "  ✓ storage/ writable" && ((PASSED++)) || echo "  ✗ storage/ not writable" && ((FAILED++))
[ -w "bootstrap/cache" ] && echo "  ✓ bootstrap/cache/ writable" && ((PASSED++)) || echo "  ✗ bootstrap/cache/ not writable" && ((FAILED++))
echo ""
echo "═══════════════════════════════════════════════"
echo "  ✓ Passed: $PASSED  ⚠ Warnings: $WARNINGS  ✗ Failed: $FAILED"
echo "═══════════════════════════════════════════════"
[ $FAILED -eq 0 ] && echo "✅ Security audit complete!" || echo "❌ Fix critical issues!"
