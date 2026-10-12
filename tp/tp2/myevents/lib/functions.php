<?php
// TP2 - Partie A : fonctions de factorisation de MyEvents.
// Chaque fonction est pure (aucun echo) : elle reçoit des données,
// renvoie un résultat, et laisse l'affichage aux pages.

/**
 * Calcule le badge de disponibilité d'un événement.
 * Retourne un tableau ['classe' => ..., 'label' => ...].
 */
function calculerBadge(array $event): array
{
    // Taux de remplissage ; garde contre la division par zéro
    $taux = $event['places'] > 0 ? $event['inscrits'] / $event['places'] : 1;

    if ($event['inscrits'] >= $event['places']) {
        return ['classe' => 'w3-red', 'label' => 'Complet'];
    }
    if ($taux >= 0.8) {
        return ['classe' => 'w3-orange', 'label' => 'Presque complet'];
    }
    return ['classe' => 'w3-green', 'label' => 'Places disponibles'];
}

/**
 * Convertit une date ISO 'aaaa-mm-jj' au format français 'jj/mm/aaaa'.
 * Si la date est invalide, elle est retournée telle quelle.
 */
function formaterDateAffichage(string $dateIso): string
{
    $date = DateTime::createFromFormat('Y-m-d', $dateIso);

    return $date === false ? $dateIso : $date->format('d/m/Y');
}

/**
 * Calcule le taux de remplissage (inscrits / places).
 * Si places <= 0, renvoie 1.0 : valeur de repli cohérente avec « complet ».
 */
function calculerTauxRemplissage(int $places, int $inscrits): float
{
    if ($places <= 0) {
        return 1.0;
    }

    return $inscrits / $places;
}

/**
 * Valide un événement et renvoie la liste des messages d'erreurs.
 * Tableau vide si tout est correct.
 */
function validerEvenement(array $event): array
{
    $erreurs = [];

    // Titre vide ou réduit à des espaces
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
 * Renvoie les événements dont la date est aujourd'hui ou ultérieure.
 * array_values() renumérote les clés consécutivement.
 */
function filtrerEvenementsFuturs(array $events): array
{
    $aujourdhui = date('Y-m-d');

    return array_values(array_filter($events, fn ($e) => $e['date'] >= $aujourdhui));
}

/**
 * Renvoie les événements triés par date croissante.
 * On trie une copie : le tableau passé en paramètre n'est pas modifié
 * (usort trie en place, d'où la copie préalable pour éviter tout effet de bord).
 */
function trierEvenementsParDate(array $events): array
{
    $copie = $events;
    usort($copie, fn ($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));

    return $copie;
}
