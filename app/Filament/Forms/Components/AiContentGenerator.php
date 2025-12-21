<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Actions\Action;

class AiContentGenerator extends Field
{
    protected string $view = 'filament.forms.components.ai-content-generator';

    protected array $targetFields = [];
    protected string $contentType = 'product';
    
    public function targetFields(array $fields): static
    {
        $this->targetFields = $fields;
        return $this;
    }

    public function contentType(string $type): static
    {
        $this->contentType = $type;
        return $this;
    }

    public function getTargetFields(): array
    {
        return $this->targetFields;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }
}
