<?php
// TP3 - B1 : fiche detail d'un evenement, avec gestion d'erreur
require 'inc/header.inc.php';
require 'data/events.php';
require_once 'lib/functions.php';

// B1 : on recupere id dans l'URL et on verifie que c'est bien un entier
$idBrut = $_GET['id'] ?? '';
$event = null;
if ($idBrut !== '' && ctype_digit($idBrut)) {
    $id = (int) $idBrut;
    if (isset($events[$id])) {
        $event = $events[$id];
    }
}
?>
<?php if ($event !== null):
$badge = calculerBadge($event);
$placesRestantes = $event['places'] - $event['inscrits'];
?>
<h2><?php echo htmlspecialchars($event['titre']); ?>
    <span class="w3-tag <?= $badge['classe'] ?>"><?= $badge['label'] ?></span>
</h2>
<p>Le <?php echo formaterDateAffichage($event['date']); ?> à <?php echo htmlspecialchars($event['lieu']); ?></p>
<p>Places restantes : <?php echo $placesRestantes; ?> / <?php echo $event['places']; ?></p>
<?php else: ?>
<!-- B1 : cas introuvable (id absent ou inexistant) -->
<div class="w3-panel w3-pale-red">
    <h3>Événement introuvable</h3>
    <p><a href="index.php">Retour à l'accueil</a></p>
</div>
<?php endif; ?>
<?php require 'inc/footer.inc.php'; ?>
