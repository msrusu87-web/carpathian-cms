#!/bin/bash

#######################################
# Carpathian CMS - GitHub Push Script
# Sanitizes sensitive data before push
#######################################

set -e

echo "🧹 Preparing Carpathian CMS for GitHub..."

# Navigate to project directory
cd "$(dirname "$0")"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}⚠️  This script will remove sensitive data before pushing to GitHub${NC}"
read -p "Continue? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    echo "Aborted."
    exit 1
fi

# Backup current .env
if [ -f .env ]; then
    echo "📦 Backing up .env..."
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
fi

# Check if .env.example exists and sanitize it
echo "🔒 Sanitizing .env.example..."
if [ ! -f .env.example ]; then
    cp .env .env.example
fi

# Remove sensitive values from .env.example
sed -i 's/GROQ_API_KEY=.*/GROQ_API_KEY=your_groq_api_key_here/' .env.example
sed -i 's/OPENAI_API_KEY=.*/OPENAI_API_KEY=your_openai_api_key_here/' .env.example
sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=your_database_password/' .env.example
sed -i 's/MAIL_PASSWORD=.*/MAIL_PASSWORD=your_email_password/' .env.example
sed -i 's/STRIPE_SECRET=.*/STRIPE_SECRET=your_stripe_secret_key/' .env.example
sed -i 's/STRIPE_KEY=.*/STRIPE_KEY=your_stripe_publishable_key/' .env.example
sed -i 's/PAYPAL_SECRET=.*/PAYPAL_SECRET=your_paypal_secret/' .env.example
sed -i 's/PAYPAL_CLIENT_ID=.*/PAYPAL_CLIENT_ID=your_paypal_client_id/' .env.example
sed -i 's/APP_KEY=.*/APP_KEY=/' .env.example

# Ensure .env is in .gitignore
echo "📝 Checking .gitignore..."
if ! grep -q "^\.env$" .gitignore 2>/dev/null; then
    echo ".env" >> .gitignore
fi

# Add other sensitive files to .gitignore
cat >> .gitignore <<EOF

# Sensitive files
.env.backup.*
*.sql
database_backup.sql
*.pem
*.key
*.cert

# IDE
.vscode/
.idea/
*.swp
*.swo
*~

# OS
.DS_Store
Thumbs.db

# Laravel
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor

EOF

# Remove duplicate lines in .gitignore
sort -u .gitignore -o .gitignore

# Sanitize database export if it exists
if [ -f database_sample.sql ]; then
    echo "🗄️  Sanitizing database export..."
    
    # Remove INSERT statements for sensitive tables
    sed -i '/INSERT INTO `users`/d' database_sample.sql
    sed -i '/INSERT INTO `personal_access_tokens`/d' database_sample.sql
    sed -i '/INSERT INTO `sessions`/d' database_sample.sql
    sed -i '/INSERT INTO `password_reset_tokens`/d' database_sample.sql
    
    echo -e "${GREEN}✅ Database sanitized - user data removed${NC}"
fi

# Check for API keys in code
echo "🔍 Scanning for hardcoded credentials..."
FOUND_SECRETS=0

# Search for potential secrets (excluding node_modules and vendor)
if grep -r --exclude-dir={node_modules,vendor,storage,bootstrap/cache} -E "(api_key|API_KEY|password|PASSWORD|secret|SECRET).*=.*['\"][^'\"]{20,}" . 2>/dev/null; then
    echo -e "${RED}⚠️  Found potential hardcoded credentials!${NC}"
    echo "Please review and remove before pushing."
    FOUND_SECRETS=1
fi

if [ $FOUND_SECRETS -eq 1 ]; then
    read -p "Continue anyway? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Aborted. Please fix the issues first."
        exit 1
    fi
fi

# Git operations
echo "📤 Preparing Git commit..."

# Add all files
git add .

# Show status
echo -e "${YELLOW}Git Status:${NC}"
git status

# Commit message
echo ""
read -p "Enter commit message: " COMMIT_MSG

if [ -z "$COMMIT_MSG" ]; then
    COMMIT_MSG="Update Carpathian CMS - $(date +%Y-%m-%d)"
fi

git commit -m "$COMMIT_MSG" || echo "Nothing to commit"

# Push to GitHub
echo ""
read -p "Push to GitHub? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🚀 Pushing to GitHub..."
    git push origin main || git push origin master
    echo -e "${GREEN}✅ Successfully pushed to GitHub!${NC}"
else
    echo "Commit ready. Push manually when ready with: git push"
fi

echo ""
echo -e "${GREEN}✅ Done!${NC}"
echo ""
echo "📋 Summary:"
echo "  - .env.example sanitized"
echo "  - .gitignore updated"
echo "  - Database export sanitized (if exists)"
echo "  - Committed to Git"
echo ""
echo "🔗 Next steps:"
echo "  1. Visit: https://github.com/msrusu87-web/carpathian-cms"
echo "  2. Check if everything looks good"
echo "  3. Update README if needed"
echo ""
