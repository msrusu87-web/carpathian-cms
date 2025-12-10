<?php

namespace App\Services;

use App\Models\SearchIndex;
use App\Models\Post;
use App\Models\Product;
use App\Models\Gig;

class SearchService
{
    public function search(string $query, int $limit = 20): array
    {
        return SearchIndex::whereRaw('MATCH(title, content) AGAINST(?)', [$query])
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function reindexAll(?string $type = null): int
    {
        $count = 0;

        if (!$type || $type === 'posts') {
            $count += $this->indexModel(Post::class, 'post');
        }

        if (!$type || $type === 'products') {
            $count += $this->indexModel(Product::class, 'product');
        }

        if (!$type || $type === 'gigs') {
            $count += $this->indexModel(Gig::class, 'gig');
        }

        return $count;
    }

    protected function indexModel(string $modelClass, string $type): int
    {
        if (!class_exists($modelClass)) {
            return 0;
        }

        $models = $modelClass::all();
        $count = 0;

        foreach ($models as $model) {
            $this->indexItem($model, $type);
            $count++;
        }

        return $count;
    }

    protected function indexItem($model, string $type): void
    {
        SearchIndex::updateOrCreate(
            [
                'indexable_type' => get_class($model),
                'indexable_id' => $model->id,
            ],
            [
                'title' => $model->title ?? $model->name ?? '',
                'content' => $model->content ?? $model->description ?? '',
                'type' => $type,
                'url' => $this->getModelUrl($model, $type),
            ]
        );
    }

    protected function getModelUrl($model, string $type): string
    {
        return match($type) {
            'post' => "/posts/{$model->slug}",
            'product' => "/products/{$model->slug}",
            'gig' => "/gigs/{$model->slug}",
            default => '#',
        };
    }
}
