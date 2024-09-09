<?php
session_start();
if (!isset($_SESSION['loggedin_id'])) {
    return;
}
session_destroy();
header("Location: ./inloggen");
?>