<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureArticleOwner
{
  /**
   * Handle an incoming request.
   *
   * @param  Closure(Request): (Response)  $next
   */

  public function handle(Request $request, Closure $next): Response
  {
    $article = $request->route('article');
    if ($article->user_id !== $request->user()->id) {
      abort(403, "Vous n'êtes pas l'auteur de cet article.");   // Fail Fast
    }
    return $next($request);
  }
}
