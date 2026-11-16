<?php
// TP4 - A4 : inclusion d'auth.php en tout premier (c'est lui qui demarre
// la session) pour savoir si on affiche le formulaire d'ajout ou non.
require 'data/events.php';
require_once 'lib/functions.php';
require_once 'lib/auth.php';

// Bonus C2 : verifie l'expiration d'inactivite et rafraichit le compteur
verifierExpirationConnexion();

// TP4 - B4 : ordre de lecture des superglobales en haut de page :
// 1. $_SESSION (via auth.php) : on doit savoir AVANT TOUT si l'utilisateur
//    est connecte, car ca conditionne la protection du POST (B1) et l'ajout
//    aux favoris (B2) ;
// 2. $_COOKIE : la derniere visite (B3) doit etre lue AVANT d'etre ecrasee
//    par sa nouvelle valeur, sinon on perdrait toujours l'ancienne ;
// 3. $_POST : le traitement de l'ajout et des favoris doit venir apres le
//    controle de connexion (exigerConnexion) et rediriger avant tout HTML ;
// 4. $_GET : le filtre par lieu ne concerne que l'affichage, il est lu en
//    dernier et ne modifie jamais l'etat de l'application.
// Cet ordre est necessaire car chaque mecanisme depend des precedents et que
// les redirections (PRG) doivent partir avant le moindre affichage HTML.

// TP3 - A2 : tableau des erreurs, vide au depart
$erreurs = array();

// TP3 - A4 : valeurs saisies, pour reafficher le formulaire si la validation echoue
$titreSaisi = '';
$dateSaisie = '';
$lieuSaisi = '';
$placesSaisies = '';

// B3 : filtre par lieu en GET, relu a chaque affichage de la page
$lieuFiltre = $_GET['lieu_filtre'] ?? '';

// TP4 - B2 : action "ajouter aux favoris" envoyee par le petit formulaire
// des cartes. On utilise POST et non GET : cette action MODIFIE l'etat de
// l'application (la session), et le CM3 dit qu'une requete qui modifie
// l'etat ne doit pas etre rejouable par un simple rafraichissement d'URL.
// En pratique un lien GET reste tres courant pour ce genre de fonctionnalite
// secondaire, mais POST est le choix propre (et il permet le motif PRG).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favori'])) {
    exigerConnexion(); // B1 : pas de favori non plus sans connexion

    $idFavoriBrut = $_POST['favori'];
    if (ctype_digit($idFavoriBrut) && isset($events[(int) $idFavoriBrut])) {
        $idFavori = (int) $idFavoriBrut;

        // B2 : initialisation du tableau de favoris au premier ajout
        if (!isset($_SESSION['favoris'])) {
            $_SESSION['favoris'] = array();
        }

        // B2 : un evenement deja en favoris ne doit pas y passer deux fois
        if (!in_array($idFavori, $_SESSION['favoris'], true)) {
            $_SESSION['favoris'][] = $idFavori;
        }

        // PRG comme au TP3 : apres modification d'etat, on redirige
        header('Location: index.php');
        exit;
    }
}

// TP3 - A2 : on traite le formulaire seulement si la page a ete appelee en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TP4 - B1 : protection REELLE - un POST direct sans connexion est bloque,
    // meme en recreant la requete a la main sans passer par le formulaire
    exigerConnexion();
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

// TP4 - B3 : derniere visite. On lit l'ANCIENNE valeur du cookie AVANT de
// l'ecraser avec la nouvelle, sinon on perdrait toujours l'ancienne.
$premiereVisite = !isset($_COOKIE['derniere_visite']);
$messageVisite = '';
if (!$premiereVisite) {
    $ecoule = time() - (int) $_COOKIE['derniere_visite'];
    $jours = (int) floor($ecoule / 86400); // 86400 secondes dans un jour
    if ($jours <= 0) {
        $messageVisite = 'Derniere visite : aujourd\'hui.';
    } elseif ($jours === 1) {
        $messageVisite = 'Derniere visite : il y a 1 jour.';
    } else {
        $messageVisite = 'Derniere visite : il y a ' . $jours . ' jours.';
    }
}

// TP4 - B3 : mise a jour du cookie d'horodatage a chaque chargement,
// avec la syntaxe "tableau d'options" vue au CM3 (30 jours, httponly).
// Pas d'option secure : on developpe en HTTP local (a ajouter en HTTPS).
setcookie('derniere_visite', (string) time(), array(
    'expires' => time() + 30 * 86400,
    'path' => '/',
    'httponly' => true,
));
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

<?php if (estConnecte()): ?>
    <!-- TP4 - A4 : bouton de deconnexion visible uniquement quand on est connecte -->
    <p><a class="w3-button w3-red" href="logout.php">Se deconnecter</a></p>

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
<?php else: ?>
    <!-- TP4 - A4 : formulaire masque pour un visiteur non connecte -->
    <div class="w3-panel w3-pale-yellow">
        <p>Seuls les utilisateurs connectes peuvent ajouter un evenement.
            <a href="login.php">Se connecter</a></p>
    </div>
<?php endif; ?>

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

<?php if ($premiereVisite): ?>
    <!-- TP4 - B3 : tout premier chargement : le cookie n'existe pas encore -->
    <div class="w3-panel w3-pale-blue">
        <p>Bienvenue, c'est votre premiere visite !</p>
    </div>
<?php else: ?>
    <!-- TP4 - B3 : message de derniere visite -->
    <div class="w3-panel w3-pale-blue">
        <p><?= htmlspecialchars($messageVisite) ?></p>
    </div>
<?php endif; ?>

<?php foreach ($aVenir as $event):
$badge = calculerBadge($event);
// TP4 - B2 : trierEvenementsParDate() reindexe les cles (usort), l'id de la
// boucle n'est donc PAS l'index canonique de $events : on le retrouve par
// valeur pour que le favori pointe sur le meme id qu'event.php?id=...
$idCanonique = array_search($event, $events, true);
$enFavori = isset($_SESSION['favoris']) && in_array($idCanonique, $_SESSION['favoris'], true);
?>
<div class="w3-card w3-margin-bottom w3-padding">
    <h3><?php echo htmlspecialchars($event['titre']); ?>
        <span class="w3-tag <?= $badge['classe'] ?>"><?= $badge['label'] ?></span>
    </h3>
    <p><?php echo formaterDateAffichage($event['date']); ?> - <?php echo htmlspecialchars($event['lieu']); ?></p>

    <?php if (estConnecte()): ?>
        <!-- TP4 - B2 : ajout aux favoris, en POST (voir commentaire en haut
             de page). Un evenement deja en favori affiche un simple temoin. -->
        <?php if ($enFavori): ?>
            <span class="w3-tag w3-green">En favori</span>
        <?php else: ?>
            <form method="POST" action="index.php">
                <input type="hidden" name="favori" value="<?= (int) $idCanonique ?>">
                <button type="submit" class="w3-button w3-light-grey w3-small">Ajouter aux favoris</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<?php require 'inc/footer.inc.php'; ?>
