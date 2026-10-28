<?php
session_start();
$medewerkerId = $_SESSION['loggedin_id'];
$isAdmin = $_SESSION['isAdmin'];
include '../database/DatabaseConnection.php';

$conn = DatabaseConnection::getConn();

if (!isset($_GET['q'])) {
    // Alleen niet-verwijderde cliënten ophalen
    if ($isAdmin) {
        // Admin toont alle clients

        $items = $conn->prepare("
        SELECT client.id, naam, woonplaats, geboortedatum
        FROM client
        WHERE (is_deleted = 0 OR is_deleted IS NULL);
    ");
    } else {
        // mederwerker toont alleen zijn clients
        $items = $conn->prepare("
        SELECT client.id, naam, woonplaats, geboortedatum
        FROM client
        LEFT JOIN verzorgerregel ON client.id = verzorgerregel.clientid
        WHERE (is_deleted = 0 OR is_deleted IS NULL) AND verzorgerregel.medewerkerid = ?;
    ");
        $items->bind_param("i", $medewerkerId);
    }

    $items->execute();
    $items = $items->get_result()->fetch_all();

} else {
    $q = trim($_GET['q']);
    $searchTerm = '%' . $q . '%';

    if ($isAdmin) {
        // Admin toont alle clients die matchen met zoekopdracht
        $stmt = $conn->prepare("
            SELECT client.id, naam, woonplaats, geboortedatum 
            FROM client 
            WHERE (is_deleted = 0 OR is_deleted IS NULL) 
              AND (naam LIKE ? OR woonplaats LIKE ? OR geboortedatum LIKE ?);
        ");
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    } else {
        // Medewerker toont alleen zijn gekoppelde clients die matchen met zoekopdracht
        $stmt = $conn->prepare("
            SELECT client.id, naam, woonplaats, geboortedatum 
            FROM client 
            INNER JOIN verzorgerregel ON client.id = verzorgerregel.clientid 
            WHERE (is_deleted = 0 OR is_deleted IS NULL) 
              AND verzorgerregel.medewerkerid = ? 
              AND (naam LIKE ? OR woonplaats LIKE ? OR geboortedatum LIKE ?);
        ");
        $stmt->bind_param("isss", $medewerkerId, $searchTerm, $searchTerm, $searchTerm);
    }

    $stmt->execute();
    $items = $stmt->get_result()->fetch_all();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënten</title>
    <link rel="stylesheet" href="../assets/css/client/client.css">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
</head>

<body>
    <?php
    include '../includes/n-header.php';
    ?>

    <div class="main">
        <div class="content">
            <div class="mt-4 mb-3 p-3">
                <?php if (isset($_GET['q'])): ?>
                    <a href="client.php" class="text-decoration-none text-white fw-bold d-block mb-3">
                        <i class="fa-xs fa-solid fa-arrow-left"></i> Terug naar overzicht
                    </a>
                <?php endif; ?>

                <a href="client_toevoegen.php" class="btn btn-primary mb-3">
                    <i class="fa fa-plus me-1"></i> Cliënt toevoegen
                </a>
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="q" class="form-control" placeholder="Zoeken...">
                        <span class="input-group-text bg-primary border-primary"><i class="fa fa-search text-white"
                                aria-hidden="true"></i></span>
                    </div>
                </form>
                <table class="table table-hover table-bordered align-middle text-center">
                    <tr>
                        <th>#</th>
                        <th>Naam</th>
                        <th>Woonplaats</th>
                        <th>Geboortedatum</th>
                        <?php
                        if ($isAdmin){
                            echo "<th>Bewerken</th>";
                            echo "<th>Verwijderen</th>";
                            }

                        ?>
                    </tr>
                    <?php
                    if (count($items) == 0) {
                        echo "<tr><td colspan='6' class='text-center'>Geen resultaten gevonden.</td></tr>";
                    }
                    foreach ($items as $row) {
                        echo "<tr>";
                        echo "<td>$row[0]</td>";
                        echo "<td><a href='overzicht/overzicht.php?id=$row[0]'>$row[1]</a></td>";
                        echo "<td>$row[2]</td>";
                        echo "<td>" . date_create($row[3])->format("d-m-Y") . "</td>";
                        if ($isAdmin){
                            echo "<td><a href='client_bewerken.php?id=$row[0]' class='btn btn-warning'>Bewerk</a></td>";
                            echo "<td><a href='client_verwijderen.php?id=$row[0]' class='btn btn-danger' onclick='return confirm(\"Weet je zeker dat je deze client wilt verwijderen?\");'>Verwijder</a></td>";
                            echo "</tr>";
                        }

                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>