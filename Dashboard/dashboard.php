<?php
    include_once '../Database/DatabaseConnection.php';
    $id = $_GET['id'];

    if ($id == null) {
        header("Location: ../index.php");
    }

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
<div  class="main" id="main">
    <div class="users" id="users">
        <div class="list">
            <?php
                if ($links != null) {
                    $MAX_SIZE = 21;
                    $size = 0;

                    foreach ($links as $link) {
                        if ($size >= $MAX_SIZE && $size % 7 == 0) {
                            ?>
                            <script>
                                changeDivHeight();

                                function changeDivHeight() {
                                    const main = document.getElementById("main");
                                    const users = document.getElementById("users");

                                    if (users.style.height === "")
                                        users.style.height = "48.5em";

                                    users.style.height = parseInt(users.style.height) + 17.5 + "em";

                                    if (main.style.height === "")
                                        main.style.height = "100vh";

                                    main.style.height = parseInt(main.style.height) + 29 + "vh";
                                }
                            </script>
                            <?php
                        }

                        $client = DatabaseConnection::getConn()->query("SELECT id, naam, foto FROM client WHERE id=$link[0]")->fetch_array();
                        if ($client[2] == null) {
                            echo "<div class='user'><a class='username' href='../Cliënt/clientpagina/clientpagina.php?id=$client[0]'>$client[1]</a><div class='img'>no image found</div></div>";
                            $size++;
                            continue;
                        }

                        echo "<div class='user'><a class='username' href='../Cliënt/clientpagina/clientpagina.php?id=$client[0]'>$client[1]</a><img class='img' src='data:image/jpeg;base64," . base64_encode($client[2]) . "'/></div>";
                        $size++;
                    }
                }
            ?>
        </div>
    </div>
</div>
</body>
</html>