<?php
require 'inc/header.inc.php';
require_once 'lib/functions.php';

$event = [
    'titre' => "Nuit de l'Info",
    'date' => '2026-12-03',
    'lieu' => 'Amphi A',
    'places' => 40,
    'inscrits' => 34,
];
$badge = calculerBadge($event);
?>
<h2><?= $event['titre'] ?>
    <span class="w3-tag <?= $badge['classe'] ?>"><?= $badge['label'] ?></span>
</h2>
<p>Le <?= formaterDateAffichage($event['date']) ?> à <?= $event['lieu'] ?></p>
<p>Places restantes : <?= $event['places'] - $event['inscrits'] ?> / <?= $event['places'] ?></p>
<?php require 'inc/footer.inc.php'; ?>
