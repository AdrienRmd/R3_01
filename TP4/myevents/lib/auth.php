<?php
// TP4 - A1 : session + identifiants attendus, inclus par toutes les pages
// qui manipulent la connexion.
// Regle absolue du CM3 : session_start() doit etre la toute premiere
// instruction, avant le moindre espace ou balise HTML.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// A1 : identifiants fixes pour ce TP (pas de base de donnees avant le CM4)
const IDENTIFIANT_ATTENDU = 'formateur';

// Bonus C1 : on ne stocke plus le mot de passe en clair, mais son hachage.
// Valeur calculee UNE fois via password_hash('MyEvents2026!', PASSWORD_DEFAULT),
// puis collees ici : la comparaison se fait avec password_verify().
const MOT_DE_PASSE_HACHE = '$2y$10$W8OUdyUxpgqccryj2DvcM.VwIh4yEWCkpFjy0ricJoaxG88XcVw92';

// A1 : renvoie true si l'utilisateur est connecte
function estConnecte(): bool
{
    return isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
}

// B1 : protection REELLE de l'ajout - pas seulement l'affichage du formulaire.
// Redirige immediatement vers login.php (avec exit) si non connecte.
function exigerConnexion(): void
{
    if (!estConnecte()) {
        header('Location: login.php');
        exit; // comme au TP3 : stoppe le script juste apres la redirection
    }
}

// Bonus C2 : expiration automatique apres 5 minutes d'inactivite.
// On mémorise en session l'horodatage de la derniere activite ; si le delai
// est depasse, on detruit la session et on renvoie vers login.php avec un
// message explicatif (?expire=1).
function verifierExpirationConnexion(int $delaiSecondes = 300): void
{
    if (!estConnecte()) {
        return; // rien a expirer si personne n'est connecte
    }

    $derniereActivite = $_SESSION['derniere_activite'] ?? time();

    if (time() - $derniereActivite > $delaiSecondes) {
        // Duree depassee : session detruite proprement comme a l'exercice A3
        $_SESSION = array();
        session_destroy();

        header('Location: login.php?expire=1');
        exit;
    }

    // Activite recente : on repousse la date d'expiration
    $_SESSION['derniere_activite'] = time();
}
