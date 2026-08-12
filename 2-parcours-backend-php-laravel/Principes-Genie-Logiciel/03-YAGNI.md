# 3 — YAGNI (You Aren't Gonna Need It)

> 🎯 **Le principe** : ne construis **pas** de fonctionnalité ou d'abstraction pour un besoin
> **futur imaginaire**. Code ce dont tu as besoin **maintenant**.
>
> 💥 **Ce que ça tue** : les abstractions « flexibles » à moitié utilisées, les options que
> personne n'active, le code mort qui alourdit tout.

---

## Le problème (❌)

« Un jour on aura peut-être plusieurs devises, plusieurs langues, un cache, des plugins… »
→ on construit tout ça **avant** d'en avoir besoin.

```php
<?php
class Convertisseur
{
    public function __construct(
        private array $devises = ['EUR', 'USD', 'GBP', 'JPY', 'BTC'],
        private ?CacheInterface $cache = null,
        private array $plugins = [],
        private bool $modeExperimental = false,
    ) {}

    public function convertir(float $montant, string $de, string $vers, ?callable $arrondi = null): float
    {
        // 200 lignes pour gérer des cas qui n'existent pas encore...
    }
}
```

Résultat : du code compliqué, difficile à tester, pour un projet qui n'a besoin que
d'**euros** aujourd'hui.

---

## La solution (✅)

Réponds au besoin **réel**, simplement. Tu enrichiras **quand** le besoin arrivera vraiment.

```php
<?php
class Prix
{
    public function __construct(private float $montantEnEuros) {}

    public function ttc(): float
    {
        return $this->montantEnEuros * 1.20;
    }
}
```

Le jour où l'USD devient un **vrai** besoin (ticket, spécification), tu ajoutes ce qu'il faut
— avec la connaissance du **vrai** cas d'usage, pas d'un cas imaginé.

---

## 🧠 « Mais si j'en ai besoin plus tard ? »

- Un code **simple** est **facile à faire évoluer**. Un code sur-anticipé est **difficile à
  défaire**.
- 80 % des « on en aura besoin plus tard » n'arrivent **jamais** — ou arrivent **différemment**
  de ce que tu avais imaginé.
- Coûte moins cher d'**ajouter** au bon moment que de **maintenir** un truc inutile pendant des mois.

> 💬 « Implémente les choses quand tu en as réellement besoin, jamais quand tu prévois juste
> que tu en auras besoin. » — Ron Jeffries.

---

## 🔗 Liens avec les autres principes

- YAGNI est le **garde-fou** de **[KISS](./02-KISS.md)** contre la sur-ingénierie.
- Attention à ne pas confondre avec **[DRY](./01-DRY.md)** : factoriser une **vraie**
  duplication n'est pas violer YAGNI ; créer une abstraction « au cas où », si.

---

## 🏋️ Mini-exercice

On te demande une fonction qui envoie un email de bienvenue. Un collègue propose d'y ajouter
tout de suite : le support SMS, un système de templates multi-langues, et une file d'attente.
**Le besoin actuel est : envoyer un email en français.**

➡️ Écris la version **YAGNI-compatible**, et explique en 2 phrases pourquoi tu refuses (pour
l'instant) les ajouts. Corrigé dans [corriges.md](./corriges.md).
