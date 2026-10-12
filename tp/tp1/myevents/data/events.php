<?php
// Données des événements de MyEvents.
// Les dates sont stockées au format ISO 'YYYY-MM-DD' : ce format permet
// à strtotime() de les interpréter correctement pour le tri et le filtrage.
// L'affichage, lui, se fait au format français jj/mm/aaaa.
$events = [
    [
        'titre'    => 'Forum des associations',
        'date'     => '2026-09-05',
        'lieu'     => 'Hall A',
        'places'   => 120,
        'inscrits' => 98,
    ],
    [
        'titre'    => 'Soirée d\'intégration',
        'date'     => '2026-09-26',
        'lieu'     => 'Foyer',
        'places'   => 80,
        'inscrits' => 80, // complet, passé
    ],
    [
        'titre'    => 'Conférence cybersécurité',
        'date'     => '2026-10-12',
        'lieu'     => 'Amphi B',
        'places'   => 60,
        'inscrits' => 45,
    ],
    [
        'titre'    => 'Hackathon IUT',
        'date'     => '2026-11-07',
        'lieu'     => 'Salle machine',
        'places'   => 60,
        'inscrits' => 60, // complet
    ],
    [
        'titre'    => 'Forum des métiers',
        'date'     => '2026-11-21',
        'lieu'     => 'Gymnase',
        'places'   => 120,
        'inscrits' => 65,
    ],
    [
        'titre'    => 'Nuit de l\'Info',
        'date'     => '2026-12-03',
        'lieu'     => 'Amphi A',
        'places'   => 40,
        'inscrits' => 38, // 95 %, presque complet
    ],
];
