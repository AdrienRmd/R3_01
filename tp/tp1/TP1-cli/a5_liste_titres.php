<?php

// A5 : saisie de plusieurs titres puis tri alphabetique
$titres = [];
do {
    echo "Titre d'evenement (vide pour terminer) : ";
    $saisie = trim(fgets(STDIN));
    if ($saisie !== '') {
        $titres[] = $saisie; // ajout en fin de tableau
    }
} while ($saisie !== '');

// Tri en place puis affichage separe par des virgules
sort($titres);
echo "Liste triee : " . implode(', ', $titres) . "\n";
