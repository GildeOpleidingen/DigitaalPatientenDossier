<?php
session_start();
include '../database/DatabaseConnection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    $conn = DatabaseConnection::getConn();

    // Soft delete: markeer als verwijderd
    $stmt = $conn->prepare("UPDATE client SET deleted = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: client.php');
        exit;
    } else {
        echo "Er is een fout opgetreden bij het verwijderen: " . $stmt->error;
    }
} else {
    echo "Geen geldig client ID opgegeven.";
}
?>
