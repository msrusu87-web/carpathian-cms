# 🔍 LOGIN DEBUGGING GUIDE - Complete Diagnostics

## Issue: 419 CSRF Token Mismatch on Login

### Root Cause Found:
**SESSION_DOMAIN was empty in .env file!**

## ✅ Fix Applied:
```bash
SESSION_DOMAIN=.carphatian.ro
```

## 🧪 Diagnostic Tools Available:

### 1. **Login Page with Live Debugging**
URL: https://carphatian.ro/login

Features:
- Real-time CSRF token monitoring
- Cookie presence detection
- Form submission debugging
- Response header inspection
- Detailed error reporting

What to check:
- ✓ CSRF Token should be visible (not "NOT FOUND")
- ✓ Session Cookie should be visible (not "NOT FOUND")
- ✓ XSRF Cookie should be visible
- Watch the debug panel during login attempt

### 2. **PHP Session Test (Bypasses Laravel)**
URL: https://carphatian.ro/session-test.php

Tests:
- Raw PHP session functionality
- Cookie setting/reading
- Session persistence across requests
- Server configuration

Actions:
- Click "Refresh Page" - counter should increment
- Check if cookies are being set
- Verify session ID persists

### 3. **Laravel Session Debug API**
URL: https://carphatian.ro/debug/session

Returns JSON with:
- Session ID
- CSRF token
- Session driver config
- Cookie domain settings
- All session data
- Cookie presence

### 4. **Server-Side Logs**
```bash
# Laravel logs
tail -f /var/www/carphatian.ro/html/storage/logs/laravel.log

# Check for session errors
grep -i "session\|csrf\|419" /var/www/carphatian.ro/html/storage/logs/laravel.log

# Check database sessions
mysql -u carphatian -p'carphatian' carphatian_cms -e "SELECT * FROM sessions ORDER BY last_activity DESC LIMIT 5;"
```

## 🔧 Configuration Changes Made:

### .env Changes:
```env
SESSION_DOMAIN=.carphatian.ro  # ← FIXED (was empty)
SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_SECURE_COOKIE=true
```

### Why SESSION_DOMAIN Matters:
- Empty domain = cookies won't be set properly
- `.carphatian.ro` = works for www.carphatian.ro and carphatian.ro
- Must match the domain you're accessing

## 📋 Testing Checklist:

### Step 1: Clear Browser Data
```
1. Open DevTools (F12)
2. Application → Storage → Clear site data
3. Close and reopen browser
```

### Step 2: Test Session Persistence
```
1. Visit https://carphatian.ro/session-test.php
2. Refresh page 3 times
3. Counter should increment: 1 → 2 → 3
4. If counter resets to 1 each time = session problem
```

### Step 3: Test Laravel Session
```
1. Visit https://carphatian.ro/debug/session
2. Check JSON response:
   - "session_id": should be present
   - "csrf_token": should be present
   - "session_domain": should show ".carphatian.ro"
   - "session_secure": should be true
3. Refresh page - session_id should stay the same
```

### Step 4: Test Login with Debugging
```
1. Visit https://carphatian.ro/login
2. Check debug panel (blue box at top):
   ✓ CSRF Token: should show token (first 20 chars)
   ✓ Session Cookie: should show "✓" not "❌"
   ✓ XSRF Cookie: should show "✓" not "❌"
3. Enter credentials and submit
4. Watch debug panel for errors
5. Check browser console (F12) for detailed logs
```

## 🚨 Common Issues & Solutions:

### Issue: "SESSION COOKIE NOT FOUND"
**Cause:** Browser not accepting cookies or SESSION_DOMAIN mismatch
**Fix:** 
- Check SESSION_DOMAIN=.carphatian.ro in .env
- Clear browser cookies
- Ensure accessing via https://

### Issue: "419 CSRF Token Mismatch"
**Causes:**
1. Session not persisting (cookie problem)
2. CSRF token generated but not saved to session
3. Session ID changes between page load and form submit

**Fixes:**
- Verify session cookie is being set (use /session-test.php)
- Check storage/framework/sessions/ is writable
- Verify database sessions table exists and is accessible

### Issue: "XSRF Cookie NOT FOUND"
**Cause:** EncryptCookies middleware not running
**Fix:** Check app/Http/Kernel.php middleware stack

## 🔍 Deep Debugging Steps:

### 1. Check Middleware Stack:
```bash
cd /var/www/carphatian.ro/html
php artisan route:list | grep login
```

### 2. Verify Session Driver:
```bash
# Check if sessions table has data
mysql -u carphatian -p'carphatian' carphatian_cms -e "SELECT COUNT(*) FROM sessions;"

# Check recent sessions
mysql -u carphatian -p'carphatian' carphatian_cms -e "SELECT id, user_id, ip_address, user_agent, FROM_UNIXTIME(last_activity) as last_seen FROM sessions ORDER BY last_activity DESC LIMIT 10;"
```

### 3. Test CSRF Middleware:
```bash
# Check if VerifyCsrfToken is active
grep -r "VerifyCsrfToken" app/Http/
```

### 4. Check File Permissions:
```bash
ls -la storage/framework/sessions/
ls -la storage/logs/
```

## 📝 What to Report:

When asking for help, include:

1. **Debug Panel Output** from https://carphatian.ro/login
2. **Session Test Results** from https://carphatian.ro/session-test.php
3. **Laravel Session JSON** from https://carphatian.ro/debug/session
4. **Browser Console Logs** (F12 → Console)
5. **Network Request Details** (F12 → Network → Click failed POST)

## ✅ Expected Working State:

### Login Page Debug Panel Should Show:
```
✓ CSRF Token: abc123def456...
✓ Session Cookie: ✓ xyz789uvw012...
✓ XSRF Cookie: ✓ mno345pqr678...
✓ Cookies Enabled: true
✓ Secure Context: true
✓ Session cookie found
✓ CSRF token found
```

### On Form Submit:
```
→ Form submission started...
→ CSRF Input Value: abc123def456...
→ Email: msrusu87@gmail.com
→ Password: ***PROVIDED***
→ Response Status: 302 Found (redirect)
✓ Login appears successful!
```

## 🎯 Quick Fix Summary:

1. **SESSION_DOMAIN was empty** → Set to `.carphatian.ro`
2. **Cleared all caches** → `php artisan config:clear`
3. **Added comprehensive debugging** → Login page + diagnostic tools
4. **Test immediately** → Visit /login and check debug panel

## 📞 Support Commands:

```bash
# Clear everything and restart
cd /var/www/carphatian.ro/html
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm

# Check if sessions are being created
mysql -u carphatian -p'carphatian' carphatian_cms -e "SELECT COUNT(*) as total, MAX(FROM_UNIXTIME(last_activity)) as latest FROM sessions;"
```

---

**Last Updated:** 2025-12-21
**Status:** SESSION_DOMAIN fixed, debugging tools deployed
**Next Step:** Test login at https://carphatian.ro/login with debug panel active
