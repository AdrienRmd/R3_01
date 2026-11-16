<?php
// TP4 - A2 : page de connexion.
// On inclut auth.php en premier : c'est lui qui demarre la session.
require_once 'lib/auth.php';

// Bonus C2 : si la session a expire, login.php?expire=1 affiche pourquoi
$expiree = isset($_GET['expire']);

// A2 : message d'erreur generique, volontairement sans preciser si c'est
// l'identifiant ou le mot de passe qui est faux (sinon on aiderait un
// attaquant a deviner les identifiants valides un par un)
$erreur = '';

// A2 : traitement de la connexion au POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    // Bonus C1 : comparaison du mot de passe par hachage, jamais en clair
    if ($identifiant === IDENTIFIANT_ATTENDU && password_verify($motDePasse, MOT_DE_PASSE_HACHE)) {
        $_SESSION['connecte'] = true;

        // Bonus C2 : depart du compteur d'inactivite
        $_SESSION['derniere_activite'] = time();

        // A2 : redirection vers l'accueil, comme le motif PRG du TP3
        header('Location: index.php');
        exit;
    }

    // A2 : echec -> message generique, le formulaire reste affiche rempli
    $erreur = 'Identifiant ou mot de passe incorrect.';
}

require 'inc/header.inc.php';
?>

<h2>Connexion</h2>

<?php if ($expiree): ?>
    <div class="w3-panel w3-pale-yellow">
        <p>Votre connexion a expire apres 5 minutes d'inactivite. Merci de vous reconnecter.</p>
    </div>
<?php endif; ?>

<?php if ($erreur !== ''): ?>
    <div class="w3-panel w3-red">
        <p><?= htmlspecialchars($erreur) ?></p>
    </div>
<?php endif; ?>

<!-- A2 : formulaire de connexion en POST -->
<form method="POST" action="login.php" class="w3-container">
    <p>
        <label>Identifiant<br>
            <input type="text" name="identifiant" placeholder="Identifiant" required
                   value="<?php echo htmlspecialchars($_POST['identifiant'] ?? ''); ?>">
        </label>
    </p>
    <p>
        <label>Mot de passe<br>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        </label>
    </p>
    <button type="submit" class="w3-button w3-blue">Se connecter</button>
</form>

<?php require 'inc/footer.inc.php'; ?>
