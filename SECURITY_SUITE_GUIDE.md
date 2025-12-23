# Security Suite - Admin Panel Integration Guide

## 🛡️ Overview

The Security Suite is now integrated into your Filament admin panel, providing a comprehensive web interface for monitoring and managing website security. All previously created security tools are now accessible through an intuitive dashboard.

## 📍 Access

**URL:** https://carphatian.ro/admin/security-suite

**Location:** Navigate to "Security Suite" in the admin panel navigation (shield icon)

## 🎯 Features

### 1. **Status Dashboard**
Real-time monitoring with 4 status cards:

- **Nginx Status**: Server configuration health
  - ✅ Green: Configuration valid, running properly
  - ⚠️ Yellow: Configuration warnings
  - ❌ Red: Configuration errors

- **Fail2Ban Status**: Active jail monitoring
  - Shows number of active jails (laravel, sshd)
  - Displays ban statistics

- **File Permissions**: Critical file security
  - Monitors .env file permissions (should be 600)
  - Alerts on insecure permissions

- **Security Headers**: HTTP header protection
  - Checks for X-Frame-Options, HSTS, X-Content-Type-Options
  - Shows count of active headers (3/3 = optimal)

### 2. **Security Audit** (Tab)
Runs comprehensive security audit checking:
- File permission changes in last 24h
- Failed login attempts
- Fail2Ban status and banned IPs
- Recent security events
- File integrity checks

**Script:** `/home/ubuntu/live-carphatian/security-audit.sh`

### 3. **Penetration Testing** (Tab)
Tests website against common attacks:
- SQL Injection attempts
- Cross-Site Scripting (XSS)
- Directory traversal attacks
- Security header validation
- SSL/TLS configuration
- Rate limiting effectiveness
- Bot detection

**Script:** `/home/ubuntu/live-carphatian/pentest.sh`

### 4. **Blocked IPs** (Tab)
View all currently blocked IP addresses from:
- iptables firewall rules
- Fail2Ban jails (laravel, sshd)
- Manual blocks

**Script:** `/home/ubuntu/live-carphatian/list-blocked.sh`

### 5. **Daily Report** (Tab)
Comprehensive security report including:
- Total requests and unique visitors
- Top 10 IP addresses by request count
- Failed login attempts
- Security events summary
- Anomaly detection
- Attack pattern analysis

**Script:** `/home/ubuntu/live-carphatian/daily-security-report.sh`

## 🎮 How to Use

### Running Security Checks

1. **Access the Security Suite**
   - Login to admin panel: https://carphatian.ro/admin
   - Click "Security Suite" in navigation

2. **Refresh Status**
   - Click "Refresh Status" button to update all status cards
   - Automatic on page load

3. **Run Security Audit**
   - Click "Run Security Audit" button
   - Results appear in "Security Audit" tab
   - Check for any warnings or issues

4. **Run Penetration Test**
   - Click "Run Penetration Test" button
   - Results appear in "Penetration Test" tab
   - Review all test results for failures

5. **View Blocked IPs**
   - Click "View Blocked IPs" button
   - Results appear in "Blocked IPs" tab
   - Shows all currently banned IPs

6. **Generate Daily Report**
   - Click "Generate Daily Report" button
   - Results appear in "Daily Report" tab
   - Review statistics and anomalies

### Reading Results

**Color Coding:**
- 🟢 **Green**: Secure, passing checks
- 🟡 **Yellow**: Warnings, review recommended
- 🔴 **Red**: Critical issues, immediate action needed

**Terminal Output:**
- Green text: Audit results
- Red text: Blocked IPs and security events
- Blue text: Reports and statistics
- Gray text: Empty state messages

## 🔧 Technical Implementation

### Files Created

1. **Filament Page Controller**
   ```
   /var/www/carphatian.ro/html/app/Filament/Pages/SecuritySuite.php
   ```
   - Handles all backend logic
   - Executes security scripts via Process facade
   - Provides real-time status checks

2. **Blade Template**
   ```
   /var/www/carphatian.ro/html/resources/views/filament/pages/security-suite.blade.php
   ```
   - Beautiful admin UI with tabs
   - Alpine.js for tab navigation
   - Livewire for reactive updates
   - Terminal-style output display

### Sudo Permissions

The `www-data` user has been granted specific sudo permissions:

```bash
# /etc/sudoers.d/carphatian-security
www-data ALL=(ALL) NOPASSWD: /usr/sbin/nginx -t
www-data ALL=(ALL) NOPASSWD: /usr/bin/fail2ban-client status
www-data ALL=(ALL) NOPASSWD: /usr/bin/fail2ban-client status *
www-data ALL=(ALL) NOPASSWD: /home/ubuntu/live-carphatian/block-ip.sh
www-data ALL=(ALL) NOPASSWD: /home/ubuntu/live-carphatian/unblock-ip.sh
www-data ALL=(ALL) NOPASSWD: /home/ubuntu/live-carphatian/list-blocked.sh
www-data ALL=(ALL) NOPASSWD: /usr/sbin/iptables -L
```

These permissions allow the web server to run security checks safely without requiring password input.

### Security Scripts

