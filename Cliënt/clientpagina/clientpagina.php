<?php
$id = $_SESSION['id'] = $_GET['id'];
include '../../Database/DatabaseConnection.php';


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
        include '../../Includes/header.php';
       
    ?>
    <div class="main">
    <?php
        
        include '../../Includes/sidebar.php';
    ?>
    <div class="main2">
        
    </div>
    </div>
</body>
</html>