<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

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

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag_adding');
    }
}
