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
        WHERE deleted = 0;
    ");
    } else {
        // mederwerker toont alleen zijn clients
        $items = $conn->prepare("
        SELECT client.id, naam, woonplaats, geboortedatum
        FROM client
        LEFT JOIN verzorgerregel ON client.id = verzorgerregel.clientid
        WHERE deleted = 0 AND verzorgerregel.medewerkerid = ?;
    ");
        $items->bind_param("i", $medewerkerId);
    }

    $items->execute();
    $items = $items->get_result()->fetch_all();

} else {
    $q = $conn->real_escape_string($_GET['q']); // extra veiligheid tegen SQL-injectie
    $items = $conn->query("SELECT id, naam, woonplaats, geboortedatum 
                           FROM client 
                           WHERE deleted = 0 
                           AND (naam LIKE '%$q%' OR woonplaats LIKE '%$q%' OR geboortedatum LIKE '%$q%');")->fetch_all();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënten</title>
    <link rel="stylesheet" href="../assets/css/client/client.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <p class="card-text">
                    <?php
                    if (isset($_GET['q'])) {
                        echo "<a href='client.php' class='text-decoration-none text-white fw-bold'><i class='fa-xs fa-solid fa-arrow-left'></i> Terug naar overzicht</a>";
                    }
                    ?>
                    <button onclick="window.location.href='client_toevoegen.php'">Client Toevoegen</button>
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
                        <th>Bewerken</th>
                        <th>Verwijderen</th>
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
                        echo "<td><a href='client_bewerken.php?id=$row[0]' class='btn btn-warning'>Bewerk</a></td>";
                        echo "<td><a href='client_verwijderen.php?id=$row[0]' class='btn btn-danger' onclick='return confirm(\"Weet je zeker dat je deze client wilt verwijderen?\");'>Verwijder</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>