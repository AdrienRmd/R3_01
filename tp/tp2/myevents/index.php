<?php
// Page d'accueil : regroupe les exercices B1, B3, B4, B5 et B6 (bonus)
require __DIR__ . '/inc/header.inc.php';
require __DIR__ . '/data/events.php';
?>

<?php
// B5 : statistiques globales sur l'ensemble des événements
$totalPlaces   = array_sum(array_column($events, 'places'));
$totalInscrits = array_sum(array_column($events, 'inscrits'));
// Taux de remplissage moyen (en %), avec garde contre la division par zéro
$tauxMoyen = $totalPlaces > 0 ? round($totalInscrits / $totalPlaces * 100) : 0;
// Taux de remplissage de chaque événement
$tauxParEvenement = array_map(fn($e) => $e['places'] > 0 ? $e['inscrits'] / $e['places'] : 0, $events);
// Index de l'événement au taux maximal (le plus demandé)
$indexMax    = array_keys($tauxParEvenement, max($tauxParEvenement))[0];
$plusDemande = $events[$indexMax]['titre'];
?>

<!-- B1 : bandeau d'accueil -->
<header class="w3-container w3-blue-grey">
    <h1>
        <?php
        echo "Bienvenue sur MyEvents !<br>";
        echo "Nous sommes le " . date("d/m/Y");
        ?>
    </h1>
</header>

<main class="w3-container">
    <!-- B5 : bloc de statistiques -->
    <div class="w3-row-padding w3-margin-top">
        <div class="w3-third w3-panel">
            <p>Nombre total d'événements : <?= count($events) ?></p>
            <p>Total de places : <?= $totalPlaces ?></p>
        </div>
        <div class="w3-third w3-panel">
            <p>Taux de remplissage moyen : <?= $tauxMoyen ?> %</p>
        </div>
        <div class="w3-third w3-panel">
            <p>Événement le plus demandé : <?= $plusDemande ?></p>
        </div>
    </div>

    <?php
    // B6 (bonus) : tri par date croissante puis masquage des événements passés
    usort($events, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
    // On ne garde que les événements du jour ou à venir
    $aVenir = array_filter($events, fn($e) => strtotime($e['date']) >= strtotime('today'));
    // Nombre d'événements passés masqués
    $masques = count($events) - count($aVenir);
    ?>

    <!-- B6 : affichage du nombre d'événements masqués -->
    <p>Evenements passes masques : <?= $masques ?></p>

    <!-- B3 + B4 : liste des événements à venir avec badge d'état -->
    <p><?= count($aVenir) ?> evenement(s) au programme</p>

    <?php foreach ($aVenir as $event): ?>
        <?php
        // Taux de remplissage avec garde contre la division par zéro
        $taux = $event['places'] > 0 ? $event['inscrits'] / $event['places'] : 1;
        // B4 : choix du badge selon le remplissage
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
            <p>Le <?= date('d/m/Y', strtotime($event['date'])) ?> à <?= $event['lieu'] ?></p>
        </div>
    <?php endforeach; ?>
</main>

<?php
require __DIR__ . '/inc/footer.inc.php';
