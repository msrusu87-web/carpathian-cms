# 🤖 AI Integration Guide

Complete guide to setting up and using AI features in Carpathian CMS.

## Table of Contents

1. [Overview](#overview)
2. [Groq Setup (Recommended)](#groq-setup-recommended)
3. [OpenAI Setup](#openai-setup)
4. [AI Features](#ai-features)
5. [Content Generation](#content-generation)
6. [SEO Optimization](#seo-optimization)
7. [Translations](#translations)
8. [Custom AI Service](#custom-ai-service)
9. [API Usage & Limits](#api-usage--limits)
10. [Troubleshooting](#troubleshooting)

---

## Overview

Carpathian CMS integrates with multiple AI providers to offer powerful content generation, SEO optimization, and translation capabilities.

### Supported Providers

| Provider | Model | Speed | Cost | Best For |
|----------|-------|-------|------|----------|
| **Groq** | Llama 3.1 70B | ⚡ Ultra Fast | 🆓 Free | Content generation |
| **OpenAI** | GPT-4o | 🐢 Moderate | 💰 Paid | Advanced features |
| **Custom** | Any | Varies | Varies | Self-hosted |

---

## Groq Setup (Recommended)

### Why Groq?

✅ **FREE** - No credit card required  
✅ **Ultra Fast** - 500+ tokens/second  
✅ **Powerful** - Llama 3.1 70B model  
✅ **High Limits** - 30 requests/minute  

### Step 1: Get API Key

1. Visit [https://console.groq.com](https://console.groq.com)
2. Sign up (free, no credit card)
3. Go to **API Keys** section
4. Click **Create API Key**
5. Copy your key (starts with `gsk_`)

### Step 2: Configure `.env`

```bash
# AI Provider
AI_PROVIDER=groq

# Groq API Configuration
GROQ_API_KEY=gsk_your_api_key_here
GROQ_MODEL=llama-3.1-70b-versatile
GROQ_MAX_TOKENS=8000
GROQ_TEMPERATURE=0.7
```

### Step 3: Test Connection

```bash
php artisan ai:test
```

Expected output:
```
✓ Groq API connection successful
✓ Model: llama-3.1-70b-versatile
✓ Response time: 1.2s
```

---

## OpenAI Setup

### Why OpenAI?

✅ Most advanced models (GPT-4o)  
✅ Best for complex tasks  
✅ Excellent code generation  
⚠️ Requires payment  

### Step 1: Get API Key

1. Visit [https://platform.openai.com](https://platform.openai.com)
2. Sign up and add billing info
3. Go to **API Keys** section
4. Click **Create new secret key**
5. Copy your key (starts with `sk-`)

### Step 2: Configure `.env`

```bash
# AI Provider
AI_PROVIDER=openai

# OpenAI API Configuration
OPENAI_API_KEY=sk-your_api_key_here
OPENAI_MODEL=gpt-4o
OPENAI_MAX_TOKENS=4000
OPENAI_TEMPERATURE=0.7
```

### Step 3: Add Credits

1. Go to **Billing** in OpenAI dashboard
2. Add $5-$10 to start
3. Set spending limits

### Cost Estimates

| Task | Tokens | Cost (GPT-4o) |
|------|--------|---------------|
| Blog post (1000 words) | ~1500 | $0.045 |
| Product description | ~200 | $0.006 |
| SEO meta tags | ~100 | $0.003 |
| Translation (1 page) | ~500 | $0.015 |

---

## AI Features

### 1. Content Generation

Generate complete blog posts, pages, and articles:

#### From Admin Panel

1. Go to **Content → Pages** (or Posts)
2. Click **Create**
3. Click **🤖 AI Generate** button
4. Enter topic/keywords
5. Select tone (professional, casual, technical)
6. Click **Generate**
7. Review and edit

#### Via API

```bash
curl -X POST https://yourdomain.com/api/ai/generate \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "topic": "Benefits of Laravel",
    "type": "blog_post",
    "length": "long",
    "tone": "professional"
  }'
```

### 2. Product Descriptions

Auto-generate compelling product descriptions:

```bash
# Admin: Products → Edit → AI Generate Description

# API
curl -X POST https://yourdomain.com/api/ai/product-description \
  -d '{
    "product_name": "Wireless Headphones",
    "features": ["Noise cancellation", "30h battery"],
    "target_audience": "Music lovers"
  }'
```

### 3. SEO Optimization

Generate SEO-friendly meta tags:

#### Meta Titles & Descriptions

```bash
# Admin: Pages → SEO Tab → Generate Meta Tags

# API
curl -X POST https://yourdomain.com/api/ai/seo \
  -d '{
    "content": "Your page content here...",
    "keywords": ["laravel", "cms", "ai"]
  }'
```

Output:
```json
{
  "meta_title": "Complete Laravel CMS Guide - Features & Benefits",
  "meta_description": "Discover how our Laravel CMS with AI integration can transform your content workflow. Features include...",
  "keywords": ["laravel cms", "ai content generation", "multilingual cms"]
}
```

### 4. Smart Translations

AI-powered content translations:

```bash
# Admin: Pages → Translate → AI Translate

# API
curl -X POST https://yourdomain.com/api/ai/translate \
  -d '{
    "text": "Hello World",
    "from": "en",
    "to": "ro"
  }'
```

---

## Content Generation

### Blog Post Generation

**Admin Panel:**

1. Navigate to **Content → Blog Posts**
2. Click **Create New Post**
3. Click **🤖 AI Assistant** button
4. Fill in the form:
   - **Topic:** "How to optimize Laravel performance"
   - **Keywords:** laravel, performance, optimization
   - **Tone:** Technical
   - **Length:** Long (1500-2000 words)
5. Click **Generate**
6. Wait 5-10 seconds
7. Review generated content
8. Edit and customize
9. Save & Publish

### Prompt Templates

Pre-built prompts for common content types:

```php
// Blog Post
"Write a comprehensive blog post about {topic}. 
Include an engaging introduction, 5-7 main points with examples, 
and a conclusion with actionable takeaways. 
Target audience: {audience}. Tone: {tone}."

// Product Description  
"Create a compelling product description for {product_name}. 
Highlight key features: {features}. 
Include benefits, use cases, and a call-to-action. 
Keep it under 200 words."

// Landing Page
"Write persuasive landing page copy for {product/service}. 
Include: headline, subheadline, problem statement, solution, 
features (3-5), social proof, and CTA. 
Target: {target_audience}."
```

### Custom Prompts

Create your own prompts:

1. Go to **Settings → AI Configuration**
2. Click **Custom Prompts**
3. Add new prompt template
4. Use variables: `{topic}`, `{keywords}`, `{tone}`

---

## SEO Optimization

### Auto-Generate Meta Tags

```php
// In your page/post edit form
AIService::generateMetaTags($content, $keywords);
```

Returns:
```php
[
    'title' => 'SEO-Optimized Title | Brand Name',
    'description' => 'Compelling 155-character meta description...',
    'keywords' => ['keyword1', 'keyword2', 'keyword3'],
    'og_title' => 'Social Media Title',
    'og_description' => 'Social media optimized description',
]
```

### Content Analysis

Analyze existing content for SEO:

```bash
php artisan ai:analyze-seo {page_id}
```

Output:
```
SEO Analysis for: "Your Page Title"
================================
✓ Title length: 58 characters (optimal)
✓ Description length: 152 characters (optimal)
⚠ Keyword density: 0.8% (low, aim for 1-2%)
⚠ Headings: Missing H2 tags
✓ Image alt texts: All present
⚠ Word count: 487 (recommend 800+ for better ranking)

Recommendations:
1. Add more H2/H3 headings
2. Increase content length to 800+ words
3. Include target keyword 2-3 more times
```

---

## Translations

### Batch Translation

Translate entire pages at once:

**Admin:**
1. Go to **Pages → Select Page**
2. Click **Languages** tab
3. Select target languages
4. Click **AI Translate All**
5. Review translations
6. Save

**CLI:**
```bash
# Translate all pages to Spanish
php artisan ai:translate-content --model=Page --language=es

# Translate specific post
php artisan ai:translate-content --model=Post --id=5 --language=ro
```

### Translation Quality

| Content Type | Accuracy | Recommendation |
|--------------|----------|----------------|
| General content | 95% | Use as-is |
| Technical content | 85% | Review carefully |
| Marketing copy | 90% | Adjust tone |
| Legal text | 70% | Manual review required |

---

## Custom AI Service

### Self-Hosted FastAPI

Run your own AI service for full control:

#### Step 1: Deploy FastAPI Service

```bash
cd chat-carphatian/ai-service
docker-compose up -d
```

#### Step 2: Configure `.env`

```bash
AI_PROVIDER=custom
AI_SERVICE_URL=http://localhost:8001
AI_SERVICE_API_KEY=your_custom_key
AI_SERVICE_TIMEOUT=60
```

#### Step 3: API Endpoints

```python
# ai-service/main.py

@app.post("/generate")
async def generate_content(request: ContentRequest):
    # Your custom AI logic
    return {"content": generated_text}

@app.post("/translate")
async def translate(request: TranslateRequest):
    # Your translation logic
    return {"translated": translated_text}
```

---

## API Usage & Limits

### Groq Limits

| Limit Type | Value |
|------------|-------|
| Requests/minute | 30 |
| Tokens/minute | 20,000 |
| Max tokens/request | 8,000 |
| Concurrent requests | 5 |

### OpenAI Limits

| Tier | Requests/min | Tokens/min |
|------|--------------|------------|
| Free | 3 | 40,000 |
| Tier 1 | 500 | 150,000 |
| Tier 2 | 5,000 | 1,500,000 |

### Monitor Usage

```bash
# Check current usage
php artisan ai:usage

# Output:
# Today's usage:
# - Requests: 145 / 30,000
# - Tokens: 25,340 / 1,000,000
# - Cost: $0.76
```

### Rate Limiting

Prevent hitting API limits:

```php
// config/ai.php
'rate_limiting' => [
    'enabled' => true,
    'max_requests_per_minute' => 20,
    'queue_when_limited' => true,
],
```

---

## Best Practices

### 1. Content Generation

✅ **DO:**
- Review AI-generated content before publishing
- Use AI as a starting point, not final copy
- Fact-check technical information
- Adjust tone to match your brand

❌ **DON'T:**
- Publish AI content without review
- Rely on AI for factual accuracy
- Use AI for medical/legal advice
- Generate duplicate content

### 2. Cost Optimization

- Use Groq for most content (free)
- Reserve OpenAI for complex tasks
- Cache frequent requests
- Batch similar requests

### 3. Quality Control

```php
// Set up approval workflow
AIContent::create([
    'content' => $ai_generated_content,
    'status' => 'pending_review',  // Requires human approval
    'reviewer_id' => null,
]);
```

---

## Troubleshooting

### "API Key Invalid"

```bash
# Verify key format
# Groq: gsk_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
# OpenAI: sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# Test connection
php artisan ai:test
```

### "Rate Limit Exceeded"

```bash
# Check current usage
php artisan ai:usage

# Wait 60 seconds and retry
# Or switch to different provider temporarily
```

### "Timeout Error"

Increase timeout in `.env`:
```bash
AI_SERVICE_TIMEOUT=120  # seconds
```

### Slow Response Times

**Groq (Fast):**
- Usually responds in 1-2 seconds
- If slow, check internet connection

**OpenAI (Slower):**
- GPT-4o: 5-10 seconds
- Use GPT-3.5-turbo for faster responses

### Generated Content Quality Issues

**Improve with better prompts:**

❌ Poor: "Write about Laravel"

✅ Good: "Write a 1000-word technical guide about Laravel performance optimization. Include: caching strategies, database query optimization, and asset compilation. Target audience: intermediate developers. Tone: technical but approachable."

---

## Advanced Features

### Context-Aware Generation

Provide context for better results:

```php
AIService::generate([
    'prompt' => 'Write a product description',
    'context' => [
        'brand_voice' => 'Professional, friendly',
        'target_audience' => 'Small business owners',
        'previous_content' => '...',  // Similar content for reference
    ],
]);
```

### Embeddings for Search

Use AI embeddings for semantic search:

```bash
php artisan ai:generate-embeddings --model=Page
```

### Sentiment Analysis

Analyze customer reviews:

```php
$sentiment = AIService::analyzeSentiment($review_text);
// Returns: 'positive', 'negative', or 'neutral'
```

---

## Next Steps

- [📝 Content Management](CUSTOMIZATION.md)
- [⚙️ Configuration](CONFIGURATION.md)
- [🌐 Multilingual Setup](MULTILINGUAL.md)

---

**Need help?** Visit [carphatian.ro](https://carphatian.ro) or [open an issue](https://github.com/msrusu87-web/carpathian-cms/issues).
