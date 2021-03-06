<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

class Tag extends Model
{
    use HasFactory;
    use AsSource;
    use SoftDeletes;

    /**
     * Определяет необходимость отметок времени для модели.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Атрибуты, для которых запрещено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'popular',
        'active',
    ];

    /**
     * Статьи, принадлежащие тегу.
     */
    public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'article_tags',
            // 'tag_id',
            // 'article_id'
        );
    }

    public function article_tags()
    {
        return $this->hasMany(ArticleTag::class, 'tag_id');
    }

    /**
     * Возращает список всех тегов
     *
     * @return Tag[] | Collection
     */
    public function scopeArticlePublished($query)
    {
        return $query->addSelect('id', 'title', 'active')
            ->whereHas('articles', function (Builder $builder) {
                $builder = Article::published($builder);
            })->where('active', true);
    }
}
