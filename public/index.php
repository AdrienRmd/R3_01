<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SkillSwap — Accueil</title>
</head>

<body>
    <h1>SkillSwap</h1>
    <p>L'application interne pour identifier qui sait quoi, demander à apprendre une compétence et organiser des sessions de mentorat.</p>

    <h2>Les trois piliers</h2>
    <ol>
        <li>Annuaire des compétences</li>
        <li>Demandes d'apprentissage</li>
        <li>Sessions de mentorat</li>
    </ol>

    <h2>Environnement</h2>
    <ul>
        <li>Date : <?php echo htmlspecialchars(date('d/m/Y')); ?></li>
        <li>Heure : <?php echo htmlspecialchars(date('H:i')); ?></li>
        <li>Version de PHP : <?php echo htmlspecialchars(PHP_VERSION); ?></li>
        <li>Serveur : <?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'inconnu'); ?></li>
    </ul>
</body>

</html>
