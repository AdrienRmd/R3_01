-- Nettoyage : suppression des tables existantes (ordre inverse des dependances)
DROP TABLE IF EXISTS sessions_mentorat;
DROP TABLE IF EXISTS demandes_apprentissage;
DROP TABLE IF EXISTS competences;
DROP TABLE IF EXISTS matieres;
DROP TABLE IF EXISTS utilisateurs;

-- Table des utilisateurs (annuaire des membres SkillSwap)
CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'etudiant'
        CHECK (role IN ('etudiant', 'tuteur', 'admin')),
    promo VARCHAR(50),
    biographie TEXT,
    actif BOOLEAN NOT NULL DEFAULT TRUE,
    date_inscription TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Table des matieres (ajoutables via l'application)
CREATE TABLE matieres (
    id SERIAL PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    libelle VARCHAR(150) NOT NULL,
    description TEXT,
    date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Competences : savoir-faire d'un utilisateur dans une matiere
CREATE TABLE competences (
    id SERIAL PRIMARY KEY,
    id_utilisateur INTEGER NOT NULL REFERENCES utilisateurs(id) ON DELETE CASCADE,
    id_matiere INTEGER NOT NULL REFERENCES matieres(id) ON DELETE CASCADE,
    niveau VARCHAR(20) NOT NULL DEFAULT 'debutant'
        CHECK (niveau IN ('debutant', 'intermediaire', 'avance', 'expert')),
    description TEXT,
    UNIQUE (id_utilisateur, id_matiere)
);

-- Demandes d'apprentissage : un eleve veut apprendre une matiere
CREATE TABLE demandes_apprentissage (
    id SERIAL PRIMARY KEY,
    id_demandeur INTEGER NOT NULL REFERENCES utilisateurs(id) ON DELETE CASCADE,
    id_matiere INTEGER NOT NULL REFERENCES matieres(id) ON DELETE CASCADE,
    message TEXT,
    statut VARCHAR(20) NOT NULL DEFAULT 'ouverte'
        CHECK (statut IN ('ouverte', 'prise_en_charge', 'terminee', 'annulee')),
    date_demande TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Sessions de mentorat : un mentor encadre un eleve sur une matiere
CREATE TABLE sessions_mentorat (
    id SERIAL PRIMARY KEY,
    id_mentor INTEGER NOT NULL REFERENCES utilisateurs(id) ON DELETE CASCADE,
    id_eleve INTEGER NOT NULL REFERENCES utilisateurs(id) ON DELETE CASCADE,
    id_matiere INTEGER NOT NULL REFERENCES matieres(id) ON DELETE CASCADE,
    date_session DATE NOT NULL,
    heure_debut TIME NOT NULL,
    duree_minutes INTEGER NOT NULL DEFAULT 60 CHECK (duree_minutes > 0),
    statut VARCHAR(20) NOT NULL DEFAULT 'planifiee'
        CHECK (statut IN ('planifiee', 'effectuee', 'annulee')),
    commentaire TEXT,
    CHECK (id_mentor <> id_eleve)
);

-- Index pour les recherches frequentes
CREATE INDEX idx_competences_utilisateur ON competences(id_utilisateur);
CREATE INDEX idx_competences_matiere ON competences(id_matiere);
CREATE INDEX idx_demandes_matiere ON demandes_apprentissage(id_matiere);
CREATE INDEX idx_sessions_date ON sessions_mentorat(date_session);

-- Affichage d'un message de confirmation
DO $$ BEGIN RAISE NOTICE 'Tables SkillSwap creees avec succes !';
END $$;
