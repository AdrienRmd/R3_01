<?php

class Competences
{
    public static function getCategories(): array
    {
        $pdo = (new DatabaseConnection())->getConnection();
        $stmt = $pdo->query('SELECT DISTINCT categorie FROM matieres ORDER BY categorie');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
