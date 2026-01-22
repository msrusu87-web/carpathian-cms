# 🚀 Brevo Integration Setup Guide

## Overview
This guide will help you integrate Brevo (formerly Sendinblue) with your Carpathian CMS for real-time email campaign statistics and improved deliverability.

## ✨ New Features

### 1. **Real-Time Statistics via Webhooks**
- Opens, clicks, bounces tracked automatically
- No manual syncing required
- Stats update instantly in admin panel

### 2. **Email List Cleaning Tools**
- Bulk mark contacts as bounced/invalid
- Delete bounced contacts in one click
- Status filters to identify bad emails

### 3. **Campaign Analytics Dashboard**
- Detailed performance metrics
- Industry benchmark comparisons
- Actionable recommendations

### 4. **Brevo API Integration** (Optional)
- Direct API email sending
- Better deliverability tracking
- Enhanced error handling

---

## 📋 Prerequisites

1. **Brevo Account** - [Sign up free](https://www.brevo.com/pricing/)
2. **API Key** - Get from Brevo dashboard: Settings → API Keys
3. **SMTP or API** - Choose sending method (SMTP recommended for simplicity)

---

## ⚙️ Configuration Steps

### Step 1: Add Brevo Credentials to `.env`

```bash
cd /home/ubuntu/carpathian-cms
nano .env
```

Add these lines:

```env
# Brevo API Configuration
BREVO_API_KEY=your_api_key_here
BREVO_WEBHOOK_SECRET=your_secret_key_here
BREVO_USE_API=false
```

**Getting your keys:**
1. Go to https://app.brevo.com/settings/keys/api
2. Create or copy your API key
3. Create a webhook secret (any random string)

### Step 2: Configure Webhooks in Brevo

1. **Go to Brevo Dashboard:**
   - Navigate to: Transactional → Settings → Webhooks
   - OR: https://app.brevo.com/settings/webhooks

2. **Create New Webhook:**
   ```
   URL: https://your-domain.com/api/marketing/webhook/brevo
   ```

3. **Select Events:**
   - ✅ Email delivered
   - ✅ Email opened
   - ✅ Email clicked
   - ✅ Hard bounce
   - ✅ Soft bounce
   - ✅ Spam complaint
   - ✅ Unsubscribed

4. **Authentication** (Optional but recommended):
   - Add custom header: `X-Brevo-Signature`
   - Use your `BREVO_WEBHOOK_SECRET`

### Step 3: Clear Cache

```bash
cd /home/ubuntu/carpathian-cms
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 🎯 Using the Admin Panel Features

### Campaign Management

#### **View Detailed Statistics**
1. Go to: **Marketing → Campaigns**
2. Click **Actions** → **Detailed Stats** on any sent campaign
3. See:
   - Complete performance metrics
   - Industry benchmarks
   - Recommendations
   - Webhook setup instructions

#### **Sync Stats from Brevo**
1. Click **Actions** → **Sync Stats**
2. Displays webhook configuration URL
3. Once webhooks are set up, stats sync automatically

### Contact List Cleaning

#### **Mark Contacts as Bounced**
1. Go to: **Marketing → Contacts**
2. Filter by status: **Active**
3. Select contacts that bounced (from Brevo report)
4. Click **Bulk Actions** → **Mark as Bounced**

#### **Delete Bounced Contacts**
1. Filter by status: **Bounced**
2. Select all or specific contacts
3. Click **Bulk Actions** → **Delete Bounced**
4. Confirm deletion

#### **Mark as Invalid**
1. Select contacts with invalid emails
2. Click **Bulk Actions** → **Mark as Invalid**
3. These won't be included in future campaigns

### Import/Export

#### **Export Contacts**
1. Select contacts to export
2. Click **Bulk Actions** → **Export CSV**
3. Download from notification link

#### **Import Contacts**
1. Click **Import CSV** button
2. Upload CSV file (columns: email, company_name, contact_name, phone, city)
3. Select list to add to (optional)
4. Click Import

---

## 🔧 Advanced Configuration

### Option A: SMTP Sending (Current Setup)

**Pros:**
- ✅ Simple setup
- ✅ Reliable
- ✅ Works with webhooks

**Current Configuration:**
```env
MAIL_MAILER=sendmail
MAIL_HOST=127.0.0.1
MAIL_PORT=25
```

**Keep this if:** Your sendmail forwards to Brevo's SMTP relay.

### Option B: Brevo API Sending (Advanced)

**Pros:**
- ✅ Better error handling
- ✅ Direct message IDs
- ✅ More control

**Setup:**
```env
BREVO_USE_API=true
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email
MAIL_PASSWORD=your_smtp_key
MAIL_ENCRYPTION=tls
```

---

## 📊 Monitoring and Maintenance

### Daily Tasks
- ✅ Check campaign stats in admin
- ✅ Review bounced emails
- ✅ Monitor delivery rates

### Weekly Tasks
- ✅ Export and backup contact list
- ✅ Clean bounced contacts
- ✅ Review campaign performance

### Monthly Tasks
- ✅ Full list cleanup
- ✅ Validate email addresses
- ✅ Update consent records

---

## 🛠️ Troubleshooting

### Webhooks Not Working

**Check:**
1. Webhook URL is correct: `/api/marketing/webhook/brevo`
2. Domain is publicly accessible
3. No firewall blocking POST requests
4. CSRF protection disabled for webhook route

**Test:**
```bash
# Check if endpoint is accessible
curl -X POST https://your-domain.com/api/marketing/webhook/brevo \
  -H "Content-Type: application/json" \
  -d '{"event":"test","email":"test@example.com"}'
```

**Expected response:**
```json
{"success":true,"message":"Webhook processed"}
```

### Stats Not Updating

**Solutions:**
1. Verify webhook is configured in Brevo
2. Check Laravel logs: `tail -f storage/logs/laravel.log`
3. Test webhook manually (see above)
4. Ensure contacts exist in database with matching emails

### "Key not found" Errors

**Causes:**
- Missing contact data
- Invalid Brevo API key
- Rate limiting

**Fix:**
1. Check `.env` has valid `BREVO_API_KEY`
2. Verify contacts have complete data
3. Check Brevo API limits: https://developers.brevo.com/docs/api-limits

---

## 📈 Performance Tips

### Improve Open Rates (Target: >30%)
- ✅ Use personalization: `{{company_name}}`
- ✅ Test different subject lines
- ✅ Send at optimal times (Tue-Thu, 10 AM - 2 PM)
- ✅ Keep subject lines under 50 characters

### Improve Click Rates (Target: >7%)
- ✅ Clear call-to-action buttons
- ✅ Use action-oriented language
- ✅ Add multiple CTAs
- ✅ Mobile-friendly design

### Reduce Bounce Rate (Target: <2%)
- ✅ Clean list monthly
- ✅ Use double opt-in
- ✅ Remove hard bounces immediately
- ✅ Validate email addresses on import

---

## 🔒 Security Best Practices

### Webhook Security
1. **Use HTTPS only** - No plain HTTP
2. **Set webhook secret** - Add to `.env`
3. **Validate signatures** - Already implemented
4. **Monitor logs** - Check for suspicious activity

### Data Protection (GDPR)
1. **Consent required** - Mark `has_consent` field
2. **Easy unsubscribe** - Included in all emails
3. **Data retention** - Delete old contacts
4. **Privacy policy** - Link in emails

---

## 📞 Support

### Resources
- [Brevo Documentation](https://developers.brevo.com/)
- [Webhook Guide](https://developers.brevo.com/docs/webhooks)
- [API Reference](https://developers.brevo.com/reference/getting-started-1)

### Common Commands
```bash
# Sync campaign stats manually
php artisan brevo:sync-stats

# Sync specific campaign
php artisan brevo:sync-stats 1

# View Laravel logs
tail -f storage/logs/laravel.log

# Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($m) { $m->to('test@example.com')->subject('Test'); });
```

---

## ✅ Setup Checklist

- [ ] Added Brevo API key to `.env`
- [ ] Added webhook secret to `.env`
- [ ] Configured webhook in Brevo dashboard
- [ ] Selected all webhook events
- [ ] Cleared Laravel cache
- [ ] Tested webhook endpoint
- [ ] Verified stats are updating
- [ ] Cleaned bounced contacts
- [ ] Exported backup of contacts
- [ ] Reviewed campaign analytics

---

## 🎉 What's New in Admin Panel

### Campaigns
- **Detailed Stats** - View comprehensive analytics with benchmarks
- **Sync Stats** - Manual sync button with webhook setup info
- **Real-time Updates** - Stats update automatically via webhooks

### Contacts
- **Mark as Bounced** - Bulk action to mark contacts as bounced
- **Mark as Invalid** - Bulk action for invalid emails
- **Delete Bounced** - One-click cleanup of bounced contacts
- **Better Filters** - Filter by status, consent, source
- **Export CSV** - Export selected contacts

### Analytics
- **Open Rate Analysis** - With industry benchmarks
- **Click Rate Analysis** - CTR performance metrics
- **Bounce Rate Monitoring** - List health indicators
- **Recommendations** - AI-powered suggestions

---

## 🚀 Next Steps

1. **Configure webhooks** - Enable real-time statistics
2. **Clean your list** - Remove bounced contacts
3. **Monitor performance** - Check campaign stats daily
4. **Optimize campaigns** - Use analytics insights
5. **Scale up** - Increase sending volume gradually

**Your campaign achieved 33.66% open rate - Keep it up! 🎉**
