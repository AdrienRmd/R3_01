<?php
// TP3 - B2 : page de recherche avec un formulaire en GET
require 'inc/header.inc.php';
require 'data/events.php';
require_once 'lib/functions.php';

// B2 : on lit les criteres dans l'URL (vides au premier affichage)
$motCleBrut = $_GET['q'] ?? '';
$motCle = trim($motCleBrut);
$lieuRecherche = $_GET['lieu'] ?? '';

// B2 : un critere vide veut dire "tous", on passe donc null a la fonction
$critereMotCle = null;
if ($motCle !== '') {
    $critereMotCle = $motCle;
}
$critereLieu = null;
if ($lieuRecherche !== '') {
    $critereLieu = $lieuRecherche;
}

// B2 : la recherche se fait avec la fonction du TP2
$resultats = rechercherEvenements($events, $critereMotCle, $critereLieu);
$nombreResultats = count($resultats);
?>
<h2>Rechercher un evenement</h2>

<!-- B2 : formulaire en GET, les criteres restent dans l'URL -->
<form method="GET" action="recherche.php">
    <p>
        <label>Mot-clé<br>
            <input type="text" name="q" value="<?php echo htmlspecialchars($motCle); ?>">
        </label>
    </p>
    <p>
        <label>Lieu<br>
            <select name="lieu">
                <option value="">Tous les lieux</option>
                <option value="Hall A" <?php if ($lieuRecherche === 'Hall A') { echo 'selected'; } ?>>Hall A</option>
                <option value="Foyer" <?php if ($lieuRecherche === 'Foyer') { echo 'selected'; } ?>>Foyer</option>
                <option value="Amphi B" <?php if ($lieuRecherche === 'Amphi B') { echo 'selected'; } ?>>Amphi B</option>
                <option value="Salle machine" <?php if ($lieuRecherche === 'Salle machine') { echo 'selected'; } ?>>Salle machine</option>
                <option value="Gymnase" <?php if ($lieuRecherche === 'Gymnase') { echo 'selected'; } ?>>Gymnase</option>
                <option value="Amphi A" <?php if ($lieuRecherche === 'Amphi A') { echo 'selected'; } ?>>Amphi A</option>
            </select>
        </label>
    </p>
    <button type="submit">Rechercher</button>
</form>

<p><?php echo $nombreResultats; ?> resultat(s) trouve(s)</p>

<?php if ($nombreResultats > 0): ?>
<?php foreach ($resultats as $event):
$badge = calculerBadge($event);
?>
<div class="w3-card w3-margin-bottom w3-padding">
    <h3><?php echo htmlspecialchars($event['titre']); ?>
        <span class="w3-tag <?= $badge['classe'] ?>"><?= $badge['label'] ?></span>
    </h3>
    <p><?php echo formaterDateAffichage($event['date']); ?> - <?php echo htmlspecialchars($event['lieu']); ?></p>
</div>
<?php endforeach; ?>
<?php else: ?>
<!-- B2 : message clair si aucun resultat -->
<div class="w3-panel w3-pale-yellow">
    <p>Aucun resultat pour cette recherche.</p>
</div>
<?php endif; ?>
<?php require 'inc/footer.inc.php'; ?>
