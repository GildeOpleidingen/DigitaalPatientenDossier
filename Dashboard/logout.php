<?php
session_start();
if (!isset($_SESSION['loggedin_id'])) {
    return;
}
include 'Database/DatabaseConnection.php';
session_destroy();
header("Location: index.php");
?>