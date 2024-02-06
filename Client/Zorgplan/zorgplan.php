<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/ClientFunctions.php';
// $id = $_GET['id'];
// if(getMedischOverzichtByClientId($id)){
//     echo "<a href='../Clientverhaal/clientverhaal.php?id=$id'> Clientverhaal invullen </a>";
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="zorgplan.css">
    <title>Zorgplan</title>
</head>
<body>
    <?php include_once '../../Includes/header.php'; ?>

    <div class="main">
        <?php include_once '../../Includes/sidebar.php'; ?>
        <div class="main2">
            <div class="main3">
                <div class="header">
                    <p class="title" style="font-size: 2rem; padding:5px;">Zorgplan</p>
                    <a href="" class="title button" style="font-size: 2rem">Zorgplan toevoegen</a>
                </div>
                <div class="content">
                    <a href="#" class="title">Patroon van Gezondheidsbeleving en -instandhouding</a>
                    <a href="#" class="title">Voedings- en stofwisselingspatroon</a>
                    <a href="#" class="title">Uitscheidingspatroon</a>
                    <a href="#" class="title">Activiteitenpatroon</a>
                    <a href="#" class="title">Slaap-rustpatroon</a>
                    <a href="#" class="title">Cognitie- en waarnemingspatroon</a>
                    <a href="#" class="title">Zelfbelevingspatroon</a>
                    <a href="#" class="title">Rollen- en relatiepatroon</a>
                    <a href="#" class="title">Seksualiteits- en voortplantingspatroon</a>
                    <a href="#" class="title">Stressverwerkingspatroon</a>
                    <a href="#" class="title">Waarde- en  levensovertuiging</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>