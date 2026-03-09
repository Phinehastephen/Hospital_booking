<?php
require_once __DIR__ . '/../config/db.php';

function matchSpecialty($pdo, $symptomsInput) {

    $symptoms = strtolower($symptomsInput);

    $stmt = $pdo->query("SELECT * FROM symptom_specialty");
    $rows = $stmt->fetchAll();

    $scores = [];

    foreach ($rows as $row) {

        if (strpos($symptoms, strtolower($row['keyword'])) !== false) {

            $specialty = $row['specialty'];

            if (!isset($scores[$specialty])) {
                $scores[$specialty] = 0;
            }

            $scores[$specialty] += $row['weight'];
        }
    }

    if (empty($scores)) {
        return null;
    }

    arsort($scores);

    return array_key_first($scores);
}