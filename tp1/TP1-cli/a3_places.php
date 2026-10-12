<?php

// A3 : saisie validee au clavier (entier positif obligatoire)
do {
    echo "Nombre de places disponibles : ";
    $saisie = trim(fgets(STDIN));
    $valide = ctype_digit($saisie) && (int) $saisie > 0;
    if (!$valide) {
        echo "Merci de saisir un entier positif.\n";
    }
} while (!$valide);

// Conversion en entier puis confirmation
$places = (int) $saisie;
echo "Nombre de places enregistre : $places\n";
