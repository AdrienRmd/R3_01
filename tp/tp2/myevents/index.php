<?php
require 'inc/header.inc.php';
require 'data/events.php';
?>
<?php
echo "Bienvenue sur MyEvents !<br>";
echo "Nous sommes le " . date("d/m/Y");
?>
<?php
$totalPlaces = array_sum(array_column($events, 'places'));
$totalInscrits = array_sum(array_column($events, 'inscrits'));
$tauxMoyen = $totalPlaces > 0
    ? round($totalInscrits / $totalPlaces * 100)
    : 0;
$tauxParEvenement = array_map(
    fn($e) => $e['places'] > 0 ? $e['inscrits'] / $e['places'] : 0,
    $events
);
$indexMax = array_keys($tauxParEvenement, max($tauxParEvenement))[0];
$plusDemande = $events[$indexMax]['titre'];
?>
<p><?= count($events) ?> evenements, <?= $totalPlaces ?> places au total,
taux de remplissage moyen : <?= $tauxMoyen ?>%</p>
<p>Evenement le plus demande : <?= $plusDemande ?></p>
<?php
usort($events, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
$aVenir = array_filter($events, fn($e) => strtotime($e['date']) >= strtotime('today'));
$masques = count($events) - count($aVenir);
?>
<p>Evenements passes masques : <?= $masques ?></p>
<p><?= count($events) ?> evenement(s) au programme</p>
<?php foreach ($aVenir as $event):
$taux = $event['places'] > 0
    ? $event['inscrits'] / $event['places']
    : 1;
if ($event['inscrits'] >= $event['places']) {
    $classe = 'w3-red'; $label = 'Complet';
} elseif ($taux >= 0.8) {
    $classe = 'w3-orange'; $label = 'Presque complet';
} else {
    $classe = 'w3-green'; $label = 'Places disponibles';
}
?>
<div class="w3-card w3-margin-bottom w3-padding">
    <h3><?= $event['titre'] ?>
        <span class="w3-tag <?= $classe ?>"><?= $label ?></span>
    </h3>
    <p><?= $event['date'] ?> - <?= $event['lieu'] ?></p>
</div>
<?php endforeach; ?>
<?php require 'inc/footer.inc.php'; ?>
