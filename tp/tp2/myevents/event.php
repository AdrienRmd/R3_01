<?php
require 'inc/header.inc.php';

$titre = "Nuit de l'Info";
$date = "14/03/2026";
$lieu = "Amphi A";
$places = 40;
$inscrits = 34;
$restantes = $places - $inscrits;
?>
<h2><?= $titre ?></h2>
<p>Le <?= $date ?> à <?= $lieu ?></p>
<p>Places restantes : <?= $restantes ?> / <?= $places ?></p>
<?php require 'inc/footer.inc.php'; ?>
