<?php
require __DIR__ . '/inc/header.inc.php';
require __DIR__ . '/data/events.php';

// B5 : statistiques globales
$totalPlaces   = array_sum(array_column($events, 'places'));
$totalInscrits = array_sum(array_column($events, 'inscrits'));
$tauxMoyen     = $totalPlaces > 0 ? round($totalInscrits / $totalPlaces * 100) : 0;
$tauxParEvenement = array_map(fn($e) => $e['places'] > 0 ? $e['inscrits'] / $e['places'] : 0, $events);
$indexMax    = array_keys($tauxParEvenement, max($tauxParEvenement))[0];
$plusDemande = $events[$indexMax]['titre'];
?>

<?php
// B1 : accueil
echo "Bienvenue sur MyEvents !<br>";
echo "Nous sommes le " . date("d/m/Y");
?>

<p><?= count($events) ?> evenements, <?= $totalPlaces ?> places au total, taux de remplissage moyen : <?= $tauxMoyen ?>%</p>
<p>Evenement le plus demande : <?= $plusDemande ?></p>

<?php
// B6 : tri par date croissante puis masquage des événements passés
usort($events, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
$aVenir  = array_filter($events, fn($e) => strtotime($e['date']) >= strtotime('today'));
$masques = count($events) - count($aVenir);
?>
<p>Evenements passes masques : <?= $masques ?></p>

<p><?= count($aVenir) ?> evenement(s) au programme</p>

<?php foreach ($aVenir as $event): ?>
    <?php
    $taux = $event['places'] > 0 ? $event['inscrits'] / $event['places'] : 1;
    if ($event['inscrits'] >= $event['places']) {
        $classe = 'w3-red';
        $label  = 'Complet';
    } elseif ($taux >= 0.8) {
        $classe = 'w3-orange';
        $label  = 'Presque complet';
    } else {
        $classe = 'w3-green';
        $label  = 'Places disponibles';
    }
    ?>
    <div class="w3-card w3-margin-bottom w3-padding">
        <h3><?= $event['titre'] ?> <span class="w3-tag <?= $classe ?>"><?= $label ?></span></h3>
        <p><?= date('d/m/Y', strtotime($event['date'])) ?> - <?= $event['lieu'] ?></p>
    </div>
<?php endforeach; ?>

<?php
require __DIR__ . '/inc/footer.inc.php';
