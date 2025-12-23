# 🛡️ Security Suite - Quick Reference

## Access
**URL:** https://carphatian.ro/admin/security-suite

## Quick Actions

| Button | What It Does | Tab |
|--------|-------------|-----|
| **Refresh Status** | Updates all 4 status cards | Dashboard |
| **Run Security Audit** | Checks permissions, logins, bans | Audit |
| **Run Penetration Test** | Tests SQL injection, XSS, etc. | Pentest |
| **View Blocked IPs** | Lists all banned IPs | Blocked |
| **Generate Daily Report** | Creates security statistics | Report |

## Status Cards

| Card | Green ✅ | Yellow ⚠️ | Red ❌ |
|------|---------|-----------|--------|
| **Nginx** | Running | Warnings | Error |
| **Fail2Ban** | 2 Jails | 1 Jail | 0 Jails |
| **Permissions** | .env: 600 | .env: 644 | .env: 666 |
| **Headers** | 3/3 Active | 2/3 Active | 0-1/3 Active |

## What to Check Daily

1. ✅ **Status Cards** - All should be green
2. 🔍 **Security Audit** - No suspicious activity
3. 🚫 **Blocked IPs** - Review attack patterns

## What to Check Weekly

1. 🐛 **Penetration Test** - All tests should PASS
2. 📊 **Daily Report** - Review anomalies

## Emergency Response

### If Status Card is Red:
1. Click the button to refresh
2. Check the specific service: `sudo systemctl status nginx` or `sudo systemctl status fail2ban`
3. Review logs: `sudo tail -100 /var/log/nginx/error.log`

### If Pentest Shows Failures:
1. Note which test failed
2. Review nginx configuration
3. Check Laravel middleware
4. Update security rules

### If Under Attack:
1. Go to **Blocked IPs** tab
2. Review attacking IPs
3. Check **Daily Report** for patterns
4. Manually block IPs if needed using block-ip.sh

## Scripts Location
All security scripts: `/home/ubuntu/live-carphatian/*.sh`

## Files Created
- `/var/www/carphatian.ro/html/app/Filament/Pages/SecuritySuite.php`
- `/var/www/carphatian.ro/html/resources/views/filament/pages/security-suite.blade.php`
- `/var/www/carphatian.ro/html/SECURITY_SUITE_GUIDE.md`

## Support
Full documentation: `SECURITY_SUITE_GUIDE.md`

---
**Last Updated:** December 23, 2025
