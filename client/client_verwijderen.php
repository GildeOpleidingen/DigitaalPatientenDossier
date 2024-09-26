<?php
session_start();
include '../database/DatabaseConnection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verwijder de client met het opgegeven id
    $stmt = DatabaseConnection::getConn()->prepare("DELETE FROM client WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: client.php');
        exit;
    } else {
        echo "Er is een fout opgetreden bij het verwijderen: " . $stmt->error;
    }
} else {
    echo "Geen client ID opgegeven.";
}
?>
