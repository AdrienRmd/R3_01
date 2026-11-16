<?php
// TP4 - A3 : deconnexion propre puis retour a l'accueil.
require_once 'lib/auth.php';

// A3 : on vide d'abord le tableau de session, puis on detruis la session.
// Detruire sans vider laisserait $_SESSION rempli pour le reste du script.
$_SESSION = array();
session_destroy();

header('Location: index.php');
exit;
