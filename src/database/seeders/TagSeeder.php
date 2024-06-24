<?php
declare (strict_types = 1);
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            '総合', 'テクノロジー', 'モバイル', 'アプリ', 'エンタメ', 'ビューティー', 'ファッション', 'ライフスタイル', 'ビジネス', 'グルメ', 'スポーツ'
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
