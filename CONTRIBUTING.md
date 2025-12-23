# Contributing to Carpathian CMS

Thank you for your interest in contributing to Carpathian CMS! 🎉

## Table of Contents

1. [Code of Conduct](#code-of-conduct)
2. [How Can I Contribute?](#how-can-i-contribute)
3. [Development Setup](#development-setup)
4. [Pull Request Process](#pull-request-process)
5. [Coding Standards](#coding-standards)
6. [Testing](#testing)

---

## Code of Conduct

By participating in this project, you agree to:

- Be respectful and inclusive
- Provide constructive feedback
- Focus on what is best for the community
- Show empathy towards other community members

## How Can I Contribute?

### Reporting Bugs

Found a bug? Help us improve!

1. **Check existing issues** - Someone might have already reported it
2. **Create a detailed bug report** including:
   - Clear title and description
   - Steps to reproduce
   - Expected vs actual behavior
   - Screenshots (if applicable)
   - System information (OS, PHP version, etc.)

### Suggesting Features

Have an idea for a new feature?

1. **Open a GitHub Discussion** first to discuss the idea
2. **Explain the use case** - Why would this feature be useful?
3. **Provide examples** - How would it work?

### Writing Code

Ready to contribute code? Great!

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write/update tests
5. Submit a pull request

---

## Development Setup

### Prerequisites

- PHP 8.4+
- Composer 2.x
- MySQL 8.0+
- Node.js 18+
- Git

### Installation

```bash
# Fork and clone your fork
git clone https://github.com/YOUR_USERNAME/carpathian-cms.git
cd carpathian-cms

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
mysql -u root -p
CREATE DATABASE carpathian_cms_dev;
exit

# Update .env with database credentials
php artisan migrate --seed

# Build assets
npm run dev

# Start server
php artisan serve
```

### Development Environment

- **IDE:** VS Code, PHPStorm, or your preferred editor
- **Code Style:** PSR-12
- **Database:** MySQL 8.0 (or MariaDB 10.3+)
- **Testing:** PHPUnit

---

## Pull Request Process

### 1. Create a Branch

```bash
git checkout -b feature/amazing-feature
# or
git checkout -b fix/bug-description
```

**Branch naming:**
- `feature/` - New features
- `fix/` - Bug fixes
- `docs/` - Documentation updates
- `refactor/` - Code refactoring
- `test/` - Adding/updating tests

### 2. Make Your Changes

- Write clean, readable code
- Follow PSR-12 coding standards
- Add comments for complex logic
- Update documentation if needed

### 3. Test Your Changes

```bash
# Run tests
php artisan test

# Check code style
./vendor/bin/phpcs

# Fix code style
./vendor/bin/phpcbf
```

### 4. Commit Your Changes

Write clear, descriptive commit messages:

```bash
git commit -m "Add AI content generation for products"
# or
git commit -m "Fix: Resolve payment gateway timeout issue"
```

**Good commit messages:**
- Use present tense ("Add feature" not "Added feature")
- Be descriptive but concise
- Reference issues when applicable: "Fix #123: Resolve login bug"

### 5. Push to Your Fork

```bash
git push origin feature/amazing-feature
```

### 6. Submit Pull Request

1. Go to the original repository
2. Click "New Pull Request"
3. Select your fork and branch
4. Fill in the PR template:
   - **Description:** What does this PR do?
   - **Related Issues:** Link any related issues
   - **Changes:** List the main changes
   - **Screenshots:** Add if applicable
   - **Testing:** How was this tested?

### 7. Code Review

- Be responsive to feedback
- Make requested changes promptly
- Discuss any disagreements respectfully
- Update your PR branch as needed

---

## Coding Standards

### PHP Code Style (PSR-12)

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Generate content using AI.
     *
     * @param  string  $prompt
     * @param  array  $options
     * @return string
     */
    public function generateContent(string $prompt, array $options = []): string
    {
        // Implementation
    }
}
```

**Key points:**
- 4 spaces for indentation (no tabs)
- Opening braces on the same line for classes/functions
- Use type hints and return types
- Document methods with PHPDoc

### JavaScript/Vue Style

```javascript
// Use const/let (not var)
const apiUrl = '/api/generate';

// Arrow functions for callbacks
items.map(item => item.name);

// Descriptive variable names
const userGeneratedContent = generateAI(prompt);
```

### Blade Templates

```blade
{{-- Clear, semantic HTML --}}
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold">
        {{ $title }}
    </h1>
    
    @if ($showContent)
        <div class="content">
            {!! $content !!}
        </div>
    @endif
</div>
```

---

## Testing

### Writing Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AIGenerationTest extends TestCase
{
    /** @test */
    public function it_generates_blog_post_content()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/api/ai/generate', [
                'prompt' => 'Write about Laravel',
                'type' => 'blog_post',
            ]);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'content',
                'title',
                'meta_description',
            ]);
    }
}
```

### Running Tests

```bash
# All tests
php artisan test

# Specific test file
php artisan test tests/Feature/AIGenerationTest.php

# With coverage
php artisan test --coverage
```

---

## Documentation

### Updating Docs

If your changes affect documentation:

1. Update relevant `.md` files in `/docs`
2. Update inline code comments
3. Add examples where helpful
4. Keep language clear and concise

### Doc Structure

```
docs/
├── INSTALLATION.md       # Installation guide
├── CONFIGURATION.md      # Configuration options
├── AI_INTEGRATION.md     # AI setup and usage
├── MULTILINGUAL.md       # Multilingual features
└── ECOMMERCE.md          # E-commerce features
```

---

## Questions?

- 💬 [GitHub Discussions](https://github.com/msrusu87-web/carpathian-cms/discussions)
- 📧 Email: support@carphatian.ro
- 🐛 [Report Issues](https://github.com/msrusu87-web/carpathian-cms/issues)

---

## Recognition

Contributors will be:
- Listed in the [Contributors](https://github.com/msrusu87-web/carpathian-cms/graphs/contributors) page
- Mentioned in release notes for significant contributions
- Eligible for "Contributor" badge on our website

---

Thank you for contributing to Carpathian CMS! 🚀

Your contributions make this project better for everyone.
