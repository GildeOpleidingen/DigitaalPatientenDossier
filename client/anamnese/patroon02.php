<?php
session_start();
require '../../database/DatabaseConnection.php';
require_once '../../models/autoload.php';

$Main = new Main();
$db = DatabaseConnection::getConn();

// --- 1. Ophalen eerder opgeslagen antwoorden ---
$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 2);
$boolArrayObservatie = isset($antwoorden['observatie'])
    ? str_split($antwoorden['observatie'])
    : array_fill(0, 6, 0);

// --- 2. Formulier verwerken ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    // Veilig ophalen waarden
    $eetlust           = intval($_POST['eetlust'] ?? 0);
    $dieet             = intval($_POST['dieet'] ?? 0);
    $dieetWelk         = trim($_POST['dieet_welk'] ?? "");
    $gewichtVerandert  = intval($_POST['gewicht_verandert'] ?? 0);
    $moeilijkSlikken   = intval($_POST['moeilijk_slikken'] ?? 0);
    $gebitsProblemen   = intval($_POST['gebitsproblemen'] ?? 0);
    $gebitsProthese    = intval($_POST['gebitsprothese'] ?? 0);
    $huidProblemen     = intval($_POST['huidproblemen'] ?? 0);
    $gevoel            = intval($_POST['gevoel'] ?? 0);

    // Observatie array (6 checkboxes → string zoals "101010")
    $observatieArray = [];
    for ($i = 1; $i <= 6; $i++) {
        $observatieArray[] = isset($_POST['observatie' . $i]) ? "1" : "0";
    }
    $observatie = implode("", $observatieArray);

    $medewerkerId = $_SESSION['loggedin_id'];

    // --- 3. Ophalen of er al een vragenlijst bestaat ---
    $stmt = $db->prepare("
        SELECT vl.id 
        FROM vragenlijst vl 
        JOIN verzorgerregel vr ON vr.id = vl.verzorgerregelid
        WHERE vr.clientid = ?
    ");
    $stmt->bind_param("i", $_SESSION['clientId']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        $vragenlijstId = $row['id'];
    } else {
        // Insert nieuwe vragenlijst
        $stmt = $db->prepare("
            INSERT INTO vragenlijst (verzorgerregelid)
            SELECT id 
            FROM verzorgerregel 
            WHERE clientid = ? AND medewerkerid = ?
        ");
        $stmt->bind_param("ii", $_SESSION['clientId'], $medewerkerId);
        $stmt->execute();

        // Opnieuw ophalen
        $stmt = $db->prepare("
            SELECT vl.id 
            FROM vragenlijst vl 
            JOIN verzorgerregel vr ON vr.id = vl.verzorgerregelid
            WHERE vr.clientid = ?
        ");
        $stmt->bind_param("i", $_SESSION['clientId']);
        $stmt->execute();
        $vragenlijstId = $stmt->get_result()->fetch_assoc()['id'];
    }

    // --- 4. Kijken of patroon02 al bestaat ---
    $stmt = $db->prepare("
        SELECT id 
        FROM patroon02voedingstofwisseling 
        WHERE vragenlijstid = ?
    ");
    $stmt->bind_param("i", $vragenlijstId);
    $stmt->execute();
    $patroonRow = $stmt->get_result()->fetch_assoc();

    // --- 5. UPDATE of INSERT ---
    if ($patroonRow) {

        // UPDATE
        $stmt = $db->prepare("
            UPDATE patroon02voedingstofwisseling
            SET 
                eetlust=?, 
                dieet=?, 
                dieet_welk=?, 
                gewicht_verandert=?, 
                moeilijk_slikken=?, 
                gebitsproblemen=?, 
                gebitsprothese=?, 
                huidproblemen=?, 
                gevoel=?, 
                observatie=?
            WHERE vragenlijstid=?
        ");

        $stmt->bind_param(
            "iisiiiiisii",
            $eetlust,
            $dieet,
            $dieetWelk,
            $gewichtVerandert,
            $moeilijkSlikken,
            $gebitsProblemen,
            $gebitsProthese,
            $huidProblemen,
            $gevoel,
            $observatie,
            $vragenlijstId
        );

    } else {

        // INSERT
        $stmt = $db->prepare("
            INSERT INTO patroon02voedingstofwisseling
            (
                vragenlijstid,
                eetlust,
                dieet,
                dieet_welk,
                gewicht_verandert,
                moeilijk_slikken,
                gebitsproblemen,
                gebitsprothese,
                huidproblemen,
                gevoel,
                observatie
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiisiiiiis",
            $vragenlijstId,
            $eetlust,
            $dieet,
            $dieetWelk,
            $gewichtVerandert,
            $moeilijkSlikken,
            $gebitsProblemen,
            $gebitsProthese,
            $huidProblemen,
            $gevoel,
            $observatie
        );
    }

    $stmt->execute();

    // --- 6. Navigatie ---
    if ($_POST['navbutton'] === "next") {
        header("Location: patroon03.php");
    } else {
        header("Location: patroon01.php");
    }
    exit;
}

// Functie om XSS te voorkomen
function e($v) {
    return htmlspecialchars($v ?? "", ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anamnese — Voedings- en Stofwisselingspatroon</title>

    <link rel="stylesheet" href="../../assets/css/client/patronen.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body style="overflow: hidden;">

<form action="" method="post">
    <div class="main">

        <?php include '../../includes/n-header.php'; ?>
        <?php include '../../includes/n-sidebar.php'; ?>

        <div class="mt-5 pt-5 content">
            <div class="mt-4 mb-3 bg-white p-4" style="height: 90%; overflow: auto;">

                <h4 class="text-primary mb-4">2. Voedings- en stofwisselingspatroon</h4>

                <div class="form-content">
                    <div class="form">

                        <!-- EETLUST -->
                        <div class="question mb-3">
                            <p>Hoe is uw eetlust nu?</p>
                            <div class="checkboxes d-flex gap-3 flex-wrap">
                                <label>
                                    <input type="radio" name="eetlust" value="0"
                                           <?= ($antwoorden['eetlust'] ?? 0) == 0 ? "checked" : "" ?>>
                                    Normaal
                                </label>

                                <label>
                                    <input type="radio" name="eetlust" value="1"
                                           <?= ($antwoorden['eetlust'] ?? "") == 1 ? "checked" : "" ?>>
                                    Slecht
                                </label>

                                <label>
                                    <input type="radio" name="eetlust" value="2"
                                           <?= ($antwoorden['eetlust'] ?? "") == 2 ? "checked" : "" ?>>
                                    Overmatig
                                </label>
                            </div>
                        </div>

                        <!-- DIEET -->
                        <div class="question mb-3">
                            <p>Heeft u een dieet?</p>

                            <div class="checkboxes d-flex flex-column">

                                <label class="d-flex align-items-center gap-2">
                                    <input type="radio" name="dieet" value="1"
                                           <?= ($antwoorden['dieet'] ?? 0) == 1 ? "checked" : "" ?>>
                                    Ja
                                    <textarea name="dieet_welk"
                                              class="form-control ms-2"
                                              style="max-width: 250px; height: 30px;"
                                              placeholder="En wel?"><?= e($antwoorden['dieet_welk'] ?? "") ?></textarea>
                                </label>

                                <label>
                                    <input type="radio" name="dieet" value="0"
                                           <?= ($antwoorden['dieet'] ?? 0) == 0 ? "checked" : "" ?>>
                                    Nee
                                </label>

                            </div>
                        </div>

                        <!-- GEWICHT -->
                        <div class="question mb-3">
                            <p>Is uw gewicht de laatste tijd veranderd?</p>
                            <div class="checkboxes d-flex gap-3">
                                <label>
                                    <input type="radio" name="gewicht_verandert" value="1"
                                           <?= ($antwoorden['gewicht_verandert'] ?? 0) == 1 ? "checked" : "" ?>>
                                    Ja
                                </label>

                                <label>
                                    <input type="radio" name="gewicht_verandert" value="0"
                                           <?= ($antwoorden['gewicht_verandert'] ?? 0) == 0 ? "checked" : "" ?>>
                                    Nee
                                </label>
                            </div>
                        </div>

                        <!-- MOEILIJK SLIKKEN -->
                        <div class="question mb-3">
                            <p>Heeft u moeite met slikken?</p>
                            <div class="checkboxes d-flex gap-3">
                                <label>
                                    <input type="radio" name="moeilijk_slikken" value="1"
                                           <?= ($antwoorden['moeilijk_slikken'] ?? 0) == 1 ? "checked" : "" ?>>
                                    Ja
                                </label>

                                <label>
                                    <input type="radio" name="moeilijk_slikken" value="0"
                                           <?= ($antwoorden['moeilijk_slikken'] ?? 0) == 0 ? "checked" : "" ?>>
                                    Nee
                                </label>
                            </div>
                        </div>

                        <!-- GEBITSPROBLEMEN -->
                        <div class="question mb-3">
                            <p>Heeft u gebitsproblemen?</p>
                            <div class="checkboxes d-flex gap-3">
                                <label>
                                    <input type="radio" name="gebitsproblemen" value="1"
                                           <?= ($antwoorden['gebitsproblemen'] ?? 0) == 1 ? "checked" : "" ?>>
                                    Ja
                                </label>

                                <label>
                                    <input type="radio" name="gebitsproblemen" value="0"
                                           <?= ($antwoorden['gebitsproblemen'] ?? 0) == 0 ? "checked" : "" ?>>
                                    Nee
                                </label>
                            </div>
                        </div>

                        <!-- GEBITSPROTHESE -->
                        <div class="question mb-3">
                            <p>Heeft u een gebitsprothese?</p>
                            <div class="checkboxes d-flex gap-3">
                                <label>
                                    <input type="radio" name="gebitsprothese" value="1"
                                           <?= ($antwoorden['gebitsprothese'] ?? 0) == 1 ? "checked" : "" ?>>
                                    Ja
                                </label>

                                <label>
                                    <input type="radio" name="gebitsprothese" value="0"
                                           <?= ($antwoorden['gebitsprothese'] ?? 0) == 0 ? "checked" : "" ?>>
                                    Nee
                                </label>
                            </div>
                        </div>

                        <!-- HUIDPROBLEMEN -->
                        <div class="question mb-3">
                            <p>Heeft u huidproblemen?</p>
                            <div class="checkboxes d-flex gap-3">
                                <label>
                                    <input type="radio" name="huidproblemen" value="1"
                                           <?= ($antwoorden['huidproblemen'] ?? 0) == 1 ? "checked" : "" ?>>
                                    Ja
                                </label>

                                <label>
                                    <input type="radio" name="huidproblemen" value="0"
                                           <?= ($antwoorden['huidproblemen'] ?? 0) == 0 ? "checked" : "" ?>>
                                    Nee
                                </label>
                            </div>
                        </div>

                        <!-- WARM / KOUD -->
                        <div class="question mb-3">
                            <p>Heeft u het koud of warm?</p>
                            <div class="checkboxes d-flex gap-3 flex-wrap">
                                <label>
                                    <input type="radio" name="gevoel" value="0"
                                           <?= ($antwoorden['gevoel'] ?? 0) == 0 ? "checked" : "" ?>>
                                    Normaal
                                </label>

                                <label>
                                    <input type="radio" name="gevoel" value="1"
                                           <?= ($antwoorden['gevoel'] ?? 0) == 1 ? "checked" : "" ?>>
                                    Koud
                                </label>

                                <label>
                                    <input type="radio" name="gevoel" value="2"
                                           <?= ($antwoorden['gevoel'] ?? 0) == 2 ? "checked" : "" ?>>
                                    Warm
                                </label>
                            </div>
                        </div>

                        <!-- OBSERVATIES -->
                        <div class="observation mt-4">
                            <h5 class="text-secondary mb-3">Verpleegkundige observaties bij dit patroon</h5>

                            <?php 
                            $observaties = [
                                "(Dreigend) voedingsteveel (zwaarlijvigheid)",
                                "Voedingstekort",
                                "(Dreigend) vochttekort",
                                "Falende warmteregulatie",
                                "Aspiratiegevaar",
                                "(Dreigende) huiddefect"
                            ];
                            ?>

                            <?php foreach ($observaties as $index => $label): ?>
                                <div class="question d-flex align-items-center mb-2">
                                    <input type="checkbox"
                                           name="observatie<?= $index+1 ?>"
                                           value="1"
                                           <?= ($boolArrayObservatie[$index] ?? "0") === "1" ? "checked" : "" ?>
                                           class="me-2">
                                    <p class="m-0"><?= e($label) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>

                <div class="submit mt-4 d-flex justify-content-between">
                    <button name="navbutton" value="prev" class="btn btn-secondary">
                        Vorige
                    </button>

                    <button name="navbutton" value="next" class="btn btn-primary">
                        Volgende
                    </button>
                </div>

            </div>
        </div>

    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
