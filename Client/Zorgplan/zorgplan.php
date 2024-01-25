<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/ClientFunctions.php';
$id = $_GET['id'];
if(getMedischOverzichtByClientId($id)){
    echo "<a href='../Clientverhaal/clientverhaal.php?id=$id'> Clientverhaal invullen </a>";
}

// TODO: Zorgplan pagina

?>