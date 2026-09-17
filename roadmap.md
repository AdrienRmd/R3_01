# SkillSwap — Feuille de route du projet

> Objectif final : l'application interne qui rend visible *qui sait quoi* dans l'entreprise,
> permet de demander à apprendre une compétence, et transforme ces demandes en sessions de mentorat.
>
> **Légende** : ✅ confirmé par le sujet de TP · 🔮 prévisionnel (sujet pas encore distribué, à ajuster)

---

## Les trois piliers fonctionnels de SkillSwap

1. **Annuaire des compétences** — qui sait quoi, à quel niveau
2. **Demandes d'apprentissage** — un collaborateur demande à apprendre une compétence
3. **Sessions de mentorat** — une demande rencontre un mentor, ça devient une session planifiée

---

## Séance 1 — Fondations PHP ✅ *(en cours)*

**Objectif : maîtriser le langage (CLI) et produire les premières pages web.**

### Partie A — PHP en ligne de commande (`bin/`)

- [x] A1 — Premier script : `bin/a1_bienvenue.php`
- [ ] A2 — Variables, concaténation, interpolation (`a2_fiche.php`)
- [ ] A3 — Conditions et validation de saisie (`a3_niveau.php`) — refuser `0`, `7`, `abc`
- [ ] A4 — Chaînes : initiales, e-mail pro, `strlen` vs `mb_strlen` (`a4_chaines.php`)
- [ ] A5 — Tableaux indexés : count, tri, ajout (`a5_tableaux.php`)
- [ ] A6 — Tableaux associatifs : fiche compétence, foreach, array_sum (`a6_associatifs.php`)

### Partie B — Premières pages (`public/`)

- [x] B1 — Page d'accueil dynamique (date + version PHP) — `public/index.php`
- [ ] B2 — Données figées : `src/Data/competences.php` (8 compétences, 3 catégories, une avec `nb_maitrises` à 0)
- [ ] B3 — Liste des compétences en tableau HTML + `htmlspecialchars()`
- [ ] B4 — Fiche d'une compétence (`fiche.php?id=N`) avec gestion des cas invalides (`?id=999`, `?id=abc`, sans paramètre)
- [ ] B5 — Badge de couverture, fonction unique dans `src/fonctions.php`
- [ ] B6 — Extensions : statistiques, filtre par catégorie, tri

> ✅ **Structure niveau entreprise adoptée** : `bin/`, `public/`, `src/`, `config/`
> (voir la correspondance détaillée plus bas). Si un corrigé du sujet exige les
> noms imposés (`cli/`, `inc/`, `data/`), renommer à la volée avant de rendre.

**Validation S1** : checklist de fin de séance du sujet, entièrement cochée.

---

## Séance 2 — Base de données MySQL + PDO ✅

**Objectif : les données quittent le fichier PHP pour une vraie base.**

- [ ] Concevoir la base `skillswap` (probablement : `competence`, et tôt ou tard `collaborateur`, `maitrise`…)
- [ ] Créer les tables et les remplir (8 compétences minimum, comme en B2)
- [ ] Se connecter en PDO (config locale **hors git** — voir `.gitignore` : `config/config.local.php`)
- [ ] Requêtes **préparées** systématiquement — comprendre l'injection SQL
- [ ] Réécrire `index.php` et `fiche.php` pour lire en base
- [ ] Prévoir la connexion MySQL : conteneur Docker du module, MySQL local du VPS, ou autre

**Validation S2** : les pages S1 fonctionnent à l'identique, alimentées par MySQL.

---

## Séance 3 — 🔮 Suite des données

Hypothèses probables : élargissement du modèle (collaborateurs, niveaux de maîtrise),
premières requêtes d'insertion/mise à jour, jointures.
**À remplacer par le vrai sujet dès distribution.**

---

## Séance 4 — Formulaires ✅ *(annoncé par le S1 : « dès la séance 4 elles viendront d'un formulaire »)*

