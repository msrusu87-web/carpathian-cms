<?php
namespace App\Services;

use Spatie\SchemaOrg\Schema;

class SchemaMarkupService
{
    public function generateArticleSchema($post): array
    {
        return Schema::article()->headline($post->title)->toArray();
    }
}
