<?php
if (!isset($_SESSION['loggedin_id'])) {
    header('Location: ../../index.php');
    exit;
}
?>