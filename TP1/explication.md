# MyEvents expliqué — TP1 du module R3.01

> Ce document explique **comment fonctionne** le projet réalisé au TP1 (voir `roadmap.md` pour le plan de progression du module).

## Qu'est-ce que MyEvents ?

MyEvents est une petite application web de gestion d'événements (forums, conférences, hackathons...) qui sert de **projet fil rouge** au module R3.01. Plutôt que d'apprendre PHP par des exemples isolés, chaque notion est immédiatement appliquée au même projet, qui grossit séance après séance.

L'objectif pédagogique du TP1 est double :
1. **Maîtriser les fondamentaux du langage** : variables, chaînes, tableaux, structures de contrôle, fonctions natives `array_*` — d'abord en ligne de commande, dans des scripts courts et ciblés.
2. **Les réutiliser dans une vraie page web** : la page d'accueil de MyEvents génère du HTML à partir de données, avec `foreach`, `if/elseif/else` et `require`.

## Les deux façons d'exécuter PHP

| Mode | Comment | Rôle dans ce TP |
|------|---------|-----------------|
| **CLI (ligne de commande)** | `php TP1-cli/a3_places.php` dans un terminal | Partie A : travailler le langage « à nu », sans navigateur ni HTML. Idéal pour répéter saisie, validation, tris. |
| **Web (Apache)** | Le navigateur demande `index.php` à Apache/WAMP, qui exécute le script et renvoie le HTML généré | Partie B : le code PHP produit une page que le navigateur affiche. C'est le mode du projet final. |

Le même langage, les mêmes fonctions — seule la sortie change : du texte brut dans le terminal, du HTML dans le navigateur.

## Structure des dossiers et rôle de chaque fichier

```
myevents/
|- index.php          Page d'accueil : accueil + statistiques + liste d'événements avec badges
|- event.php          Fiche détaillée d'un événement (encore statique)
|- data/
|  '- events.php      Le tableau $events : les données de l'application
'- inc/
   |- header.inc.php  Début commun des pages : <!DOCTYPE html>, <head>, W3.CSS, barre de navigation
   '- footer.inc.php  Fin commune : pied de page, </body></html>
```

**`data/events.php` — les données.** Chaque événement est un tableau associatif (`titre`, `date`, `lieu`, `places`, `inscrits`). Choix important : les dates y sont stockées **au format ISO `YYYY-MM-DD`**, car c'est le format que `strtotime()` interprète de façon fiable pour le tri (B6) et le filtrage ; le format français `jj/mm/aaaa` n'est utilisé qu'à l'affichage :

```php
'titre'    => 'Conférence cybersécurité',
'date'     => '2026-10-12',
```

**`inc/header.inc.php` et `inc/footer.inc.php` — le gabarit commun.** Toutes les pages commencent et finissent pareil : plutôt que de recopier le HTML, chaque page fait `require __DIR__ . '/inc/header.inc.php';` puis `require __DIR__ . '/inc/footer.inc.php';`. Le header charge le framework CSS W3.CSS et affiche la barre de navigation.

**`index.php` — la page qui regroupe B1, B3, B4, B5 et B6.** C'est elle qui calcule les statistiques (`array_sum`, `array_map`, `max`...), trie par date, filtre les événements passés, puis parcourt le résultat avec `foreach` pour afficher une carte par événement, chacune ornée d'un **badge calculé** (Complet / Presque complet / Places disponibles).

**`event.php` — la fiche (B2).** Pour l'instant ses données sont en dur dans le fichier ; au TP3, elle deviendra `event.php?id=...` et ira chercher l'événement dans `$events`.

## Les notions PHP du TP1, expliquées simplement

### Variables et `echo`

Une variable PHP commence par `$`. `echo` envoie une valeur vers la sortie (terminal en CLI, page en Web) :

```php
$titre = "Nuit de l'Info";
echo "Bienvenue dans MyEvents !\n";
```

### Concaténation vs interpolation

Deux façons de combiner variables et texte, vues côte à côte dans `a2_fiche.php` :

```php
echo 'Evenement : ' . $titre . ', le ' . $date . "\n"; // concaténation avec .
echo "Evenement : $titre, le $date\n";                 // interpolation
```

### Guillemets simples vs doubles — le piège de A2

Les guillemets **simples** n'interprètent jamais les variables ; les guillemets **doubles**, oui. La troisième ligne de `a2_fiche.php` le démontre :

```php
echo 'Evenement : $titre, le $date' . "\n"; // affiche littéralement $titre !
```

### Saisie clavier : `fgets(STDIN)` + `trim()`

En CLI, `fgets(STDIN)` lit une ligne au clavier, **retour à la ligne inclus** — d'où le `trim()` qui le retire :

```php
$saisie = trim(fgets(STDIN));
```

### Validation : `ctype_digit()` + cast `(int)`

