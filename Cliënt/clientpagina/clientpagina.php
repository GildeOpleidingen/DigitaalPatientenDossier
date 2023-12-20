<?php
    include_once '../../Database/DatabaseConnection.php';

    if(!isset($_GET['id'])) {
        header("Location: clientoverzicht.php");
    }

    $id = $_GET['id'];
    $_SESSION['clientId'] = $_GET['id'];
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="clientpagina.css">
    <title>Document</title>
</head>
<body>
<?php
    include_once '../../Includes/header.php';
       
    ?>
    <div class="main">
    <?php

    include_once '../../Includes/sidebar.php';
    ?>
    <div class="main2">
        
    </div>
    </div>
</body>
</html>