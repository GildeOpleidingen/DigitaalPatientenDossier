<?php
session_start();
include_once '../../../Database/DatabaseConnection.php';

if (!isset($_SESSION['loggedin_id'])) {
    return;
}

$id = $_SESSION['loggedin_id'];
$clientId = $_POST['clientId'];
$omteVerwijderen = $_POST['verwijder'];
print_r($omteVerwijderen);

foreach ($omteVerwijderen as $key => $value) {
    if ($value == 'on') {
        $result = DatabaseConnection::getConn()->prepare("DELETE FROM verzorgerregel WHERE medewerkerid = ? AND clientid = ?");
        $result->bind_param("ss", $key, $clientId);
        $result->execute();
    }
}
?>