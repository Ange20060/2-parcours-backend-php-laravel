<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreArticleRequest;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Services\ArticleService;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Controllers\Controller;

class ArticleApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Article::with('user');

    if ($request->has('published')) {
        $query->where('published', $request->boolean('published'));
    }

    return ArticleResource::collection($query->latest()->paginate(15));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
{
    $article = $request->user()->articles()->create($request->validated());
    return new ArticleResource($article);
}

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
  {
    return view('articles.show', ['article' => $article]);
  }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
{
    if ($article->user_id !== $request->user()->id) {
        abort(403, "Action non autorisée.");
    }
    $article->update($request->validated());
    return new ArticleResource($article);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function publish(Article $article, ArticleService $service)
  {
    $service->publier($article);
    return back()->with('success', 'Article publié.');
  }
}
