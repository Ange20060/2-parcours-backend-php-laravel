# 📝 Niveau 5 — Exercices (PHP & le Web)

Ces exercices se lancent avec le serveur intégré : `php -S localhost:8000` puis on ouvre
`http://localhost:8000`. `declare(strict_types=1);` en tête. **🎯 But** indiqué à chaque fois.
Corrigés : [corriges.md](./corriges.md).

> 🎯 **Exigence sécurité** : **ne jamais faire confiance** à une entrée utilisateur. Toute
> donnée reçue est validée (Fail Fast) et tout affichage est échappé (XSS).

---

## Exercice 1 — Lire une requête GET 🌐
> 🎯 **But** : comprendre `$_GET` et la query string.

Crée `bonjour.php` qui lit un paramètre `?nom=Marie` et affiche « Bonjour Marie ». Si `nom`
est absent, affiche « Bonjour visiteur ». **Échappe** la sortie avec `htmlspecialchars`.
Teste : `http://localhost:8000/bonjour.php?nom=Marie`.

---

## Exercice 2 — Traiter un formulaire POST 📮
> 🎯 **But** : recevoir des données via `$_POST` et les valider.

Crée un formulaire HTML (méthode POST) demandant un nom et un email, et un script qui :
1. valide que les deux champs sont présents et que l'email est valide (`filter_input`) ;
2. réaffiche les valeurs **échappées** ;
3. affiche les erreurs de validation le cas échéant (Fail Fast côté serveur).

---

## Exercice 3 — Codes de statut HTTP 🔢
> 🎯 **But** : renvoyer les bons **codes HTTP** (Explicite).

Crée un script qui, selon `?id=` :
- renvoie `200` + un message si `id` existe (simule avec un tableau) ;
- renvoie `404` (via `http_response_code(404)`) si l'id est inconnu ;
- renvoie `400` si `id` est absent ou non numérique.

---

## Exercice 4 — Hachage de mot de passe 🔐
> 🎯 **But** : stocker un mot de passe **correctement** (jamais en clair !).

Écris deux fonctions : `creerHash(string $motDePasse): string` (`password_hash`) et
`verifier(string $motDePasse, string $hash): bool` (`password_verify`). Montre qu'un même mot
de passe produit **deux hachages différents** (le sel), mais que `verifier` renvoie `true` pour
les deux.
> ⚠️ Ne stocke **jamais** un mot de passe en clair ni avec `md5`/`sha1`.

---

## Exercice 5 — Sessions 🍪
> 🎯 **But** : maintenir un état entre les requêtes (base de l'authentification).

Crée un mini-compteur de visites par session : à chaque rechargement de la page, incrémente et
affiche `$_SESSION['visites']`. Ajoute un lien « réinitialiser » qui détruit la session.

---

## Exercice 6 — Se protéger du XSS 🛡️
> 🎯 **But** : comprendre et neutraliser une faille **XSS**.

Crée un « livre d'or » minimal : un champ où l'utilisateur écrit un message, affiché ensuite.
1. Montre d'abord (en commentaire) pourquoi afficher `$_POST['message']` **brut** est dangereux
   (ex : `<script>alert('xss')</script>`).
2. Corrige avec `htmlspecialchars`. Explique la différence.

---

## Exercice 7 — Une petite API JSON 🌟
> 🎯 **But** : renvoyer du **JSON** avec le bon `Content-Type` (préparation aux API Laravel).

Crée `api.php` qui :
1. renvoie l'en-tête `Content-Type: application/json` ;
2. selon `?action=liste`, renvoie un tableau de produits en JSON ;
3. renvoie une erreur JSON `{"erreur": "..."}` avec le statut `400` si l'action est inconnue.
Teste avec le navigateur ou `curl http://localhost:8000/api.php?action=liste`.

---

👉 Correction : [corriges.md](./corriges.md)
