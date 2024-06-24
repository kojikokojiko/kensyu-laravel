<?php

namespace Database\Factories;

use App\Models\Thumbnail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThumbnailFactory extends Factory
{
    protected $model = Thumbnail::class;

    public function definition()
    {
        return [
            'path' => 'thumbnails/' . $this->faker->uuid . '.jpg',
        ];
    }
}
