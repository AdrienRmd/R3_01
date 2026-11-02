# TP3 - Mes explications

## Partie A - Le formulaire d'ajout

### A1 - Le formulaire HTML

J'ai ajouté un formulaire `method="POST"` au-dessus de la liste des événements, avec les champs titre (texte), date (date), lieu (select avec Amphi A, Hall B et Foyer) et places (number avec `min="1"`). Le formulaire n'a pas d'attribut `action`, donc il envoie les données à la page elle-même. J'ai fait attention à utiliser exactement les names `titre`, `date`, `lieu` et `places`, ce sont les clés attendues par les fonctions du TP2.

### A2 - Distinguer affichage et traitement

En haut de `index.php`, avant tout affichage HTML, je teste `$_SERVER['REQUEST_METHOD'] === 'POST'` pour savoir si le formulaire a été envoyé. Je récupère chaque champ dans une variable (`trim()` sur le titre, cast en `(int)` pour les places), je construis le tableau `$nouvelEvenement`, puis j'appelle `ajouterEvenement($events, $nouvelEvenement)` du TP2. Cette fonction valide et ajoute l'événement, et renvoie le tableau d'erreurs : je n'ai donc aucune validation à réécrire dans la page.

### A3 - Afficher les erreurs

Juste au-dessus du formulaire, si `count($erreurs) > 0`, j'affiche une alerte `w3-panel w3-red` avec une liste `<ul>` : une erreur par ligne. Les messages viennent directement de `validerEvenement()` via `ajouterEvenement()`. Si le tableau est vide, rien ne s'affiche.

### A4 - Formulaire sticky

Si la validation échoue, je garde dans des variables (`$titreSaisi`, `$dateSaisie`, `$lieuSaisi`, `$placesSaisies`) ce que l'utilisateur avait tapé, et je les remets dans les attributs `value` du formulaire (et `selected` pour la bonne option du select). J'utilise `htmlspecialchars()` sur chaque valeur réaffichée, sinon un guillemet dans le titre casserait le formulaire (et c'est une faille XSS).

### A5 - Catégories à cocher

J'ai ajouté deux cases à cocher `Conference` et `Atelier` avec le même name `categories[]` : quand plusieurs cases portent un name terminé par `[]`, PHP les récupère dans un tableau. Côté PHP, `$categories = $_POST['categories'] ?? array();`, et je mets ce tableau dans `$nouvelEvenement['categories']`. J'ai aussi ajouté la clé `'categories' => array()` à chaque événement de `data/events.php` pour que tous les événements aient cette clé, même vide.

## Partie B - La fiche détail et la recherche

### B1 - event.php robuste

Je récupère `id` depuis `$_GET` et je vérifie avec `ctype_digit()` que c'est bien un entier avant de caster en `(int)` : comme ça, un id absent, non numérique ou inexistant (99) ne donne aucun warning PHP. Si `isset($events[$id])` est vrai, j'affiche la fiche avec `calculerBadge()` et `formaterDateAffichage()` du TP2, sinon j'affiche « Événement introuvable » dans une alerte `w3-panel w3-pale-red` avec un lien de retour vers `index.php`.

### B2 - Page de recherche

J'ai créé `recherche.php` avec un formulaire en GET (champ texte `q` + select `lieu` avec une option « Tous les lieux ») : une recherche est une consultation, pas une modification, donc GET et pas POST, et l'URL résultat peut être mise en favoris. Je lis `$_GET['q']` et `$_GET['lieu']`, je passe `null` pour un critère vide, puis j'appelle `rechercherEvenements()` du TP2. J'affiche le nombre de résultats et la liste, avec un message « Aucun resultat » si la recherche ne trouve rien.

### B3 - Filtre rapide sur index.php

J'ai ajouté sur `index.php` un petit formulaire GET avec un select `lieu_filtre`, en plus du formulaire d'ajout en POST : les deux coexistent sans se gêner car je lis `$_POST` une seule fois en haut de page pour l'ajout, et `$_GET['lieu_filtre']` à chaque affichage pour filtrer la liste avec `rechercherEvenements($aVenir, null, $lieuFiltre)`. Comportement choisi : après un ajout en POST, le filtre reste actif, car le formulaire POST n'a pas d'attribut `action` et renvoie donc vers l'URL courante, qui contient encore le paramètre GET `lieu_filtre`.

### B4 - Test de bout en bout

J'ai déroulé le scénario complet : titre vide (erreur affichée, champs conservés), places à 0 (rejeté), ajout valide (l'événement apparaît dans la liste avec son badge), filtre par lieu puis ajout d'un autre lieu (le filtre reste actif, comportement documenté en B3), consultation de `event.php` avec un id valide puis inexistant, et recherche sans résultat (message clair, aucune erreur PHP). Aucune anomalie constatée.
