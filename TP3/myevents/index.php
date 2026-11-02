<?php
// TP3 - A2 : traitement du formulaire AVANT tout affichage HTML
require 'data/events.php';
require_once 'lib/functions.php';

// A2 : tableau des erreurs, vide au depart
$erreurs = array();

// A4 : valeurs saisies, pour reafficher le formulaire si la validation echoue
$titreSaisi = '';
$dateSaisie = '';
$lieuSaisi = '';
$placesSaisies = '';

// B3 : filtre par lieu en GET, relu a chaque affichage de la page
$lieuFiltre = $_GET['lieu_filtre'] ?? '';

// A2 : on traite le formulaire seulement si la page a ete appelee en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titreBrut = $_POST['titre'] ?? '';
    $titre = trim($titreBrut);
    $date = $_POST['date'] ?? '';
    $lieu = $_POST['lieu'] ?? '';
    $places = (int) ($_POST['places'] ?? 0);

    // A5 : les cases cochees arrivent dans un tableau
    $categories = $_POST['categories'] ?? array();

    // A2 : construction du nouvel evenement avec les cles attendues par le TP2
    $nouvelEvenement = array(
        'titre' => $titre,
        'date' => $date,
        'lieu' => $lieu,
        'places' => $places,
        'inscrits' => 0,
        'categories' => $categories,
    );

    // A2 : la fonction du TP2 valide ET ajoute, elle renvoie les erreurs
    $erreurs = ajouterEvenement($events, $nouvelEvenement);

    // Bonus C1 - Post/Redirect/Get : après un ajout réussi, on redirige
    // immédiatement. Un F5 ne renvoie donc plus le formulaire, et l'événement
    // ne peut pas être ajouté deux fois. Les erreurs, elles, doivent rester
    // affichées sur la même page pour que le formulaire soit réaffiché rempli.
    if (count($erreurs) === 0) {
        header('Location: index.php');
        exit; // tres important : stoppe le script juste après la redirection
    }

    // A4 : on garde les valeurs saisies seulement si la validation a echoue
    if (count($erreurs) > 0) {
        $titreSaisi = $titre;
        $dateSaisie = $date;
        $lieuSaisi = $lieu;
        $placesSaisies = (string) $places;
    }
}

// TP2 : liste des evenements a venir, triee par date
$evenementsFuturs = filtrerEvenementsFuturs($events);
$aVenir = trierEvenementsParDate($evenementsFuturs);
$masques = count($events) - count($aVenir);
$stats = statistiquesEvenements($events);

// B3 : si un lieu est filtre, on ne garde que les evenements de ce lieu
if ($lieuFiltre !== '') {
    $aVenir = rechercherEvenements($aVenir, null, $lieuFiltre);
}
?>
<?php require 'inc/header.inc.php'; ?>
<?php
echo "Bienvenue sur MyEvents !<br>";
echo "Nous sommes le " . date("d/m/Y");
?>
<p><?= $stats['nombre'] ?> evenements, <?= $stats['totalPlaces'] ?> places au total,
taux de remplissage moyen : <?= $stats['tauxGlobal'] ?>%</p>
<p>Evenement le plus demande : <?= $stats['evenementPlusDemande'] ?></p>
<p>Evenements passes masques : <?= $masques ?></p>

<!-- A3 : affichage des erreurs -->
<?php if (count($erreurs) > 0): ?>
<div class="w3-panel w3-red">
    <ul>
        <?php foreach ($erreurs as $erreur): ?>
            <li><?php echo $erreur; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<h2>Ajouter un evenement</h2>

<!-- A1 : formulaire d'ajout, en POST -->
<form method="POST">
    <p>
        <label>Titre<br>
            <input type="text" name="titre" placeholder="Titre de l'evenement" required
                   value="<?php echo htmlspecialchars($titreSaisi); ?>">
        </label>
    </p>
    <p>
        <label>Date<br>
            <input type="date" name="date" required
                   value="<?php echo htmlspecialchars($dateSaisie); ?>">
        </label>
    </p>
    <p>
        <label>Lieu<br>
            <select name="lieu" required>
                <option value="Amphi A" <?php if ($lieuSaisi === 'Amphi A') { echo 'selected'; } ?>>Amphi A</option>
                <option value="Hall B" <?php if ($lieuSaisi === 'Hall B') { echo 'selected'; } ?>>Hall B</option>
                <option value="Foyer" <?php if ($lieuSaisi === 'Foyer') { echo 'selected'; } ?>>Foyer</option>
            </select>
        </label>
    </p>
    <p>
        <label>Places<br>
            <input type="number" name="places" min="1" required
                   value="<?php echo htmlspecialchars($placesSaisies); ?>">
        </label>
    </p>
    <p>
        Categories :
        <!-- A5 : deux cases a cocher -->
        <label><input type="checkbox" name="categories[]" value="conference"> Conference</label>
        <label><input type="checkbox" name="categories[]" value="atelier"> Atelier</label>
    </p>
    <button type="submit">Ajouter l'evenement</button>
</form>

<!-- B3 : filtre rapide par lieu, en GET -->
<h2>Filtrer par lieu</h2>
<form method="GET" action="index.php">
    <select name="lieu_filtre">
        <option value="">Tous les lieux</option>
        <option value="Hall A" <?php if ($lieuFiltre === 'Hall A') { echo 'selected'; } ?>>Hall A</option>
        <option value="Foyer" <?php if ($lieuFiltre === 'Foyer') { echo 'selected'; } ?>>Foyer</option>
        <option value="Amphi B" <?php if ($lieuFiltre === 'Amphi B') { echo 'selected'; } ?>>Amphi B</option>
        <option value="Salle machine" <?php if ($lieuFiltre === 'Salle machine') { echo 'selected'; } ?>>Salle machine</option>
        <option value="Gymnase" <?php if ($lieuFiltre === 'Gymnase') { echo 'selected'; } ?>>Gymnase</option>
        <option value="Amphi A" <?php if ($lieuFiltre === 'Amphi A') { echo 'selected'; } ?>>Amphi A</option>
    </select>
    <button type="submit">Filtrer</button>
</form>

<p><?= count($aVenir) ?> evenement(s) au programme</p>
<?php foreach ($aVenir as $event):
$badge = calculerBadge($event);
?>
<div class="w3-card w3-margin-bottom w3-padding">
    <h3><?php echo htmlspecialchars($event['titre']); ?>
        <span class="w3-tag <?= $badge['classe'] ?>"><?= $badge['label'] ?></span>
    </h3>
    <p><?php echo formaterDateAffichage($event['date']); ?> - <?php echo htmlspecialchars($event['lieu']); ?></p>
</div>
<?php endforeach; ?>
<?php require 'inc/footer.inc.php'; ?>
