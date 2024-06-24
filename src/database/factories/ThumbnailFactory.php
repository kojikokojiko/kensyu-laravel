<?php

declare (strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use App\Models\Thumbnail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThumbnailFactory extends Factory
{
    protected $model = Thumbnail::class;

    public function definition()
    {
        return [
            'path' => 'path/to/thumbnail.jpg',
            'article_id' => function() {
                return Article::factory()->create()->id;
            },
        ];
    }
}
