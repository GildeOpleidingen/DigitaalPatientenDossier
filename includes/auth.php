<?php
if (!isset($_SESSION['loggedin_id'])) {
    header('Location: ../../inloggen');
    exit;
}
?>