**Objectif : l'utilisateur entre des données.**

- [ ] Formulaires HTML (method GET/POST), champs, boutons
- [ ] Récupération et **validation côté serveur** (`$_POST` aussi peu fiable que `$_GET`)
- [ ] Échappement systématique (`htmlspecialchars`) — la faille XSS devient réelle
- [ ] Probable : ajouter une compétence, ou exprimer une demande d'apprentissage

---

## Séance 5 — Organisation du code ✅ *(annoncé : « ce qu'on généralisera en séance 5 »)*

**Objectif : factoriser, structurer.**

- [ ] Généraliser l'usage de `src/` (fonctions communes, comme le badge en B5)
- [ ] Séparation logique / affichage, inclusions, conventions PSR détaillées
- [ ] Probable : mise en place d'une structure type contrôleur/rendu

---

## Séances 6 à 8 — 🔮 Cœur fonctionnel

Hypothèses de montée en puissance, calées sur les trois piliers :

- **S6** — Authentification : qui est connecté (sessions PHP, mot de passe)
- **S7** — Demandes d'apprentissage : les collaborateurs demandent à apprendre
- **S8** — Sessions de mentorat : appariement demande ↔ mentor, planification, suivi

**À remplacer par les vrais sujets dès distribution.**

---

## Séance 9 — Épilogue et retour des assistants IA ✅

- [ ] Finalisation, nettoyage, validation générale
- [ ] Rallumer les assistants IA **avec du recul** : comparer ce qu'ils produisent
      à ce qu'on a appris à écrire soi-même pendant huit séances

---

## Environnement et règles d'or

### Environnement

| Où | Comment |
|---|---|
| Local | `php -S localhost:8080 -t public` depuis `R3_01/` |
| VPS | `git pull` dans `~/R3_01` → `https://aremond.ovh/R3_01/` (alias Apache → `~/R3_01/public`) |
| Synchronisation | commit + push (local) → pull (VPS), à chaque fin de séance |
| Sauvegarde | le dépôt GitHub **est** la copie hors IUT exigée par la checklist |

### Structure niveau entreprise (adoptée)

> Le projet utilise les conventions professionnelles (Symfony, Laravel).
> Si un corrigé du sujet exige les noms imposés, renommer à la volée :
> `bin/` → `cli/`, `src/` → `inc/`, `src/Data/` → `data/`.

```
mon-projet/
├── bin/        # commandes CLI
├── public/     # SEULE racine web, tout passe par index.php
├── src/        # code de l'application (+ src/Data/ pour les données figées)
├── config/     # configuration (config/config.local.php, hors git)
└── templates/  # vues (introduites en S5, séparation logique / affichage)
```

| Projet (entreprise) | Nom du sujet (si exigé) | Rôle |
|---|---|---|
| `bin/` | `cli/` | scripts en ligne de commande |
| `public/` | `public/` | identique : unique dossier exposé au web (sécurité) |
| `src/` | `inc/` | fonctions puis classes, autoloadées par Composer (PSR-4) en pro |
| `src/Data/` | `data/` | données figées, souvent remplacées par la base (S2) |
| `config/` | `config.local.php` à la racine | secrets et réglages, hors git |
| `templates/` | HTML dans les `.php` | vues séparées de la logique (objectif S5) |

### Règles d'or (acquises dès la S1, valables jusqu'à la S9)

1. **Échapper** tout ce qui s'affiche : `htmlspecialchars()`
2. **Ne jamais faire confiance** à `$_GET` / `$_POST` : valider avant d'utiliser
3. **Requêtes préparées** dès qu'il y a une base de données (S2)
4. **Un seul endroit** par morceau de logique : fonctions dans `src/`
5. Nommage clair (`$competence`, pas `$c`), indentation propre, PSR
6. Respecter les noms de fichiers imposés : les corrigés en dépendent
