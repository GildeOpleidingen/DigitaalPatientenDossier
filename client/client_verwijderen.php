<?php
session_start();
include '../database/DatabaseConnection.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    $pdo = DatabaseConnection::getPdo();

    // Soft delete: markeer als verwijderd
    $stmt = $pdo->prepare("
        UPDATE client 
        SET is_deleted = 1 
        WHERE id = :id
    ");
    $stmt->execute(['id' => $id]);

    header('Location: client.php');
    exit;
} else {
    echo "Geen geldig client ID opgegeven.";
}
?>
