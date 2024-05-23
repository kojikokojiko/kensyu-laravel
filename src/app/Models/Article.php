<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'body'];

    public function thumbnail()
    {
        return $this->hasOne(Thumbnail::class, 'article_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'article_id');
    }
}
