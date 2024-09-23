<?php
session_start();
include_once '../../database/DatabaseConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rapportageId = $_POST['rapportageId'];
    $returnUrl = $_POST['returnUrl']; // Haal de refererende URL op

    if (isset($_POST['delete']) && $_POST['delete'] == "true") {
        // Verwijder de rapportage
        $stmt = DatabaseConnection::getConn()->prepare("DELETE FROM rapport WHERE id = ?");
        $stmt->bind_param("i", $rapportageId);
        $stmt->execute();
        $stmt->close();
        
        // Redirect naar de refererende URL na verwijderen
        header("Location: " . $returnUrl);
        exit();
    }
} else {
    $rapportageId = $_GET['id'];

    if (!$rapportageId) {
        header("Location: ../client.php");
        exit();
    }

    $rapportage = DatabaseConnection::getConn()->prepare("SELECT * FROM rapport WHERE id = ?");
    $rapportage->bind_param("i", $rapportageId);
    $rapportage->execute();
    $rapportage = $rapportage->get_result()->fetch_assoc();

    if ($rapportage == null) {
        header("Location: ../client.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        if (confirm('Weet je zeker dat je deze rapportage wilt verwijderen?')) {
            document.write('<form method="POST" id="deleteForm"><input type="hidden" name="rapportageId" value="<?= $rapportage["id"] ?>"><input type="hidden" name="delete" value="true"><input type="hidden" name="returnUrl" value="<?= $_SERVER['HTTP_REFERER'] ?>"></form>');
            document.getElementById('deleteForm').submit();
        } else {
            window.location.href = '<?= $_SERVER['HTTP_REFERER'] ?>';
        }
    </script>
</head>
<body>
</body>
</html>
