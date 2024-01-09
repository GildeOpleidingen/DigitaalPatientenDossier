<?php
    include_once '../Database/DatabaseConnection.php';
    $id = $_GET['id'];
    $_SESSION['loggedin_id'] = $_GET['id'];

    $result = DatabaseConnection::getConn()->prepare("SELECT clientid FROM verzorgerregel WHERE medewerkerid = ?");
    $result->bind_param("s", $id);
    $result->execute();
    $links = $result->get_result()->fetch_all();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<?php
    include_once '../Includes/header.php';
?>
<div  class="main">
    <div class="users">
        <div class="list">
            <?php
                if ($links != null) {
                    foreach ($links as $link) {
                        $client = DatabaseConnection::getConn()->query("SELECT id, naam, foto FROM client WHERE id=$link[0]")->fetch_array();
                        if ($client[2] == null) {
                            echo "<div class='user'><a class='username' href='../Cliënt/clientpagina/clientpagina.php?id=$client[0]'>$client[1]</a><div class='img'>no image found</div></div>";
                            continue;
                        }

                        echo "<div class='user'><a class='username' href='../Cliënt/clientpagina/clientpagina.php?id=$client[0]'>$client[1]</a><img class='img' src='data:image/jpeg;base64," . base64_encode($client[2]) . "'/></div>";
                    }
                }
            ?>
        </div>
    </div>
</div>
</body>
</html>