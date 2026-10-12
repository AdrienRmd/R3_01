<?php

// A4 : manipulation de chaines (fonctions multibyte pour les accents)
echo "Titre de l'evenement : ";
$titre = trim(fgets(STDIN));

echo "Nombre de caracteres : " . mb_strlen($titre) . "\n";
echo "En majuscules : " . mb_strtoupper($titre) . "\n";
echo "Premiere lettre en maj: " . ucwords($titre) . "\n";
