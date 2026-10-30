<?php
require 'inc/header.inc.php';
require 'data/events.php';
require_once 'lib/functions.php';

$aVenir = trierEvenementsParDate(filtrerEvenementsFuturs($events));
$masques = count($events) - count($aVenir);
$stats = statistiquesEvenements($events);
?>
<?php
echo "Bienvenue sur MyEvents !<br>";
echo "Nous sommes le " . date("d/m/Y");
?>
<p><?= $stats['nombre'] ?> evenements, <?= $stats['totalPlaces'] ?> places au total,
taux de remplissage moyen : <?= $stats['tauxGlobal'] ?>%</p>
<p>Evenement le plus demande : <?= $stats['evenementPlusDemande'] ?></p>
<p>Evenements passes masques : <?= $masques ?></p>
<p><?= count($aVenir) ?> evenement(s) au programme</p>
<?php foreach ($aVenir as $event):
$badge = calculerBadge($event);
?>
<div class="w3-card w3-margin-bottom w3-padding">
    <h3><?= $event['titre'] ?>
        <span class="w3-tag <?= $badge['classe'] ?>"><?= $badge['label'] ?></span>
    </h3>
    <p><?= formaterDateAffichage($event['date']) ?> - <?= $event['lieu'] ?></p>
</div>
<?php endforeach; ?>
<?php require 'inc/footer.inc.php'; ?>
