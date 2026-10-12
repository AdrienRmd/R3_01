<?php
// B2 : fiche détaillée d'un événement
// Données de l'événement (simulées, affichage direct au format jj/mm/aaaa)
$titre    = 'Nuit de l\'Info';
$date     = '03/12/2026';
$lieu     = 'Amphi A';
$places   = 40;
$inscrits = 34;
// Nombre de places restantes
$restantes = $places - $inscrits;

require __DIR__ . '/inc/header.inc.php';
?>

<!-- Fiche de l'événement -->
<main class="w3-container">
    <h2><?= $titre ?></h2>
    <p>Le <?= $date ?> à <?= $lieu ?></p>
    <p>Places restantes : <?= $restantes ?> / <?= $places ?></p>
</main>

<?php
require __DIR__ . '/inc/footer.inc.php';
