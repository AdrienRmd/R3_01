-- Suppression des donnees existantes (enfants d'abord, puis matieres)
DELETE FROM sessions_mentorat;
DELETE FROM demandes_apprentissage;
DELETE FROM competences;
DELETE FROM utilisateurs;
DELETE FROM matieres;
-- Reinitialisation des sequences a 1
ALTER SEQUENCE sessions_mentorat_id_seq RESTART WITH 1;
ALTER SEQUENCE demandes_apprentissage_id_seq RESTART WITH 1;
ALTER SEQUENCE competences_id_seq RESTART WITH 1;
ALTER SEQUENCE utilisateurs_id_seq RESTART WITH 1;
ALTER SEQUENCE matieres_id_seq RESTART WITH 1;

-- Insertion des matieres de base pour un etudiant en BUT2, par categorie
INSERT INTO matieres (code, libelle, categorie, description)
VALUES
    -- Categorie : Langages
    ('HTML', 'HTML', 'Langages', 'Intégration sémantique et accessible'),
    ('CSS', 'CSS', 'Langages', 'Responsive, animations modernes'),
    ('JS', 'JavaScript', 'Langages', 'DOM, interactions utilisateur'),
    ('PHP', 'PHP', 'Langages', 'Back-end et formulaires'),
    ('PY', 'Python', 'Langages', 'Scripting et algorithmique'),
    ('C', 'C', 'Langages', 'Programmation bas niveau'),
    ('CPP', 'C++', 'Langages', 'Programmation orientée objet'),
    ('SQL', 'SQL / PostgreSQL', 'Langages', 'Bases de données relationnelles'),

    -- Categorie : Outils
    ('GIT', 'Git / GitHub', 'Outils', 'Versioning et collaboration'),
    ('VSCODE', 'VS Code', 'Outils', 'Environnement de développement'),
    ('TRELLO', 'Trello', 'Outils', 'Gestion de projet et tâches'),
    ('FIGMA', 'Figma', 'Outils', 'Maquettes et prototypes UI'),

    -- Categorie : Environnement
    ('LINUX', 'Linux / Terminal', 'Environnement', 'Commandes de base, scripts'),

    -- Categorie : Frameworks
    ('BOOTSTRAP', 'Bootstrap', 'Frameworks', 'Framework CSS responsive'),
    ('LARAVEL', 'Laravel', 'Frameworks', 'Framework MVC PHP');

-- Affichage d'un message de confirmation
DO $$ BEGIN RAISE NOTICE 'Matieres inserees avec succes !';
END $$;
