# Multilingual Admin Guide

## Overview
The Carpathian CMS now supports full multilingual editing for Products, Pages, and Blog Posts through the admin panel. Each content type can have separate translations for all supported languages.

## Supported Languages
- 🇬🇧 English (en)
- 🇷🇴 Română (ro)
- 🇩🇪 Deutsch (de)
- 🇫🇷 Français (fr)
- 🇪🇸 Español (es)
- 🇮🇹 Italiano (it)

## How to Edit Multilingual Content

### Products
1. Go to **E-commerce → Products** in the admin panel
2. Create or edit a product
3. You'll see **language tabs** in the "Multilingual Content" section
4. Click on each language tab to enter:
   - **Product Name** - The title of the product in that language
   - **Short Description** - Brief product summary (optional)
   - **Full Description** - Complete product details with rich text editor

#### Product Fields Per Language:
- Name (required for English)
- Description (short summary)
- Content (full HTML description)

#### Common Fields (Not Translated):
- SKU
- Price
- Sale Price
- Stock
- Images
- Category
- Active status
- Featured status

### Pages
1. Go to **CMS → Pages** in the admin panel
2. Create or edit a page
3. Find the **"Multilingual Content"** section with language tabs
4. For each language, enter:
   - **Page Title** - The page heading
   - **Page Excerpt** - Brief summary
   - **Page Content** - Full page content with TinyMCE editor

#### Page Fields Per Language:
- Title (required for English)
- Excerpt (optional summary)
- Content (full HTML content)

#### Common Fields (Not Translated):
- Slug (URL identifier)
- Featured image
- Status (draft/published)
- Publication date
- Author
- Template
- SEO meta tags
- Custom fields

### Blog Posts
1. Go to **Blog → Articles** in the admin panel
2. Create or edit a post
3. Navigate through the **language tabs** in "Multilingual Content"
4. Enter for each language:
   - **Post Title** - The article title
   - **Post Excerpt** - Short summary
   - **Post Content** - Full article with rich text editor

#### Post Fields Per Language:
- Title (required for English)
- Excerpt (optional summary)
- Content (full HTML content)

#### Common Fields (Not Translated):
- Slug (URL identifier)
- Featured image
- Status (draft/published)
- Publication date
- Author
- Category
- Tags
- SEO meta tags

## Best Practices

### 1. Start with English
Always fill in the English content first as it's required and serves as the fallback language.

### 2. Use Auto-Generated Slugs
The slug is automatically generated from the English title. It's the same across all languages for consistent URLs.

### 3. Translation Priority
Focus on translating:
1. **High Priority**: Title, Name, Short descriptions
2. **Medium Priority**: Full content/descriptions
3. **Optional**: SEO meta tags (can be auto-generated)

### 4. Consistency
- Keep product names consistent with their branding across languages
- Maintain the same tone and style in all language versions
- Verify translations show correctly on the frontend before publishing

### 5. Images and Media
Images are shared across all languages. Add localized text as image captions in the content editor if needed.

## Frontend Display

### Language Detection
The website automatically displays content in the user's selected language based on:
1. User's language selection from the language switcher
2. Browser language preference
3. Default language (Romanian)

### Product Cards
Product cards now show:
- Translated product name
- Translated description (if available)
- Localized "View Details" button
- Category name in the current language
- All prices, discounts, and badges

### Empty States
If no translation exists for a field in a specific language, the system falls back to English, then Romanian.

## Database Structure

### Translatable Fields Storage
Translatable fields are stored as JSON in the database:
```json
{
  "en": "English text",
  "ro": "Text românesc",
  "de": "Deutscher Text",
  "fr": "Texte français",
  "es": "Texto español",
  "it": "Testo italiano"
}
```

### Models Using Spatie Translatable
- `Product` - name, description, content
- `Page` - title, content, excerpt
- `Post` - title, content, excerpt
- `ProductCategory` - name, description
- `Portfolio` - title, description, content

## Troubleshooting

### Translations Not Showing
1. Clear cache: Run `php artisan cache:clear`
2. Clear views: Run `php artisan view:clear`
3. Check that the translation was saved in the correct language tab
4. Verify the locale cookie is set correctly

### Admin Panel Tabs Not Appearing
1. Make sure you're using the latest version of the admin panel
2. Clear your browser cache
3. Log out and log back in

### Migration Issues
If you encounter database errors, ensure the migration ran successfully:
```bash
php artisan migrate:status
```

Look for: `2025_12_18_140027_ensure_translatable_fields_are_json`

## Technical Details

### Files Modified
- `/app/Filament/Resources/ProductResource.php` - Added language tabs
- `/app/Filament/Resources/PageResource.php` - Added language tabs
- `/app/Filament/Resources/PostResource.php` - Added language tabs
- `/resources/views/widgets/products.blade.php` - Updated to use translations
- Database migration for JSON field conversion

### Translation Helper Usage
In Blade templates:
```php
// Get translated field
$product->getTranslation('name', app()->getLocale())

// Or use the helper
__('messages.key_name')
```

## Future Enhancements
- Bulk translation tools
- Translation completion percentage
- AI-assisted translation suggestions
- Import/Export translations for professional translation services

---

**Last Updated**: December 18, 2025
**Version**: 1.0
