# 🟡 Niveau 5 — PHP & le Web (HTTP)

Comprendre ce qu'est **vraiment** le backend : recevoir une **requête HTTP**, la traiter,
renvoyer une **réponse**. On voit les fondations que Laravel automatisera ensuite.

> 🎯 **Objectifs :** le cycle requête/réponse HTTP · `$_GET`/`$_POST` et les formulaires ·
> sessions & cookies · en-têtes et codes de statut · bases de la **sécurité** (injection, XSS,
> CSRF, hachage de mot de passe avec `password_hash`).

## 📖 Les leçons (dans l'ordre)
1. [Le protocole HTTP](./01-http.md)
2. [Recevoir des données (GET / POST)](./02-donnees-get-post.md)
3. [Sessions et cookies](./03-sessions-cookies.md)
4. [Sécurité de base (XSS, injection SQL, CSRF, mots de passe)](./04-securite.md)
5. [Retourner du JSON (ta première API)](./05-api-json.md)

Puis :
- 📝 [Les exercices](./exercices.md) — 7 exercices, chacun avec son **but précis** · ✅ [Corrigés](./corriges.md)

## 📐 Principes clés
**[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)** (valider toute entrée utilisateur) ·
**[Explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)** (codes de statut clairs).

👉 Suite : [Niveau 6 : Bases de données & SQL](../Niveau-6-BDD-SQL/)
