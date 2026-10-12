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

## Organisation des dossiers

```
R3_01/
|- projet/                     <- ressources du module (hors TPs)
'- tp/
   |- roadmap.md               <- ce fichier, à la racine de tp/ (couvre tout le module)
   |- tp1.md ... tp5.md        <- sujets des TP
   |- tp1/                     <- TP1 terminé : TP1-cli/, myevents/, explication.md
    '- tp2/                     <- TP2 en cours
       '- myevents/             <- copie indépendante de l'application
          '- lib/functions.php  <- Partie A écrite et testée
```

---

## Roadmap détaillée du TP1 — terminé

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

## Roadmap détaillée du TP2 — en cours

Objectif de la séance : extraire la logique dans `lib/functions.php` (Partie A), puis brancher les pages dessus et constater le gain de la factorisation (Partie B). L'application évolue dans une copie indépendante : `tp/tp2/myevents/`.

### Partie A — Écrire les fonctions de base (`lib/functions.php`) ✔

- [x] **A1 — Le badge, en fonction** (`calculerBadge()`) : seuils Complet / Presque complet (≥ 0.8) / Places disponibles, garde contre la division par zéro.
- [x] **A2 — Formater une date** (`formaterDateAffichage()`) : `DateTime::createFromFormat('Y-m-d', ...)` + ternaire de repli si la date est invalide.
- [x] **A3 — Taux de remplissage, sécurisé** (`calculerTauxRemplissage()`) : valeur de repli 1.0 si `places <= 0`.
- [x] **A4 — Valider un événement** (`validerEvenement()`) : titre, places, cohérence inscrits/places ; renvoie un tableau de messages d'erreurs.
- [x] **A5 — Filtrer les événements futurs** (`filtrerEvenementsFuturs()`) : `array_filter()` + fonction fléchée, `array_values()` pour renuméroter les clés.
- [x] **A6 — Trier sans modifier l'original** (`trierEvenementsParDate()`) : tri sur une copie, `usort()` + `strtotime()` + `<=>`.

> Partie A conforme au sujet, sans éléments superflus.

### Partie B — Intégrer et réutiliser

- [ ] **B1 — Brancher les fonctions dans `index.php`** : `require_once 'lib/functions.php'`, appels à `calculerBadge()`, `formaterDateAffichage()`, `filtrerEvenementsFuturs()` + `trierEvenementsParDate()`.
- [ ] **B2 — Brancher les fonctions dans `event.php`** : fin de la duplication ; test du seuil « presque complet » changé à un seul endroit.
- [ ] **B3 — Statistiques réutilisables** (`statistiquesEvenements()`) : nombre, totalPlaces, tauxGlobal, evenementPlusDemande en un seul appel.
- [ ] **B4 — Ajouter un événement par référence** (`ajouterEvenement(array &$events, ...)`) : valide, modifie `$events` via `&`, renvoie les erreurs.
- [ ] **B5 — Recherche multi-critères** (`rechercherEvenements()`) : mot-clé insensible à la casse + lieu exact, paramètres `?string` facultatifs.

### Bonus

- [ ] **C1 — Fonction récursive** (`sommeRecursive()`) : cas de base + appel récursif sur `array_slice()`.
- [ ] **C2 — Pagination** (`paginerEvenements()`) : `array_slice()` + `ceil()`, réutilisée au TP3.
- [ ] **C3 — Mémoriser un résultat (static)** : mémoïsation de `statistiquesEvenements()`.

---

## Arborescence actuelle

```
R3_01/
'- tp/
   |- roadmap.md
   |- tp1.md ... tp5.md
   |- tp1/
   |  |- explication.md
   |  |- TP1-cli/
   |  |  |- a1_hello.php
   |  |  |- a2_fiche.php
   |  |  |- a3_places.php
   |  |  |- a4_titre.php
   |  |  |- a5_liste_titres.php
   |  |  '- a6_stats.php
   |  '- myevents/
   |     |- index.php
   |     |- event.php
   |     |- data/
   |     |  '- events.php
   |     '- inc/
   |        |- header.inc.php
   |        '- footer.inc.php
   '- tp2/
      '- myevents/
         |- index.php
         |- event.php
         |- data/
         |  '- events.php
         |- lib/
         |  '- functions.php
         '- inc/
            |- header.inc.php
            '- footer.inc.php
```

`tp/tp1/` est le TP1 terminé (scripts CLI + première version web, figés). `tp/tp2/myevents/` en est la copie indépendante : c'est elle qui évoluera jusqu'au TP5. Le fichier `phpinfo.php` du TP0 a été supprimé après la vérification, comme l'exige le sujet.

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

**TP2 — en cours (dans `tp/tp2/`) :** Partie A terminée — les six fonctions de base vivent dans `tp/tp2/myevents/lib/functions.php` ; `index.php` et `event.php` y sont encore les versions TP1, non branchées. Restent la Partie B (branchement, statistiques, ajout par référence, recherche) et les bonus C1 à C3.

---

## Et maintenant ?

1. **TP2 — Partie B (prochaine étape)** : brancher les fonctions dans `tp/tp2/myevents/index.php` et `event.php` (`require_once 'lib/functions.php'`, appels à `calculerBadge()`, `formaterDateAffichage()`, `filtrerEvenementsFuturs()`, `trierEvenementsParDate()`), puis écrire `statistiquesEvenements()`, `ajouterEvenement()` (par référence) et `rechercherEvenements()`. Test clé : changer le seuil « presque complet » à un seul endroit et voir les deux pages suivre. Bonus C1 à C3 ensuite.
2. **TP3 — Formulaires, HTTP & validation** : formulaire d'ajout en POST, erreurs de validation, `event.php?id=...`, page `recherche.php` en GET.
3. **TP4 — Sessions & cookies** : connexion/déconnexion, protection de l'ajout, préférences mémorisées.
4. **TP5 — Sécurité** : XSS, `filter_input()`, audit et durcissement complet.
5. **Ensuite** : persistance des événements dans une base MySQL via PDO, avec requêtes préparées (anti-injection SQL).
