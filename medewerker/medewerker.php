<?php
session_start();
require_once('../includes/auth.php');
include '../database/DatabaseConnection.php';

$conn = DatabaseConnection::getConn();

if (!isset($_GET['q'])) {
    $items = $conn
        ->query("SELECT id, naam, klas, email, telefoonnummer, foto, rol 
                 FROM medewerker 
                 WHERE is_deleted = 0;")
        ->fetch_all(MYSQLI_ASSOC);
} else {
    $q = $conn->real_escape_string(trim($_GET['q']));
    $items = $conn
        ->query("SELECT id, naam, klas, email, telefoonnummer, foto, rol 
                 FROM medewerker 
                 WHERE is_deleted = 0
                 AND (naam LIKE '%$q%' 
                 OR klas LIKE '%$q%' 
                 OR email LIKE '%$q%' 
                 OR telefoonnummer LIKE '%$q%');")
        ->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker</title>

    <link rel="stylesheet" href="../assets/css/medewerker/medewerker.css">
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
</head>

<body>
    <?php include '../includes/n-header.php'; ?>

    <div class="main">
        <div class="content">
            <div class="mt-4 mb-3 p-3">

                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php
                if (isset($_GET['q'])) {
                    echo "
                <a href='medewerker.php' class='text-decoration-none text-white fw-bold d-block mb-3'>
                    <i class='fa-xs fa-solid fa-arrow-left'></i> Terug naar overzicht
                </a>";
                }
                ?>

                <!-- NIEUWE KNOP -->
                <a href="medewerker_toevoegen.php"
                    class="btn btn-primary mb-3">
                    <i class="fa fa-plus"></i> Medewerker toevoegen
                </a>

                <!-- ZOEKEN -->
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="q" class="form-control" placeholder="Zoeken...">
                        <span class="input-group-text bg-primary border-primary">
                            <i class="fa fa-search text-white"></i>
                        </span>
                    </div>
                </form>

                <!-- TABEL -->
                <table class="table">
                    <tr>
                        <th>Naam</th>
                        <th>Klas</th>
                        <th>E-mail</th>
                        <th>Telefoonnummer</th>
                        <?php if (Auth::isAdmin()): ?>
                            <th>Acties</th>
                        <?php endif; ?>
                    </tr>

                    <?php
                    if (count($items) == 0) {
                        $colspan = Auth::isAdmin() ? 5 : 4;
                        echo "<tr>
                            <td colspan='{$colspan}' class='text-center'>
                                Geen resultaten gevonden.
                            </td>
                          </tr>";
                    }

                    foreach ($items as $row) {
                        if ($_SESSION['rol'] == "medewerker" && $row['rol'] == "beheerder") {
                            continue;
                        }

                        echo "<tr>";
                        echo "<td class='row1'>
                            <a href='overzicht/overzicht.php?id={$row['id']}'>
                                " . htmlspecialchars($row['naam']) . "
                            </a>
                          </td>";
                        echo "<td class='row1'>" . htmlspecialchars($row['klas']) . "</td>";
                        echo "<td class='row1'>" . htmlspecialchars($row['email'] ?? '') . "</td>";
                        echo "<td class='row1'>" . htmlspecialchars($row['telefoonnummer'] ?? '') . "</td>";

                        if (Auth::isAdmin()) {
                            echo "<td class='row1'>";
                            echo "<a href='medewerker_bewerken.php?id={$row['id']}' 
                                     class='btn btn-warning btn-sm me-1'>
                                     <i class='fa fa-pencil'></i> Bewerken
                                  </a>";
                            if ((int)$row['id'] !== (int)$_SESSION['loggedin_id']) {
                                $safeName = htmlspecialchars(addslashes($row['naam']), ENT_QUOTES);
                                echo "<a href='medewerker_verwijderen.php?id={$row['id']}' 
                                         class='btn btn-danger btn-sm' 
                                         onclick=\"return confirm('Weet je zeker dat je medewerker \\'{$safeName}\\' wilt verwijderen?');\">
                                         <i class='fa fa-trash'></i> Verwijder
                                      </a>";
                            } else {
                                echo "<span class='badge bg-secondary'>Huidig account</span>";
                            }
                            echo "</td>";
                        }

                        echo "</tr>";
                    }
                    ?>
                </table>

            </div>
        </div>
    </div>
</body>

</html>