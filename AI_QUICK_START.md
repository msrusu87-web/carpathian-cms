# 🚀 AI Content Generator - Quick Start

## Setup (5 minutes)

### 1. Get Groq API Key
1. Visit: https://console.groq.com/
2. Sign up (free account)
3. Go to: API Keys section
4. Click "Create API Key"
5. Copy the key (starts with `gsk_...`)

### 2. Configure
```bash
# Edit .env file
nano /var/www/carphatian.ro/html/.env

# Add this line:
GROQ_API_KEY=gsk_your_actual_key_here

# Save and exit (Ctrl+X, Y, Enter)

# Clear cache
cd /var/www/carphatian.ro/html
php artisan config:cache
```

### 3. Test
1. Login to admin: https://carphatian.ro/admin
2. Go to: Products → Edit any product
3. Look for ✨ "Generate with AI" button in header
4. Click it and try generating content!

## Usage (30 seconds per field)

### For Products:
1. Edit product → Click ✨ "Generate with AI"
2. Select: `Description`, `SEO Title`, `SEO Keywords`
3. Instructions: "Write compelling description for [your product]"
4. Tone: `Persuasive`
5. Click "Generate" → Wait 10 seconds → Done!

### For Blog Posts:
1. Edit post → Click ✨ "Generate with AI"
2. Select: `Content`, `Excerpt`
3. Instructions: "Write informative article about [topic]"
4. Tone: `Professional`
5. Click "Generate" → Wait 10 seconds → Done!

### For Pages:
1. Edit page → Click ✨ "Generate with AI"
2. Select: `Content`
3. Instructions: "Write about [page topic]"
4. Tone: `Friendly`
5. Click "Generate" → Wait 10 seconds → Done!

## Tips for Best Results

### ✅ DO:
- Be specific in instructions
- Mention key points to cover
- Specify target audience
- Use "regenerate" if not satisfied
- Keep "Use existing data" ON

### ❌ DON'T:
- Use vague instructions like "write something"
- Expect perfect results first try
- Forget to review/edit generated content
- Generate without context (turn OFF only if creating from scratch)

## Common Use Cases

### 1. New Product Launch
```
Fields: Name, Description, SEO Title, SEO Description, SEO Keywords
Instructions: "Create compelling copy for [product]. Focus on: [benefit 1], [benefit 2], [benefit 3]"
Tone: Persuasive
Length: Medium
Result: Complete product page in 15 seconds
```

### 2. Blog Post Creation
```
Fields: Title, Content, Excerpt, SEO Description
Instructions: "Write blog post about [topic]. Include: introduction, 3-5 main points, examples, conclusion"
Tone: Professional
Length: Long
Result: Full blog post in 20 seconds
```

### 3. SEO Optimization
```
Fields: SEO Title, SEO Description, SEO Keywords
Instructions: "Optimize for keyword: [your keyword]"
Tone: Professional
Length: Short
Use existing: ON
Result: SEO fields updated in 5 seconds
```

### 4. Translation/Adaptation
```
1. Switch language (EN/RO/DE/FR/ES/IT)
2. Fields: All content fields
3. Instructions: "Translate and adapt content for [language] audience"
Tone: Match original
Use existing: ON
Result: Localized content in 15 seconds
```

## Troubleshooting

### "Generation Failed"
**Fix**: Check GROQ_API_KEY in .env is correct

### Content too generic
**Fix**: Add more details in instructions

### HTML looks broken
**Fix**: AI should handle this. If not, edit manually

### Wrong language
**Fix**: Switch language in header BEFORE generating

## Where to Find AI Button?

The ✨ "Generate with AI" button is located in the **header (top right)** of edit pages:

- `/admin/products/{id}/edit` → Header → ✨ Generate with AI
- `/admin/pages/{id}/edit` → Header → ✨ Generate with AI
- `/admin/posts/{id}/edit` → Header → ✨ Generate with AI
- `/admin/widgets/{id}/edit` → Header → ✨ Generate with AI

It's next to:
- Language switcher (EN/RO/DE...)
- View/Delete buttons

## Free Tier Limits

Groq AI Free Tier:
- ✅ 14,400 requests/day
- ✅ 30 requests/minute
- ✅ No credit card required
- ✅ Commercial use allowed

**More than enough for most websites!**

## Next Steps

1. ✅ Setup complete? → Start generating content!
2. 📖 Want to learn more? → Read `AI_CONTENT_GENERATOR_GUIDE.md`
3. 🔧 Need technical details? → Read `AI_CONTENT_GENERATOR_TECHNICAL.md`
4. 💡 Have questions? → Contact support@carphatian.ro

---

**Ready to 10x your content creation speed?** 🚀  
**Get started now!** ✨
