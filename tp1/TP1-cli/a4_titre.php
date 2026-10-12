<?php

// A4 : manipulation de chaines (fonctions multibyte pour les accents)
echo "Titre de l'evenement : ";
$ligne = fgets(STDIN);
$titre = trim($ligne === false ? '' : $ligne); // fin de flux traitee comme chaine vide

echo "Nombre de caracteres : " . mb_strlen($titre) . "\n";
echo "En majuscules : " . mb_strtoupper($titre) . "\n";
echo "Premiere lettre en maj: " . ucwords($titre) . "\n";
