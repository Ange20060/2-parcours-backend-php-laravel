<?php
// app/Services/ArticleService.php
namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function publier(Article $article): void
    {
        $article->update(['published' => true]);
        // ... autre logique métier : notifier des abonnés, journaliser, etc.
    }
}
