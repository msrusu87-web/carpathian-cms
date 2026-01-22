# Carphatian CMS - User Documentation

**Version:** 1.0.0  
**Last Updated:** December 12, 2025

---

## 📚 Table of Contents

1. [Getting Started](#getting-started)
2. [Admin Panel Overview](#admin-panel-overview)
3. [Managing Pages](#managing-pages)
4. [Managing Blog Posts](#managing-blog-posts)
5. [Managing Products](#managing-products)
6. [Translation Editor](#translation-editor)
7. [Language Management](#language-management)
8. [Categories & Tags](#categories--tags)
9. [Media Library](#media-library)
10. [Settings & Configuration](#settings--configuration)
11. [SEO Optimization](#seo-optimization)
12. [Troubleshooting](#troubleshooting)

---

## 🚀 Getting Started

### Accessing the Admin Panel

1. Navigate to `https://yourdomain.com/admin`
2. Enter your admin credentials
3. Click "Login"

### Dashboard Overview

The admin dashboard provides quick access to:
- **Quick Actions**: Create new pages, posts, and products
- **Statistics**: View counts of pages, posts, products, and users
- **Recent Activity**: See latest content updates

---

## 🎨 Admin Panel Overview

### Navigation Menu

**Resources**
- **Pages**: Manage static pages (About, Contact, etc.)
- **Posts**: Create and edit blog articles
- **Products**: Manage e-commerce products
- **Categories**: Organize content
- **Tags**: Add topic tags
- **Users**: Manage user accounts

**System**
- **Languages**: Manage translations
- **Widgets**: Configure homepage widgets
- **Templates**: Page templates
- **Settings**: System configuration

---

## 📄 Managing Pages

### Creating a New Page

1. Click **Pages** → **Create Page**
2. Fill in the form:
   - **Title** (Romanian): Page title in Romanian
   - **Title** (English, etc.): Translations for other languages
   - **Slug**: URL-friendly version (e.g., `about-us`)
   - **Content**: Page body using rich text editor
   - **Excerpt**: Short description for previews
   - **Status**: Published, Draft, or Scheduled
   - **Featured Image**: Upload main image

3. **SEO Settings** (Advanced):
   - **Meta Title**: Custom SEO title
   - **Meta Description**: SEO description (150-160 chars)
   - **Meta Keywords**: Comma-separated keywords

4. Click **Save**

### Editing Existing Pages

1. Go to **Pages**
2. Click on page title or **Edit** action
3. Make changes
4. Click **Save**

### Deleting Pages

1. Go to **Pages**
2. Select page(s) using checkboxes
3. Click **Delete** from bulk actions
4. Confirm deletion

---

## 📝 Managing Blog Posts

### Creating a Blog Post

1. Click **Posts** → **Create Post**
2. Fill in required fields:
   - **Title**: Post title (all languages)
   - **Slug**: Auto-generated from title
   - **Category**: Select post category
   - **Tags**: Add relevant tags
   - **Content**: Write post using editor
   - **Excerpt**: Short summary
   - **Featured Image**: Upload post image
   - **Status**: Published/Draft/Scheduled
   - **Published At**: Publication date

3. **SEO Optimization**:
   - Add meta description
   - Include focus keywords
   - Optimize title for search

4. Click **Publish** or **Save as Draft**

### Scheduling Posts

1. Create or edit post
2. Set **Status** to "Scheduled"
3. Select future **Published At** date
4. Save

### Managing Categories

1. Go to **Categories**
2. Click **Create Category**
3. Add:
   - Name (all languages)
   - Slug
   - Description
   - Icon/Color (optional)
4. Save

---

## 🛍️ Managing Products

### Adding a Product

1. Click **Products** → **Create Product**
2. Complete product details:
   - **Name**: Product name (multilingual)
   - **SKU**: Stock keeping unit
   - **Description**: Full product details
   - **Price**: Regular price
   - **Sale Price**: Discounted price (optional)
   - **Stock**: Quantity available
   - **Category**: Product category
   - **Images**: Upload multiple images
   - **Status**: Active/Inactive

3. **Variants** (if applicable):
   - Add size/color variants
   - Set individual prices
   - Track stock per variant

4. Click **Save**

### Product Categories

1. Go to **Categories** → **Product Categories**
2. Create hierarchical structure
3. Add category images
4. Set sorting order

---

## 🌍 Translation Editor

### Accessing Translation Editor

1. Go to **Languages** in admin menu
2. Click **Editează Traduceri** (Edit Translations)

### Editing Translations

1. **Select Language**: Choose from dropdown (RO, EN, ES, etc.)
2. **Select File**: Choose translation file:
   - `messages.php` - Main translations
   - `validation.php` - Form validations
   - `auth.php` - Authentication messages

3. **Edit Translations**:
   - Find the key you want to edit
   - Update the translation value
   - Click **💾** to save individual item
   
4. **Search**: Use search box to filter keys

### Adding New Translations

1. Scroll to "Add New Translation" section
2. Enter:
   - **Key**: Translation identifier (e.g., `welcome_message`)
   - **Value**: Translated text
3. Click **Add Translation**

### Deleting Translations

1. Find translation to delete
2. Click **🗑️** (Delete) icon
3. Confirm deletion

**Note**: Cache is automatically cleared after saving!

---

## 🗣️ Language Management

### Adding a New Language

1. Go to **Languages**
2. Click **Create Language**
3. Fill in:
   - **Name**: Language name (e.g., "Deutsch")
   - **Code**: ISO code (e.g., "de")
   - **Locale**: Full locale (e.g., "de_DE")
   - **Direction**: LTR or RTL
   - **Active**: Enable/disable

4. Click **Save**

### Setting Default Language

1. Edit language
2. Check **Is Default** checkbox
3. Save

### Activating/Deactivating Languages

1. Go to **Languages**
2. Toggle **Active** switch
3. Inactive languages won't show on frontend

---

## 🏷️ Categories & Tags

### Creating Categories

1. Navigate to **Categories**
2. Click **Create**
3. Add:
   - Name (multilingual)
   - Slug (auto-generated)
   - Description
   - Parent category (for hierarchy)
   - Color/Icon
4. Save

### Managing Tags

1. Go to **Tags**
2. Create new tags with:
   - Name
   - Slug
   - Description (optional)

### Assigning to Content

- When creating/editing posts or products
- Select categories from dropdown
- Add tags by typing and pressing Enter

---

## 📁 Media Library

### Uploading Files

1. Click **Upload** button
2. Select files or drag & drop
3. Files are automatically organized by date

### Using Images

1. Click image to view details
2. Copy URL for external use
3. Click **Insert** when adding to content

### File Types Supported

- **Images**: JPG, PNG, GIF, WebP, SVG
- **Documents**: PDF, DOC, DOCX
- **Other**: ZIP, CSV, XLS

### Best Practices

- Optimize images before upload (max 2MB)
- Use descriptive filenames
- Add alt text for SEO

---

## ⚙️ Settings & Configuration

### General Settings

1. Go to **Settings** → **General**
2. Configure:
   - Site name
   - Tagline
   - Default language
   - Timezone
   - Date format

### Contact Settings

1. Navigate to **Settings** → **Contact**
2. Add:
   - Email address
   - Phone number
   - Physical address
   - Social media links

### Email Configuration

1. Go to **Settings** → **Email**
2. Set up SMTP:
   - Mail driver
   - Host & port
   - Username & password
   - Encryption (TLS/SSL)

---

## 🎯 SEO Optimization

### Page-Level SEO

For each page/post, optimize:

1. **Title Tag**: 
   - Keep under 60 characters
   - Include primary keyword
   
2. **Meta Description**:
   - 150-160 characters
   - Compelling, action-oriented
   
3. **URL Slug**:
   - Short, descriptive
   - Include keywords
   - Use hyphens, not underscores

4. **Headings**:
   - One H1 per page (title)
   - Use H2-H6 for structure
   
5. **Images**:
   - Add alt text
   - Optimize file size
   - Use descriptive filenames

### Sitemap

- Automatically generated at `/sitemap.xml`
- Updates when content changes
- Submit to Google Search Console

### Robots.txt

- Located at `/robots.txt`
- Controls search engine crawling
- Edit in Settings → SEO

---

## 🔧 Troubleshooting

### Common Issues

**Can't login to admin**
- Check credentials
- Clear browser cache
- Reset password via "Forgot Password"

**Changes not appearing**
- Clear application cache: Settings → Clear Cache
- Hard refresh browser (Ctrl+F5)
- Check if content is published

**Images not loading**
- Verify file permissions (755 for storage/)
- Check storage link: `php artisan storage:link`
- Ensure public/storage symlink exists

**Translations not showing**
- Clear translation cache
- Verify language is active
- Check translation file exists

**Slow performance**
- Enable caching in Settings
- Optimize images
- Clear old log files

### Cache Management

**Clear All Caches**:
1. Go to Settings → Cache
2. Click "Clear All Caches"

**Or via terminal**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Getting Help

**Support Channels**:
- Email: support@carphatian.ro
- Documentation: https://carphatian.ro/docs
- Community Forum: https://community.carphatian.ro

---

## 📋 Quick Reference

### Keyboard Shortcuts

- `Ctrl + S` - Save current form
- `Ctrl + P` - Preview page
- `Esc` - Close modal

### Content Status

- **Draft**: Not visible to public
- **Published**: Live and visible
- **Scheduled**: Will publish at set date
- **Archived**: Hidden but not deleted

### User Roles

- **Super Admin**: Full system access
- **Admin**: Manage content and users
- **Editor**: Create and edit content
- **Author**: Create own posts only
- **Subscriber**: View-only access

---

**Made with ❤️ by Carphatian**