`ctype_digit()` vérifie que la chaîne ne contient que des chiffres ; seulement ensuite on la convertit en entier avec `(int)` (le cast d'une chaîne non numérique donnerait `0` silencieusement) :

```php
$valide = ctype_digit($saisie) && (int) $saisie > 0;
```

### `mb_strlen()` vs `strlen()`

En UTF-8, un caractère accentué occupe 2 octets : `strlen()` compte des **octets** et donne un résultat faux sur « Nuit de l'Info à l'IUT ». Les fonctions `mb_*` (multibyte) comptent des **caractères** :

```php
echo "Nombre de caracteres : " . mb_strlen($titre) . "\n";
```

### Tableaux indexés et associatifs

Un tableau indexé numérote ses éléments (`$titres[0]`, `$titres[1]`...) ; un tableau associatif associe une **clé** à chaque valeur — c'est la structure de chaque événement de `data/events.php` (`['titre' => ..., 'places' => ...]`).

### Ajouter un élément : `$tab[] = ...`

`$titres[] = $saisie;` ajoute à la fin du tableau, sans se soucier de l'index — utilisé dans `a5_liste_titres.php` pour accumuler les titres saisis.

### `sort()` et `usort()`

`sort($titres)` trie un tableau **en place** (il modifie `$titres` directement). `usort()` trie avec une fonction de comparaison personnalisée — ici, par date convertie en horodatage :

```php
usort($events, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
```

### `implode()`

Assemble les éléments d'un tableau en une chaîne, séparés par un délimiteur :

```php
echo "Liste triee : " . implode(', ', $titres) . "\n";
```

### Les fonctions `array_*` (vues dans `a6_stats.php` et `index.php`)

- `count($events)` : nombre d'éléments ;
- `array_column($events, 'places')` : extrait la colonne `places` de tous les événements ;
- `array_sum(...)` : total de places ;
- `array_filter($events, fn($e) => ...)` : ne garde que les éléments qui valident la condition (ex. événements complets, événements à venir) ;
- `array_map(fn($e) => ..., $events)` : applique un calcul à chaque élément (ex. un taux de remplissage par événement).

```php
$totalPlaces = array_sum(array_column($events, 'places'));
$complets    = array_filter($events, fn($e) => $e['inscrits'] >= $e['places']);
```

### `foreach` avec la syntaxe alternative

Dans une page web, on alterne PHP et HTML. La syntaxe alternative `: ... endforeach;` est plus lisible que les accolades au milieu du HTML :

```php
<?php foreach ($aVenir as $event): ?>
    <h3><?= $event['titre'] ?></h3>
<?php endforeach; ?>
```

`<?= $x ?>` est l'équivalent court de `<?php echo $x; ?>`.

### `if/elseif/else` et ternaire

Le badge de B4 est un `if/elseif/else` classique ; la condition « sinon » remplit la carte. L'opérateur **ternaire** `condition ? valeurSiVrai : valeurSiFaux` compacte un if/else en une expression — utilisé pour le taux et la garde anti-division-par-zéro :

```php
$tauxMoyen = $totalPlaces > 0 ? round($totalInscrits / $totalPlaces * 100) : 0;
```

### `require` et l'organisation `inc/`

`require` inclut et exécute un autre fichier PHP : les données (`data/events.php`) et les gabarits (`inc/header.inc.php`, `inc/footer.inc.php`) sont ainsi centralisés. Si le fichier manque, `require` interrompt le script (contrairement à `include` qui ne fait qu'un warning).

### `date()` et `strtotime()`

`date('d/m/Y')` donne la date du jour au format français. `strtotime()` convertit une date texte en horodatage Unix ; elle comprend le format ISO `YYYY-MM-DD`, d'où le choix fait dans `data/events.php`. Combinés, ils reformattent une date stockée :

```php
date('d/m/Y', strtotime($event['date']))
```

### La garde contre la division par zéro

Diviser par zéro est une erreur. Avant chaque division par `places`, on vérifie qu'il est strictement positif et on prévoit une valeur de repli (un événement sans place est traité comme complet) :

```php
$taux = $event['places'] > 0 ? $event['inscrits'] / $event['places'] : 1;
```

## Comment lancer le projet

### En ligne de commande (Partie A)

Depuis le répertoire de travail `R3_01` :

```
php TP1-cli/a1_hello.php
```

Exemples de saisies pour les scripts interactifs :

- `php TP1-cli/a3_places.php` — saisir `toto` puis `-5` (refusés), puis `40` → `Nombre de places enregistre : 40` ;
- `php TP1-cli/a4_titre.php` — saisir par ex. `Nuit de l'Info à l'IUT` → nombre de caractères (26), majuscules, initiales ;
- `php TP1-cli/a5_liste_titres.php` — saisir `Hackathon IUT`, `Forum des metiers`, `Nuit de l'Info`, puis une ligne **vide** pour terminer → liste triée et jointe par des virgules.

### En web (Partie B)

**Option 1 — WAMP (Apache) :** copier le dossier `myevents` dans `C:\wamp64\www\`, puis ouvrir :

```
http://localhost/myevents/
```

**Option 2 — serveur intégré :** depuis le dossier `myevents` :

```
php -S localhost:8000
```

puis ouvrir `http://localhost:8000/index.php` (et `http://localhost:8000/event.php` pour la fiche).

## Les pièges fréquents du TP (à retenir)

1. **Parse error** : un `;` manquant en fin d'instruction. Lire le message d'erreur et la ligne indiquée **avant** de modifier le code.
2. **Guillemets simples** : `'$titre'` affiche littéralement `$titre` — aucune interpolation entre guillemets simples.
3. **`strlen` vs `mb_strlen`** : sur du texte accentué UTF-8, `strlen()` compte des octets et renvoie un nombre trop grand → toujours `mb_*`.
4. **Division par zéro** : tester `places > 0` avant de calculer un taux.
5. **Clés de tableau inexistantes** : `$events[0]['organisateur']` sur une clé absente déclenche `Warning: Undefined array key`. Ne pas confondre `$events[0]` (un événement par son index) et `$events[0]['titre']` (une info de cet événement, par sa clé).
6. **`$_POST` renvoie des chaînes** : une valeur de formulaire arrive toujours comme une chaîne (`"40"`, pas `40`), ce qui peut fausser une comparaison stricte `===`. À retenir pour le CM2/TP3.
7. **`phpinfo()`** : le fichier de vérification du TP0 révèle des informations sensibles sur le serveur — il doit être **supprimé** dès la vérification terminée. Premier réflexe de sécurité du module.
