<?php
// TP2 - Partie A : fonctions de factorisation de MyEvents

/**
 * A1 - Calcule le badge de disponibilité d'un événement.
 * Retourne un tableau ['classe' => ..., 'label' => ...].
 */
function calculerBadge(array $event): array
{
    $taux = $event['places'] > 0
        ? $event['inscrits'] / $event['places']
        : 1;
    if ($event['inscrits'] >= $event['places']) {
        return ['classe' => 'w3-red', 'label' => 'Complet'];
    }
    if ($taux >= 0.8) {
        return ['classe' => 'w3-orange', 'label' => 'Presque complet'];
    }
    return ['classe' => 'w3-green', 'label' => 'Places disponibles'];
}

/**
 * A2 - Convertit une date ISO 'aaaa-mm-jj' au format français 'jj/mm/aaaa'.
 * Si la date est invalide, elle est retournée telle quelle.
 */
function formaterDateAffichage(string $dateIso): string
{
    $date = DateTime::createFromFormat('Y-m-d', $dateIso);

    return $date === false ? $dateIso : $date->format('d/m/Y');
}

/**
 * A3 - Calcule le taux de remplissage (inscrits / places).
 * Si places <= 0, renvoie 1.0 : valeur de repli cohérente avec un événement « complet ».
 */
function calculerTauxRemplissage(int $places, int $inscrits): float
{
    if ($places <= 0) {
        return 1.0;
    }

    return $inscrits / $places;
}

/**
 * A4 - Valide un événement et renvoie la liste des messages d'erreurs.
 * Tableau vide si tout est correct.
 */
function validerEvenement(array $event): array
{
    $erreurs = [];

    if (trim($event['titre']) === '') {
        $erreurs[] = 'Le titre est obligatoire.';
    }
    if ($event['places'] <= 0) {
        $erreurs[] = 'Le nombre de places doit être positif.';
    }
    if ($event['inscrits'] > $event['places']) {
        $erreurs[] = 'Le nombre d\'inscrits ne peut pas dépasser les places.';
    }

    return $erreurs;
}

/**
 * A5 - Renvoie les événements dont la date est aujourd'hui ou ultérieure.
 * (Les clés sont consécutives : ajout via $futurs[] dans une boucle.)
 */
function filtrerEvenementsFuturs(array $events): array
{
    $aujourdhui = date('Y-m-d');

    // On garde uniquement les événements d'aujourd'hui ou après
    $futurs = [];
    foreach ($events as $e) {
        if ($e['date'] >= $aujourdhui) {
            $futurs[] = $e;
        }
    }

    return $futurs;
}

/**
 * A6 - Renvoie une copie triée par date croissante, sans modifier l'original.
 */
function trierEvenementsParDate(array $events): array
{
    // On trie une copie pour ne pas modifier le tableau d'origine
    $copie = $events;

    // usort trie $copie en comparant les événements deux à deux.
    // Pour chaque paire ($a, $b), la fonction doit répondre :
    //   -1 si $a doit venir AVANT $b
    //    1 si $a doit venir APRÈS $b
    //    0 si c'est équivalent (même date)
    usort($copie, function ($a, $b) {
        $dateA = strtotime($a['date']);   // date de $a en nombre de secondes
        $dateB = strtotime($b['date']);   // date de $b en nombre de secondes

        if ($dateA < $dateB) {
            return -1;
        }
        if ($dateA > $dateB) {
            return 1;
        }
        return 0;
    });

    return $copie;
}

/**
 * B3 - Statistiques réutilisables sur une collection d'événements.
 * Parcours en une seule boucle : totaux et meilleur taux calculés ensemble.
 */
function statistiquesEvenements(array $events): array
{
    // Compteurs remplis au fur et à mesure de la boucle
    $totalPlaces = 0;
    $totalInscrits = 0;
    $plusFortTaux = -1;         // meilleur taux rencontré pour l'instant
    $titrePlusDemande = '';     // titre de l'événement ayant ce meilleur taux

    foreach ($events as $e) {
        $totalPlaces += $e['places'];
        $totalInscrits += $e['inscrits'];

        $taux = calculerTauxRemplissage($e['places'], $e['inscrits']);
        if ($taux > $plusFortTaux) {
            $plusFortTaux = $taux;
            $titrePlusDemande = $e['titre'];
        }
    }

    // Taux global en pourcentage, arrondi (0 s'il n'y a aucune place)
    if ($totalPlaces > 0) {
        $tauxGlobal = round($totalInscrits / $totalPlaces * 100);
    } else {
        $tauxGlobal = 0;
    }

    return [
        'nombre' => count($events),
        'totalPlaces' => $totalPlaces,
        'tauxGlobal' => $tauxGlobal,
        'evenementPlusDemande' => $titrePlusDemande,
    ];
}

/**
 * B4 - Valide puis ajoute un événement au tableau par référence.
 * Renvoie dans tous les cas le tableau d'erreurs (vide si ajouté).
 */
function ajouterEvenement(array &$events, array $nouvelEvenement): array
{
    $erreurs = validerEvenement($nouvelEvenement);
    if ($erreurs === []) {
        $events[] = $nouvelEvenement;
    }

    return $erreurs;
}

/**
 * B5 - Recherche multi-critères : mot-clé dans le titre (insensible à la casse)
 * et/ou lieu exact. Critères facultatifs et indépendants.
 */
function rechercherEvenements(array $events, ?string $motCle = null, ?string $lieu = null): array
{
    return array_values(array_filter(
        $events,
        fn($e) => ($motCle === null || mb_stripos($e['titre'], $motCle) !== false)
            && ($lieu === null || $e['lieu'] === $lieu)
    ));
}
