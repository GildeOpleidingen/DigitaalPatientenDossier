<?php
session_start();
include_once 'database/DatabaseConnection.php';

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: index.php");
} else {
    $medewerkerId = $_SESSION['loggedin_id'];
    $isAdmin = $_SESSION['isAdmin'];
}
if($isAdmin === false){
    $result = DatabaseConnection::getConn()->prepare("SELECT clientid FROM verzorgerregel WHERE medewerkerid = ?");
    $result->bind_param("i", $medewerkerId);
    $result->execute();
    $links = $result->get_result()->fetch_all();
} else{
    $result = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker");
    $result->execute();
    $links = $result->get_result()->fetch_all();
}






?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>

<body>
    <div class="mt-5 py-5" id="main">
        <?php
        include_once 'includes/n-header.php';
        ?>
        <div class="mt-5 container">
            <div class="row">
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

                        if ($isAdmin === true) {
                            $admin = DatabaseConnection::getConn()->query("SELECT id, naam, foto FROM medewerker WHERE id=$link[0]")->fetch_array();
                        }

                        if ($client[2] == null && $isAdmin === false) {
                            
                            echo '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                <a href="client/overzicht/overzicht.php?id='.$client[0].'" class="card border-0 rounded-0 shadow-sm text-decoration-none">
                                    <svg class="bd-placeholder-img card-img-top" width="100%" height="200" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false">
                                        <title>Placeholder</title>
                                        <rect width="100%" height="100%" fill="#868e96"></rect>
                                        <text x="50%" y="50%" text-anchor="middle" fill="#dee2e6" dy=".3em">Geen foto beschikbaar</text>
                                    </svg>
                                    <div class="card-body">
                                        <h5 class="card-title">'.$client[1].'</h5>
                                    </div>
                                </a>
                            </div>';
                            $size++;
                            continue;
                        }

                        echo '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">';

                        if ($isAdmin === true) {
                        
                            echo '<a href="medewerker/overzicht/overzicht.php?id='.$admin[0].'" class="card border-0 rounded-0 shadow-sm text-decoration-none">
                                    <img src="data:image/jpeg;base64,' . base64_encode($admin[2]) . '" class="card-img-top" style="height: 200px; object-fit: cover;" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$admin[1].'</h5>
                                    </div>
                                </a>';
                        } else {
                            
                            echo '<a href="client/overzicht/overzicht.php?id='.$client[0].'" class="card border-0 rounded-0 shadow-sm text-decoration-none">
                                    <img src="data:image/jpeg;base64,' . base64_encode($client[2]) . '" class="card-img-top" style="height: 200px; object-fit: cover;" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$client[1].'</h5>
                                    </div>
                                </a>';
                        }

                        echo '</div>';

                        $size++;

                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>