<?php

use App\Models\Article;
use App\Models\User;
use App\Services\ArticleService;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('liste les articles', function () {
    Article::factory()->count(3)->create();

    getJson('/api/articles')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'title',
                    'content',
                ],
            ],
        ]);
});

it('rejette un article sans titre', function () {
    actingAs(User::factory()->create());

    postJson('/api/articles', [
        'content' => 'du contenu',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('title');
});

it('refuse la création à un invité', function () {
    postJson('/api/articles', [
        'title' => 'X',
        'content' => 'Y',
    ])
        ->assertStatus(401);
});

it('autorise un utilisateur authentifié', function () {
    actingAs(User::factory()->create());

    postJson('/api/articles', [
        'title' => 'X',
        'content' => 'Y',
    ])
        ->assertStatus(201);
});

it('est publiable si titre et contenu sont remplis', function () {
    $service = new ArticleService;

    expect(
        $service->estPubliable(
            new Article([
                'title' => 'T',
                'content' => 'C',
            ])
        )
    )->toBeTrue();

    expect(
        $service->estPubliable(
            new Article([
                'title' => '',
                'content' => 'C',
            ])
        )
    )->toBeFalse();
});
