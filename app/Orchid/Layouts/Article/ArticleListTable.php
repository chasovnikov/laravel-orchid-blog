<?php

namespace App\Orchid\Layouts\Article;

use App\Models\Article;
use App\Models\Category;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ArticleListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'articles';

    protected function striped(): bool
    {
        return true;
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('title', 'Заголовок'),
            TD::make('published', 'Видна?')->render(function (Article $article) {
                return $article->published ? 'Видна' : 'Скрыта';
            })/* ->width('80px') */->popover('Опубликована?')->sort(),
            TD::make('category_title', 'Категория')->render(
                function (Article $article) {
                    return Category::findOrfail($article->category_id)->title;
                }
            )->sort(),
            TD::make('created_at', 'Дата создания')->defaultHidden(),
            TD::make('updated_at', 'Дата обновления')->defaultHidden(),
            TD::make('Edit')->render(function (Article $article) {
                return ModalToggle::make('')
                    ->modal('editArticle')
                    ->method('createOrUpdateArticle')
                    ->modalTitle('Редактирование статьи ' . $article->title)
                    ->asyncParameters([
                        'article' => $article->id
                    ])->icon('note');
            })
        ];
    }
}