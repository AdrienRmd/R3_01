# SkillSwap — Feuille de route

Application interne : identifier qui sait quoi, permettre de demander à apprendre une compétence, et transformer ces demandes en sessions de mentorat.

## Piliers fonctionnels

1. Annuaire des compétences
2. Demandes d'apprentissage
3. Sessions de mentorat

## Séances

| Séance | Thème | Contenu |
|---|---|---|
| S1 | Fondations PHP | Bases du langage en CLI, premières pages web dynamiques |
| S2 | MySQL + PDO | Conception de la base, requêtes préparées, migration des pages vers MySQL |
| S3 | Suite des données | Élargissement du modèle, insertions/mises à jour, jointures *(sujet à venir)* |
| S4 | Formulaires | Saisie utilisateur, validation serveur, échappement XSS |
| S5 | Organisation du code | Factorisation dans `src/`, séparation logique/affichage, PSR |
| S6 | Authentification | Sessions PHP, mots de passe *(sujet à venir)* |
| S7 | Demandes d'apprentissage | Fonctionnalité pilier 2 *(sujet à venir)* |
| S8 | Sessions de mentorat | Appariement demande ↔ mentor, planification, suivi *(sujet à venir)* |
| S9 | Finalisation | Nettoyage, validation générale, bilan |

**Validation S1** : checklist de fin de séance du sujet entièrement cochée.
**Validation S2** : les pages S1 fonctionnent à l'identique, alimentées par MySQL.

## Plan d'exécution

### Étape 1 — Terminer la S1 (PHP + premières pages)

- [x] 1.1 — Script CLI de découverte (`bin/a1_bienvenue.php`)
- [x] 1.2 — Page d'accueil dynamique (`public/index.php`)
- [ ] 1.3 — Scripts CLI : variables, conditions, chaînes, tableaux (`bin/a2_…` à `bin/a6_…`)
- [ ] 1.4 — Données figées : `src/Data/competences.php` (8 compétences, 3 catégories)
- [ ] 1.5 — Liste des compétences en tableau HTML sur `index.php`
- [ ] 1.6 — Fiche compétence `public/fiche.php?id=N` avec validation du paramètre
- [ ] 1.7 — Fonctions communes (`src/fonctions.php`) : badge, helpers d'affichage
- [ ] 1.8 — Extensions : statistiques, filtre par catégorie, tri
- [ ] 1.9 — Valider la checklist S1 → commit, push, pull sur le VPS

### Étape 2 — Migrer vers MySQL (S2)

- [ ] 2.1 — Modéliser la base `skillswap` (tables `competence`, puis `collaborateur`, `maitrise`)
- [ ] 2.2 — Créer les tables et insérer les données de `src/Data/competences.php`
- [ ] 2.3 — Connexion PDO dans `config/config.local.php` (hors git)
- [ ] 2.4 — Réécrire `index.php` et `fiche.php` avec des requêtes préparées
- [ ] 2.5 — Supprimer les données figées devenues inutiles
- [ ] 2.6 — Vérifier : pages S1 identiques, alimentées par MySQL → synchroniser

### Étape 3 — Étendre le modèle de données (S3)

- [ ] 3.1 — Ajouter `collaborateur` et `maitrise` (niveau 1–5) avec jointures
- [ ] 3.2 — Écritures en base : insertions et mises à jour
- [ ] 3.3 — Adapter l'annuaire : afficher qui sait quoi, à quel niveau

### Étape 4 — Formulaires (S4)

- [ ] 4.1 — Formulaire d'ajout de compétence (POST)
- [ ] 4.2 — Validation serveur + échappement XSS
- [ ] 4.3 — Formulaire de demande d'apprentissage (piliers 1 → 2)

### Étape 5 — Restructurer le code (S5)

- [ ] 5.1 — Créer `templates/` et séparer logique / affichage
- [ ] 5.2 — Factoriser toutes les vues répétées (header, footer, badge)
- [ ] 5.3 — Normaliser le nommage et PSR

### Étape 6 — Authentification (S6)

- [ ] 6.1 — Table `collaborateur` complétée (identifiants, mots de passe)
- [ ] 6.2 — Connexion / déconnexion par sessions PHP
- [ ] 6.3 — Restreindre les actions à l'utilisateur connecté

### Étape 7 — Demandes d'apprentissage (S7)

- [ ] 7.1 — Table `demande` liée à collaborateur + compétence
- [ ] 7.2 — Créer, lister et suivre ses demandes

### Étape 8 — Sessions de mentorat (S8)

- [ ] 8.1 — Table `session_mentorat` : appariement demande ↔ mentor
- [ ] 8.2 — Planification (date, statut) et suivi
- [ ] 8.3 — Tableau de bord : demandes en attente, sessions à venir

### Étape 9 — Finalisation (S9)

- [ ] 9.1 — Nettoyage du code, vérification de toutes les règles
- [ ] 9.2 — Test complet du parcours : annuaire → demande → mentorat
- [ ] 9.3 — Déploiement final sur le VPS, bilan

> Rythme : une étape par séance. Après chaque sous-étape terminée : commit + push.

## Environnement

| Où | Comment |
|---|---|
| Local | `php -S localhost:8080 -t public` |
| VPS | `git pull` dans `~/R3_01` → `https://aremond.ovh/R3_01/` (alias Apache → `~/R3_01/public`) |
| Synchronisation | commit + push (local) → pull (VPS) à chaque fin de séance |
| Sauvegarde | dépôt GitHub |

## Structure du projet

```
R3_01/
├── bin/        # scripts CLI (renommé cli/ si exigé par le sujet)
├── public/     # unique racine web
├── src/        # code applicatif (+ src/Data/, renommé inc/ et data/ si exigé)
├── config/     # configuration (config.local.php, hors git)
└── templates/  # vues (dès S5)
```

## Règles

1. Échapper toute sortie : `htmlspecialchars()`
2. Valider `$_GET` / `$_POST` avant usage
3. Requêtes préparées dès qu'il y a une base de données
4. Une seule implémentation par logique : fonctions dans `src/`
5. Nommage explicite, indentation propre, PSR
6. Respecter les noms de fichiers imposés
