<?php
namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function publier(Article $article): void
    {
        $article->update(['published' => true]);
    }
    public function estPubliable(Article $article): bool
    {
        return !empty($article->title)
            && !empty($article->content);
    }
}
