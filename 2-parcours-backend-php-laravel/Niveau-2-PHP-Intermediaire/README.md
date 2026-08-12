# 🟢 Niveau 2 — PHP intermédiaire

On monte en puissance sur le langage : **tableaux** (l'outil le plus utilisé du backend),
manipulation de **chaînes**, **gestion d'erreurs par exceptions**, **fichiers**, **dates**, et
les standards de la communauté (**PSR**, autoloading via Composer).

> 🎯 **À la fin de ce niveau, tu sauras :**
> - Manipuler tableaux indexés et associatifs, et les fonctions `array_*` (`map`, `filter`, `reduce`)
> - Gérer les erreurs avec `try/catch`, `throw`, et créer des exceptions personnalisées
> - Lire/écrire des fichiers et du **JSON**
> - Respecter les standards **PSR-1/PSR-12** et utiliser l'**autoloading PSR-4** de Composer

---

## 📖 Les leçons (dans l'ordre)

1. [Les tableaux (indexés & associatifs)](./01-tableaux.md)
2. [Les fonctions de tableaux (`map`/`filter`/`reduce`, `usort`)](./02-fonctions-tableaux.md)
3. [Les chaînes de caractères](./03-chaines.md)
4. [La gestion d'erreurs : les exceptions](./04-exceptions.md)
5. [Fichiers et JSON](./05-fichiers-json.md)
6. [Les dates (`DateTimeImmutable`)](./06-dates.md)
7. [Composer, autoloading & les standards PSR](./07-composer-psr.md)

Puis :
- 📝 [Les exercices](./exercices.md) — 9 exercices, chacun avec son **but précis**
- ✅ [Les corrigés](./corriges.md) (code PHP testé)

---

## 📐 Principes mis en avant à ce niveau
- **[DRY](../Principes-Genie-Logiciel/01-DRY.md)** avec les fonctions de tableaux (éviter les boucles répétitives)
- **[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)** avec les exceptions
- **[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** avec la configuration et le JSON

---

## ✅ Checklist de fin de niveau

- [ ] Je manipule tableaux indexés/associatifs et je les parcours proprement
- [ ] J'utilise `array_map/filter/reduce` au lieu de boucles manuelles quand c'est plus clair
- [ ] Je gère les erreurs avec des **exceptions** (et j'en crée des personnalisées)
- [ ] Je lis/écris du **JSON** et des fichiers
- [ ] Je comprends l'**autoloading PSR-4** de Composer et je respecte **PSR-12**

Tout coché 👉 [Niveau 3 : Programmation Orientée Objet](../Niveau-3-POO/)
