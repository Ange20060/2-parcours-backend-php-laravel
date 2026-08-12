# 🟡 Niveau 3 — Programmation Orientée Objet (POO)

Le niveau **charnière** pour un futur développeur Laravel : la POO est **partout** dans le
framework. On apprend à modéliser le monde avec des **classes**, puis à les assembler
proprement (encapsulation, interfaces, composition) — en préparant le terrain pour **SOLID**.

> 🎯 **À la fin de ce niveau, tu sauras :**
> - Créer des **classes** et des **objets** (propriétés, méthodes, constructeur)
> - Appliquer l'**encapsulation** (`private`/`protected`/`public`, getters/setters)
> - Utiliser l'**héritage**, les **classes abstraites**, les **interfaces** et les **traits**
> - Organiser le code avec les **namespaces** et l'**autoloading**
> - Choisir entre **héritage et composition** à bon escient

---

## 📖 Les leçons (dans l'ordre)

1. [Classes et objets](./01-classes-objets.md)
2. [Encapsulation et visibilité](./02-encapsulation.md)
3. [Héritage et classes abstraites](./03-heritage-abstraites.md)
4. [Interfaces et polymorphisme](./04-interfaces-polymorphisme.md)
5. [Les traits](./05-traits.md)
6. [Enums et objets immuables](./06-enums-immutabilite.md)
7. [De la POO vers SOLID (composition & injection)](./07-vers-solid.md)

> ℹ️ Les **namespaces & l'autoloading PSR-4** ont été vus au [Niveau 2, leçon 7](../Niveau-2-PHP-Intermediaire/07-composer-psr.md).

Puis :
- 📝 [Les exercices](./exercices.md) — 8 exercices, chacun avec son **but précis**
- ✅ [Les corrigés](./corriges.md) (code PHP testé)

---

## 📐 Le pont vers les principes
Ce niveau débloque tout le domaine **[Principes du Génie Logiciel](../Principes-Genie-Logiciel/)** :
- **[SOLID](../Principes-Genie-Logiciel/11-SOLID.md)** (nécessite la POO)
- **[Composition > Héritage](../Principes-Genie-Logiciel/09-composition-vs-heritage.md)**
- **[Cohésion & couplage](../Principes-Genie-Logiciel/06-cohesion-couplage.md)** via l'injection de dépendances

> 💡 **Conseil** : lis (ou relis) le domaine Principes **juste après** ce niveau. Tout prendra son sens.

---

## ✅ Checklist de fin de niveau

- [ ] Je crée des classes avec propriétés/méthodes typées et un constructeur
- [ ] J'applique l'encapsulation (visibilité) à bon escient
- [ ] Je sais quand utiliser héritage, interface, ou trait
- [ ] Je comprends le polymorphisme (programmer contre une interface)
- [ ] J'organise mon code en namespaces avec autoloading PSR-4
- [ ] Je sais justifier un choix **composition vs héritage**

Tout coché 👉 [Niveau 4 : Principes du Génie Logiciel](../Principes-Genie-Logiciel/) puis
[Niveau 5 : PHP & le Web](../Niveau-5-PHP-Web/)
