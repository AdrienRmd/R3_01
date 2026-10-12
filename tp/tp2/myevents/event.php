<?php
require __DIR__ . '/inc/header.inc.php';

$titre    = 'Nuit de l\'Info';
$date     = '03/12/2026';
$lieu     = 'Amphi A';
$places   = 40;
$inscrits = 34;
$restantes = $places - $inscrits;
?>

<h2><?= $titre ?></h2>
<p>Le <?= $date ?> à <?= $lieu ?></p>
<p>Places restantes : <?= $restantes ?> / <?= $places ?></p>

<?php
require __DIR__ . '/inc/footer.inc.php';