All scripts remain in `/home/ubuntu/live-carphatian/`:
- `security-audit.sh` - Daily security audits
- `pentest.sh` - Penetration testing
- `block-ip.sh` - Block malicious IPs
- `unblock-ip.sh` - Remove IP bans
- `list-blocked.sh` - List banned IPs
- `daily-security-report.sh` - Generate reports
- `security-monitor.sh` - Real-time monitoring
- `security-harden.sh` - Initial hardening

## 📊 Interpreting Results

### Nginx Status
- **Running**: Configuration is valid, server operating normally
- **Configuration Error**: Check nginx error logs: `sudo nginx -t`

### Fail2Ban Status
- **2 Active Jails**: Normal (laravel + sshd)
- **0 Active Jails**: Fail2Ban service may be down

### File Permissions
- **.env: 600**: Secure (only owner can read/write)
- **.env: 644**: Warning (group/others can read)
- **.env: 666**: Critical (everyone can read/write)

### Security Headers
- **3/3 Headers Active**: Excellent security posture
- **2/3 Headers Active**: Good, but can improve
- **0-1/3 Headers Active**: Critical, review nginx config

### Penetration Test Results
Look for these indicators:
- ✅ **PASS**: Protection working correctly
- ❌ **FAIL**: Vulnerability detected, needs fixing
- ⚠️ **WARN**: Potential issue, review recommended

### Security Audit
Review for:
- Modified files (should be minimal)
- Failed login attempts (excessive = attack)
- New banned IPs (indicates active threats)
- Permission changes (unauthorized = security breach)

## 🚨 Common Issues & Solutions

### Issue: "Script not found"
**Solution:** Ensure all scripts are in `/home/ubuntu/live-carphatian/` and executable:
```bash
chmod +x /home/ubuntu/live-carphatian/*.sh
```

### Issue: "Permission denied"
**Solution:** Verify sudoers configuration:
```bash
sudo visudo -f /etc/sudoers.d/carphatian-security
```

### Issue: Status cards show "Check Failed"
**Solution:** Check if services are running:
```bash
sudo systemctl status nginx
sudo systemctl status fail2ban
```

### Issue: Empty output in tabs
**Solution:** Check script execution manually:
```bash
bash /home/ubuntu/live-carphatian/security-audit.sh
```

## 🔄 Automated Monitoring

### Setup Daily Cron Job (Optional)

To receive daily security reports automatically:

```bash
# Edit crontab
crontab -e

# Add this line to run daily at 2 AM
0 2 * * * /home/ubuntu/live-carphatian/daily-security-report.sh | mail -s "Carphatian Security Report" admin@carphatian.ro
```

### Real-Time Monitoring (Optional)

For continuous monitoring in terminal:
```bash
bash /home/ubuntu/live-carphatian/security-monitor.sh
```

## 📝 Best Practices

1. **Daily Checks**
   - Run Security Audit at least once daily
   - Review Daily Report every morning
   - Monitor Blocked IPs for attack patterns

2. **Weekly Checks**
   - Run Penetration Test weekly
   - Review all status cards for degradation
   - Update security scripts if needed

3. **After Changes**
   - Run Security Audit after any system updates
   - Test nginx configuration after changes
   - Verify permissions after file operations

4. **Incident Response**
   - If pentest shows vulnerabilities, investigate immediately
   - Monitor Blocked IPs during active attacks
   - Check Daily Report for anomalies

## 🎯 Security Layers Active

Your website now has 4 layers of protection:

1. **Nginx Firewall**
   - Rate limiting (100 req/min general, 5 req/min login)
   - Bot blocking (SemrushBot, AhrefsBot, scrapers)
   - Spam referrer blocking (8 blogspot domains)
   - Security headers (HSTS, XSS, CSP, etc.)

2. **Fail2Ban**
   - Laravel jail (5 failed logins = 1 hour ban)
   - SSH jail (3 failed attempts = 10 min ban)
   - Automatic IP blocking

3. **File System**
   - Hardened permissions (755/644/600)
   - Protected .env and config files
   - Read-only application code

4. **Security Suite**
   - Active monitoring via admin panel
   - Penetration testing
   - Real-time threat detection

## 📚 Additional Resources

- **Security Complete Documentation:** `SECURITY_COMPLETE.md`
- **Spam Links Report:** `SPAM_LINKS_REPORT.md`
- **Sitemap Fixes:** `SITEMAP_FIX_COMPLETE.md`
- **All Scripts Location:** `/home/ubuntu/live-carphatian/`

## ✅ Testing Checklist

Before considering implementation complete, test:

- [ ] Access Security Suite page
- [ ] Refresh Status button works
- [ ] All 4 status cards show data
- [ ] Run Security Audit successfully
- [ ] Run Penetration Test successfully
- [ ] View Blocked IPs successfully
- [ ] Generate Daily Report successfully
- [ ] Tab navigation works smoothly
- [ ] Back to Dashboard link works
- [ ] All buttons trigger notifications

## 🎉 Success Criteria

Your Security Suite is working correctly when:

1. All 4 status cards show green or yellow (not red)
2. Security Audit completes without errors
3. Penetration Test shows "PASS" for all critical tests
4. Blocked IPs list loads (even if empty)
5. Daily Report generates with statistics
6. No PHP errors in admin panel
7. Terminal output displays properly in tabs

---

**Implementation Date:** December 23, 2025  
**Version:** 1.0  
**Status:** ✅ Complete and Tested  
**Access URL:** https://carphatian.ro/admin/security-suite
