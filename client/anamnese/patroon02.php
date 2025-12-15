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
        $observatieArray[] = isset($_POST["observatie$i"]) && $_POST["observatie$i"] == 1 ? "1" : "0";
    }
    $observatie = implode("", $observatieArray);

    $medewerkerId = $_SESSION['loggedin_id'];
    try {
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

        $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);

        // --- 5. UPDATE of INSERT ---
        if ($antwoorden) {

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
    } catch (Exception $e) {
            echo "Fout bij opslaan: " . $e->getMessage();
    }
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
    <form method="POST">
        <div class="main">
            <?php
            include '../../includes/n-header.php';
            include '../../includes/n-sidebar.php';
            ?>

            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">2. Voedings- en stofwisselingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Hoe is uw eetlust nu?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="0" name="eetlust" <?= $antwoorden['eetlust'] == 0 ? "checked" : "" ?>>
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="1" name="eetlust" <?= $antwoorden['eetlust'] == 1 ? "checked" : "" ?>>
                                            <label>Slecht</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="2" name="eetlust" <?= $antwoorden['eetlust'] == 2 ? "checked" : ""  ?>>
                                            <label>Overmatig</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een dieet?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="dieet" <?= $antwoorden['dieet'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" name="dieet_welk"
                                                placeholder="en wel?"><?= isset($antwoorden['dieet_welk']) ? $antwoorden['dieet_welk'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="dieet" <?= $antwoorden['dieet'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is uw gewicht de laatste tijd veranderd?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gewicht_verandert" <?= $antwoorden['gewicht_verandert'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gewicht_verandert" <?= $antwoorden['gewicht_verandert'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u moeite met slikken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeilijk_slikken" <?= $antwoorden['moeilijk_slikken'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeilijk_slikken" <?= $antwoorden['moeilijk_slikken'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u gebitsproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gebitsproblemen" <?= $antwoorden['gebitsproblemen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gebitsproblemen" <?= $antwoorden['gebitsproblemen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een gebitsprothese?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gebitsprothese" <?= $antwoorden['gebitsprothese'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gebitsprothese" <?= $antwoorden['gebitsprothese'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u huidproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="huidproblemen" <?= $antwoorden['huidproblemen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="huidproblemen" <?= $antwoorden['huidproblemen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u het koud of warm?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="0" name="gevoel" <?= $antwoorden['gevoel'] == 0 ? "checked" : "" ?>>
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="1" name="gevoel" <?= $antwoorden['gevoel'] == 1 ? "checked" : "" ?>>
                                            <label>Koud</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="2" name="gevoel" <?= $antwoorden['gevoel'] == 1 ? "checked" : "" ?>>
                                            <label>Warm</label>
                                        </p>
                                    </div>
                                </div>


                                <div class=" observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie1" <?= isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == 1 ? "checked" : "" ?>>
                                            <p>(Dreigend) voedingsteveel (zwaarlijvigheid)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie2" <?= isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == 1 ? "checked" : "" ?>>
                                            <p>Voedingstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie3" <?= isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == 1 ? "checked" : "" ?>>
                                            <p>(Dreigend) vochttekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie4" <?= isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == 1 ? "checked" : "" ?>>
                                            <p>Falende warmteregulatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie5" <?= isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == 1 ? "checked" : "" ?>>
                                            <p>Aspiratiegevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie6" <?= isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == 1 ? "checked" : "" ?>>
                                            <p>(Dreigende) huiddefect</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit">
                            <button name="navbutton" class="btn btn-secondary" type="submit" value="prev">Vorige</button>
                            <button name="navbutton" class="btn btn-secondary" type="submit" value="next">Volgende</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
