<?php

// A2 : variables, concatenation et interpolation
$titre = "Nuit de l'Info"; // guillemets doubles : la valeur contient une apostrophe
$date = '14/03/2026';
$lieu = 'Amphi A';

echo 'Evenement : ' . $titre . ', le ' . $date . "\n"; // concatenation
echo "Evenement : $titre, le $date\n"; // interpolation
echo 'Evenement : $titre, le $date' . "\n"; // piege : guillemets simples n'interpretent pas les variables
