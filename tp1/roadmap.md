# MyEvents — Roadmap du module R3.01

> IUT de Calais — BUT2 — Développement Web PHP (R3.01)
> Projet fil rouge : **MyEvents**, une application de gestion d'événements construite et fait évoluer séance après séance.

## Vue d'ensemble

MyEvents est le projet qui accompagne tout le module : à chaque séance, une nouvelle brique s'ajoute à l'application. On démarre par l'installation de l'environnement (TP0), puis les fondamentaux du langage appliqués au projet (TP1). Ensuite, l'application gagne en structure (fonctions), en interactivité (formulaires), en état (sessions/cookies) et enfin en robustesse (sécurité).

| Séance | Thème (tiré du sujet) | Brique ajoutée à MyEvents |
|--------|----------------------|---------------------------|
| **TP0** | Installation de l'environnement | WAMP/Apache + PHP 8.x opérationnel, dossier `myevents` créé dans `www`, vérification par `phpinfo.php` (fichier **supprimé** après vérification) |
| **TP1** | Fondamentaux PHP appliqués à MyEvents | Scripts CLI d'entraînement (`TP1-cli/`) + première version web : `index.php` (accueil, liste, badges, stats), `event.php` statique, `data/events.php`, gabarits `inc/` |
| **TP2** | Fonctions & factorisation | Nouveau fichier `lib/functions.php` : `calculerBadge()`, `formaterDateAffichage()`, `calculerTauxRemplissage()`, `validerEvenement()`, `filtrerEvenementsFuturs()`, `trierEvenementsParDate()`, `statistiquesEvenements()`, `ajouterEvenement()` (par référence `&`), `rechercherEvenements()` ; bonus : récursivité, pagination, `static` |
| **TP3** | Formulaires, HTTP & validation | Formulaire d'ajout en POST sur `index.php`, affichage des erreurs, formulaire « sticky », catégories à cocher ; fiche `event.php?id=...` via `$_GET` ; nouvelle page `recherche.php` (GET) ; filtre par lieu ; bonus : Post/Redirect/Get, pagination, honeypot |
| **TP4** | Sessions & cookies | `lib/auth.php`, `login.php` / `logout.php`, protection du formulaire d'ajout, cookie « dernier lieu recherché », favoris en session, message de dernière visite ; bonus : `password_hash()`, expiration de session, « se souvenir de moi » |
| **TP5** | Sécurité : validation & XSS | Démonstration de la faille XSS puis correction par `htmlspecialchars()`, validation avec `filter_input()` (entier, e-mail, URL + schéma), audit complet de l'application, liste blanche des catégories, script d'auto-test en CLI ; bonus : rate limiting, jeton CSRF, Content-Security-Policy |

> **Au-delà du TP5** : le sujet annonce que les données, encore figées dans un fichier PHP, seront persistées dans une vraie base MySQL via PDO et les requêtes préparées (introduction au CM4).

---

## Roadmap détaillée du TP1

Objectifs de la séance : scripts PHP en ligne de commande (variables, saisie, chaînes, tableaux), fonctions natives `array_*`, puis transposition dans une vraie page web avec `foreach` et `if/elseif/else`.

### Partie A — Prise en main en ligne de commande (`TP1-cli/`)

- [x] **A1 — Premier script** (`a1_hello.php`) : `echo`, fin d'instruction `;`, `\n` en ligne de commande.
- [x] **A2 — Variables et concaténation** (`a2_fiche.php`) : variables `$titre`, `$date`, `$lieu` ; concaténation avec `.` vs interpolation en guillemets doubles ; le piège des guillemets simples.
- [x] **A3 — Saisie utilisateur et validation** (`a3_places.php`) : `fgets(STDIN)` + `trim()`, boucle `do...while`, validation `ctype_digit()` puis cast `(int)`.
- [x] **A4 — Chaînes de caractères** (`a4_titre.php`) : `mb_strlen()`, `mb_strtoupper()`, `ucwords()` — pourquoi les fonctions `mb_*` sont indispensables avec des accents.
- [x] **A5 — Tableaux indexés et tri** (`a5_liste_titres.php`) : remplissage avec `$titres[] = ...`, tri avec `sort()`, affichage avec `implode()`.
- [x] **A6 — Tableaux associatifs et fonctions `array_*`** (`a6_stats.php`) : `count()`, `array_sum()`, `array_column()`, `array_filter()` avec fonction fléchée `fn`.

