<?php

declare(strict_types=1);

/**
 * Script CLI de découverte — SkillSwap (séance S1, étape 1.1).
 *
 * Exécution en ligne de commande :
 *   php bin/a1_bienvenue.php
 */

$application = 'SkillSwap';
$objectif = 'identifier qui sait quoi, apprendre les uns des autres';

echo 'Bienvenue sur ' . $application . ' !' . PHP_EOL;
echo 'Objectif : ' . $objectif . '.' . PHP_EOL;
echo PHP_EOL;
echo 'Nous sommes le ' . date('d/m/Y') . ', il est ' . date('H:i') . '.' . PHP_EOL;
echo 'Ce script tourne sous PHP ' . PHP_VERSION . ' (' . PHP_OS . ').' . PHP_EOL;
