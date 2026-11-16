<?php
// Début commun de toutes les pages : head, lien W3.CSS et barre de navigation

// TP4 - B2 : le compteur de favoris doit être visible sur TOUTES les pages.
// Certaines n'incluent pas lib/auth.php : on démarre donc la session ici,
// seulement si elle ne l'est pas déjà (sinon warning "session already active").
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombreFavoris = count($_SESSION['favoris'] ?? array());
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MyEvents</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
<!-- Barre de navigation principale -->
<div class="w3-bar w3-blue">
    <a href="index.php" class="w3-bar-item w3-button">Accueil</a>
    <a href="event.php" class="w3-bar-item w3-button">Événement</a>
    <!-- TP4 - B2 : nombre de favoris en session, sur toutes les pages -->
    <a href="recherche.php" class="w3-bar-item w3-button">Favoris : <?= $nombreFavoris ?></a>
</div>