### Partie B — Application web MyEvents (`myevents/`)

- [x] **B1 — Page d'accueil dynamique** (`index.php`) : message de bienvenue généré par PHP, date du jour avec `date('d/m/Y')`.
- [x] **B2 — La fiche d'un événement** (`event.php`) : variables, calcul des places restantes, affichage avec `<?= ?>`.
- [x] **B3 — Le tableau des événements** (`data/events.php` + `index.php`) : 6 événements codés en dur, inclusion avec `require`, compteur `count()`, parcours `foreach` en syntaxe alternative, cartes W3.CSS.
- [x] **B4 — Badge de disponibilité** (`index.php`) : `if/elseif/else` + ternaire, seuils Complet / Presque complet (≥ 80 %) / Places disponibles, garde contre la division par zéro.
- [x] **B5 — Statistiques de la page d'accueil** (`index.php`) : `array_sum`, `array_column`, `array_map`, `array_keys` + `max`, taux moyen arrondi avec `round()`.
- [x] **B6 — Tri et filtre par date (bonus)** (`index.php`) : `usort()` + `strtotime()`, filtre des événements futurs avec `array_filter()`, compteur d'événements passés masqués.

---

## Arborescence finale du TP1

```
R3_01/
|- TP1-cli/
|  |- a1_hello.php
|  |- a2_fiche.php
|  |- a3_places.php
|  |- a4_titre.php
|  |- a5_liste_titres.php
|  '- a6_stats.php
'- myevents/
   |- index.php
   |- event.php
   |- data/
   |  '- events.php
   '- inc/
      |- header.inc.php
      '- footer.inc.php
```

`TP1-cli/` contient les scripts d'entraînement en ligne de commande ; `myevents/` est le projet web qui évoluera jusqu'au TP5. Le fichier `phpinfo.php` du TP0 a été supprimé après la vérification, comme l'exige le sujet.

---

## État des lieux : ce qui est en place, ce qui manque (volontairement)

**En place et fonctionnel :**
- Les 6 scripts CLI de la partie A, tous testés ;
- une page d'accueil complète : accueil, statistiques, tri par date, filtre des événements passés, liste avec badges ;
- la séparation des rôles : pages / `data/` / `inc/`, déjà dans l'esprit de ce qui viendra.

**Volontairement absent (ce sera l'objet des TP suivants) :**
- `event.php` reste **statique** : ses variables sont écrites en dur dans le fichier. Au TP3, il deviendra une vraie fiche détaillée via `event.php?id=...` et `$_GET`.
- Les données sont **figées dans `data/events.php`** : aucun formulaire ne permet encore d'ajouter un événement (TP3), aucune base de données ne les persiste (annoncé pour le CM4 avec PDO/MySQL).
- Le calcul du badge est écrit **en ligne dans `index.php`** : dès qu'il sera aussi nécessaire dans `event.php`, il sera dupliqué — c'est précisément le problème de factorisation que résout le TP2 avec `lib/functions.php`.
- Aucune donnée utilisateur n'étant encore saisie, `htmlspecialchars()` n'a pas été utilisé : la protection arrive avec les formulaires (TP3) et l'audit sécurité (TP5).
- Le tri du bonus B6 (`usort`) modifie `$events` en place : le TP2 introduira `trierEvenementsParDate()`, qui trie une copie sans toucher à l'original.

---

## Et maintenant ?

1. **TP2 — Fonctions & factorisation** : extraire toute la logique (badge, format de date, taux, validation, tri, statistiques) dans `lib/functions.php`, incluse avec `require_once`, puis brancher `index.php` et `event.php` sur ces fonctions. Test clé : changer le seuil « presque complet » à un seul endroit et voir les deux pages suivre.
2. **TP3 — Formulaires, HTTP & validation** : formulaire d'ajout en POST, erreurs de validation, `event.php?id=...`, page `recherche.php` en GET.
3. **TP4 — Sessions & cookies** : connexion/déconnexion, protection de l'ajout, préférences mémorisées.
4. **TP5 — Sécurité** : XSS, `filter_input()`, audit et durcissement complet.
5. **Ensuite** : persistance des événements dans une base MySQL via PDO, avec requêtes préparées (anti-injection SQL).
