<?php
if(!isset($_GET['id'])) {
    header("Location: ../../index.php");
    exit;
}

include '../../Database/DatabaseConnection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="Stylesheet" href="../../Includes/header.css">
	<link rel="Stylesheet" href="cliëntverhaal.css">

    <title>Clientverhaal invullen</title>
</head>
<?php include '../../Includes/header.php'; ?>
<body>
    <main>
        <form method="POST">
            <div>Foto: <input type="file" name="foto"></div>
            <div>Introductie: <input type="text" name="introductie"></div>
            <div>Gezin en familie: <input type="text" name="gezinfamilie"></div>
            <div>Hobby's: <input type="text" name="hobbys"></div>
            <div>Omgang: <input type="text" name="belangrijkeinfo"></div>


            <input type="submit" name="submit">
        </form>
    </main>
</body>
</html>