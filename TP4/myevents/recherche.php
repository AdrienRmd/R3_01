<?php
// TP3 - B2 : page de recherche avec un formulaire en GET
require 'inc/header.inc.php';
require 'data/events.php';
require_once 'lib/functions.php';

// B2 : on lit les criteres dans l'URL (vides au premier affichage)
$motCleBrut = $_GET['q'] ?? '';
$motCle = trim($motCleBrut);
$lieuRecherche = $_GET['lieu'] ?? '';

// TP4 - A5 : quand une recherche est soumise avec un lieu precis, on le
// memorise dans un cookie valable 30 jours (syntaxe "tableau d'options" du
// CM3 : httponly oui, secure omis car developpement HTTP local). Attention :
// un cookie tout juste cree n'est visible dans $_COOKIE qu'a partir de la
// requete SUIVANTE, c'est normal et attendu.
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lieu']) && $_GET['lieu'] !== '') {
    setcookie('dernier_lieu_recherche', $_GET['lieu'], array(
        'expires' => time() + 30 * 86400,
        'path' => '/',
        'httponly' => true,
    ));
}

// TP4 - A5 : pre-selection = lieu de la requete courante, sinon le cookie
// du dernier lieu recherche (le temps que l'utilisateur en choisisse un autre)
$lieuPreselectionne = $_GET['lieu'] ?? ($_COOKIE['dernier_lieu_recherche'] ?? '');

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

// Bonus C2 - pagination des résultats : on n'affiche que 5 cartes par page.
// Le numéro de page arrive dans l'URL, comme les critères de recherche.
$pageBrute = $_GET['page'] ?? '1';
$page = 1;
if (ctype_digit($pageBrute) && (int) $pageBrute >= 1) {
    $page = (int) $pageBrute;
}

$pagination = paginerEvenements($resultats, $page, 5);
$resultatsPage = $pagination['items'];
$totalPages = $pagination['totalPages'];
$page = $pagination['page']; // page réellement affichée, ramenée dans les bornes

// Bonus C2 - les liens de pagination conservent le mot-clé et le lieu
$parametresPagination = array('q' => $motCle, 'lieu' => $lieuRecherche);
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
                <option value="Hall A" <?php if ($lieuPreselectionne === 'Hall A') { echo 'selected'; } ?>>Hall A</option>
                <option value="Foyer" <?php if ($lieuPreselectionne === 'Foyer') { echo 'selected'; } ?>>Foyer</option>
                <option value="Amphi B" <?php if ($lieuPreselectionne === 'Amphi B') { echo 'selected'; } ?>>Amphi B</option>
                <option value="Salle machine" <?php if ($lieuPreselectionne === 'Salle machine') { echo 'selected'; } ?>>Salle machine</option>
                <option value="Gymnase" <?php if ($lieuPreselectionne === 'Gymnase') { echo 'selected'; } ?>>Gymnase</option>
                <option value="Amphi A" <?php if ($lieuPreselectionne === 'Amphi A') { echo 'selected'; } ?>>Amphi A</option>
            </select>
        </label>
    </p>
    <button type="submit">Rechercher</button>
</form>

<p><?php echo $nombreResultats; ?> resultat(s) trouve(s)
    <!-- Bonus C2 - position dans la pagination -->
    - page <?php echo $page; ?>/<?php echo $totalPages; ?></p>

<?php if ($nombreResultats > 0): ?>
<?php foreach ($resultatsPage as $event):
$badge = calculerBadge($event);
?>
<div class="w3-card w3-margin-bottom w3-padding">
    <h3><?php echo htmlspecialchars($event['titre']); ?>
        <span class="w3-tag <?= $badge['classe'] ?>"><?= $badge['label'] ?></span>
    </h3>
    <p><?php echo formaterDateAffichage($event['date']); ?> - <?php echo htmlspecialchars($event['lieu']); ?></p>
</div>
<?php endforeach; ?>

<?php
// Bonus C2 - liens page precedente / page suivante : les trois parametres
// (q, lieu, page) doivent rester presents dans l'URL a chaque fois
if ($totalPages > 1):
?>
<p>
    <?php if ($page > 1): ?>
        <?php $parametresPagination['page'] = $page - 1; ?>
        <a class="w3-button w3-light-grey" href="recherche.php?<?php echo http_build_query($parametresPagination); ?>">&laquo; Page precedente</a>
    <?php endif; ?>

    <?php if ($page < $totalPages): ?>
        <?php $parametresPagination['page'] = $page + 1; ?>
        <a class="w3-button w3-light-grey" href="recherche.php?<?php echo http_build_query($parametresPagination); ?>">Page suivante &raquo;</a>
    <?php endif; ?>
</p>
<?php endif; ?>
<?php else: ?>
<!-- B2 : message clair si aucun resultat -->
<div class="w3-panel w3-pale-yellow">
    <p>Aucun resultat pour cette recherche.</p>
</div>
<?php endif; ?>
<?php require 'inc/footer.inc.php'; ?>
