# Marketing Automation Plugin

A complete marketing automation suite for Carpathian CMS.

## Features

### 1. Web Scraping
- Scrape business information from websites
- Extract company names, emails, phone numbers
- Supports bulk URL scraping
- Rate-limited to prevent blocking

### 2. Contact Management
- Store and organize contacts
- Tag-based organization
- Import/Export CSV
- Duplicate detection
- GDPR-compliant data handling

### 3. Email Campaigns
- Create email campaigns with templates
- TinyEditor for rich email content
- Schedule campaigns
- Track opens and clicks
- Automatic unsubscribe handling

### 4. Anti-Spam Protection
- Rate limiting (50 emails/hour, 200/day default)
- Minimum delay between emails (30 seconds)
- Automatic unsubscribe link injection
- Domain blacklisting
- One email per contact per day limit
- CAN-SPAM compliance

## Installation

1. Activate the plugin from Admin > Settings > Plugins
2. Run migrations: `php artisan migrate --path=plugins/marketing/database/migrations`
3. Configure SMTP in `.env`

## Configuration

Edit `plugin.json` to adjust:
- `rate_limits.scrape_per_minute` - Web scraping rate limit
- `rate_limits.emails_per_hour` - Hourly email limit
- `rate_limits.emails_per_day` - Daily email limit
- `anti_spam.min_delay_between_emails_seconds` - Delay between sends

## Usage

### Scraping Contacts
1. Go to Marketing > Scraper
2. Enter URLs (one per line) or a search query
3. Click "Start Scraping"
4. Review extracted contacts before saving

### Creating Campaigns
1. Go to Marketing > Campaigns
2. Create a new campaign
3. Select target contact list
4. Compose email using the template editor
5. Schedule or send immediately

### Managing Contacts
1. Go to Marketing > Contacts
2. View, edit, or delete contacts
3. Create lists for segmentation
4. Export to CSV

## Legal Compliance

This plugin includes features for GDPR and CAN-SPAM compliance:
- Automatic unsubscribe links
- Contact consent tracking
- Data export/deletion capabilities
- Email bounce handling

**Important:** Ensure you have proper consent before sending marketing emails.

## API Endpoints

```
POST /api/marketing/unsubscribe - Handle unsubscribe requests
GET /api/marketing/track/{campaign}/{contact} - Track email opens
```

## Support

For issues, contact the Carpathian CMS team.
