<?php

// A6 : tableau associatif et fonctions array_*
$events = [
    ['titre' => "Nuit de l'Info", 'places' => 40, 'inscrits' => 40], // complet
    ['titre' => 'Forum des metiers', 'places' => 120, 'inscrits' => 65],
    ['titre' => 'Hackathon IUT', 'places' => 60, 'inscrits' => 60], // complet
];

// Statistiques globales
$totalPlaces = array_sum(array_column($events, 'places'));
echo "Nombre d'evenements : " . count($events) . "\n";
echo "Total des places : $totalPlaces\n";

// Filtrage des evenements complets (inscrits >= places)
$complets = array_filter($events, fn($e) => $e['inscrits'] >= $e['places']);
echo "Evenements complets : " . implode(', ', array_column($complets, 'titre')) . "\n";
