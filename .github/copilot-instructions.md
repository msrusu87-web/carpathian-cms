# Copilot Instructions - Carphatian CMS Workspace

## CRITICAL: Production First
ALWAYS work in `/var/www/carphatian.ro/html/`, NOT in this folder.

## Testing Checklist (MANDATORY before delivering)
1. `php -l [file]` - Syntax check
2. `php artisan optimize:clear` - Clear all caches
3. `curl https://carphatian.ro/[page]` - Verify response
4. `tail -20 storage/logs/laravel.log` - Check for errors

## Quick Reference
- Currency: **lei/RON** (not $)
- Clusters: Marketing, Settings, Shop, CMS, Blog, Communications
- Use `$cluster = ...::class` not `$navigationGroup`
- Slug should NOT include cluster prefix when page is in a cluster
