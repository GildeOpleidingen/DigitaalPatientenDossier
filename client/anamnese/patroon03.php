<?php
session_start();
$_SESSION['clientId'] = 1; // Replace 1 with a valid client ID from your database

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

if (!isset($_SESSION['clientId'])) {
    die("Error: Client ID is not set in the session.");
}

try {
    $db = new DatabaseConnection();
    $conn = $db->getConnection();
    echo "Database connected successfully!";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 3);
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] !== null ? str_split($antwoorden['observatie']) : [];

if (isset($_REQUEST['navbutton'])) {
    try {
        $db = new DatabaseConnection();
        $conn = method_exists($db, 'getConnection') ? $db->getConnection() : $db->conn;

        if (!$conn) {
            throw new Exception("Database connection not established.");
        }

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // --- Build observatie string (10 checkboxes)
        $observatie = '';
        for ($i = 1; $i <= 10; $i++) {
            $observatie .= isset($_POST["observatie$i"]) ? '1' : '0';
        }

        if (strlen($observatie) !== 10) {
            $observatie = str_pad($observatie, 10, '0'); // Pad with zeros if less than 10
        }

        // --- Clean input safely
        $vragenlijstid = $_SESSION['clientId'] ?? 0;

        // Validate required fields
        if ($vragenlijstid === 0) {
            throw new Exception("Client ID is missing.");
        }

        // check if record exists
        $checkStmt = $conn->prepare("SELECT vragenlijstid FROM patroon03uitscheiding WHERE vragenlijstid = ?");
        $checkStmt->execute([$vragenlijstid]);
        $exists = $checkStmt->fetchColumn();

        if ($exists) {
            // update existing record
            $sql = "
                UPDATE patroon03uitscheiding SET
                    ontlasting_probleem = :ontlasting_probleem,
                    op_welke = :op_welke,
                    op_preventie = :op_preventie,
                    op_medicijnen = :op_medicijnen,
                    op_medicijnen_welke = :op_medicijnen_welke,
                    urineer_probleem = :urineer_probleem,
                    up_incontinentie = :up_incontinentie,
                    up_incontinentie_behandeling = :up_incontinentie_behandeling,
                    up_incontinentie_behandeling_welke = :up_incontinentie_behandeling_welke,
                    transpiratie = :transpiratie,
                    transpiratie_welke = :transpiratie_welke,
                    observatie = :observatie
                WHERE vragenlijstid = :vragenlijstid
            ";
        } else {
            // insert new record
            $sql = "
                INSERT INTO patroon03uitscheiding (
                    vragenlijstid, ontlasting_probleem, op_welke, op_preventie,
                    op_medicijnen, op_medicijnen_welke, urineer_probleem,
                    up_incontinentie, up_incontinentie_behandeling,
                    up_incontinentie_behandeling_welke, transpiratie,
                    transpiratie_welke, observatie
                ) VALUES (
                    :vragenlijstid, :ontlasting_probleem, :op_welke, :op_preventie,
                    :op_medicijnen, :op_medicijnen_welke, :urineer_probleem,
                    :up_incontinentie, :up_incontinentie_behandeling,
                    :up_incontinentie_behandeling_welke, :transpiratie,
                    :transpiratie_welke, :observatie
                )
            ";
        }

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':vragenlijstid' => $vragenlijstid,
                ':ontlasting_probleem' => isset($_POST['ontlasting_probleem']) ? 1 : 0,
                ':op_welke' => $_POST['op_welke'] ?? null,
                ':op_preventie' => $_POST['ontlasting_probleem_oplossing'] ?? null,
                ':op_medicijnen' => isset($_POST['op_medicijnen']) ? 1 : 0,
                ':op_medicijnen_welke' => $_POST['op_medicijnen_welke'] ?? null,
                ':urineer_probleem' => isset($_POST['urineer_probleem']) ? 1 : 0,
                ':up_incontinentie' => isset($_POST['up_incontinentie']) ? 1 : 0,
                ':up_incontinentie_behandeling' => isset($_POST['up_incontinentie_behandeling']) ? 1 : 0,
                ':up_incontinentie_behandeling_welke' => $_POST['up_incontinentie_behandeling'] ?? null,
                ':transpiratie' => isset($_POST['transpiratie']) ? 1 : 0,
                ':transpiratie_welke' => $_POST['transpiratie_welke'] ?? null,
                ':observatie' => $observatie
            ]);
            echo "Query executed successfully!";
        } catch (Exception $e) {
            echo "Query failed: " . $e->getMessage();
        }

        // redirect safely
        if ($_REQUEST['navbutton'] === 'next') {
            echo "Redirecting to patroon04.php";
            exit;
        } elseif ($_REQUEST['navbutton'] === 'prev') {
            echo "Redirecting to patroon02.php";
            exit;
        }
        exit;

    } catch (Exception $e) {
        echo "<div style='color:red;padding:10px;'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/patronen.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Anamnese</title>
</head>

<body style="overflow: hidden;">
<form action="" method="post">
    <div class="main">
        <?php
        include '../../includes/n-header.php';
        include '../../includes/n-sidebar.php';
        ?>
        <div class="mt-5 pt-5 content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                <div class="form-content">
                    <div class="h4 text-primary">3. Uitscheidingspatroon</div>
                    <div class="form">
                        <div class="questionnaire">
                            <div class="question">
                                <p>Heeft u problemen met ontlasting?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="ontlasting_probleem" <?= isset($antwoorden['ontlasting_probleem']) ? "checked" : "" ?>>
                                        <label>Ja</label>
                                        <textarea rows="1" cols="25" id="checkfield" placeholder="en wel?" name="op_welke"><?= isset($antwoorden['op_welke']) ? $antwoorden['op_welke'] : '' ?></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="ontlasting_probleem" <?= !isset($antwoorden['ontlasting_probleem']) ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question">
                                <p>- Wat doet u om deze problemen te bestrijden?</p>
                                <textarea rows="1" cols="25" name="ontlasting_probleem_oplossing"><?= isset($antwoorden['op_preventie']) ? $antwoorden['op_preventie'] : '' ?></textarea>
                            </div>
                            <div class="question">
                                <p>- Gebruikt u iets om uw stoelgang te reguleren?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="op_medicijnen" <?= isset($antwoorden['op_medicijnen']) ? "checked" : "" ?>>
                                        <label>Ja</label>
                                        <textarea rows="1" cols="25" id="checkfield" placeholder="en wel?" name="op_medicijnen_welke"><?= isset($antwoorden['op_medicijnen_welke']) ? $antwoorden['op_medicijnen_welke'] : '' ?></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="op_medicijnen" <?= !isset($antwoorden['op_medicijnen']) ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question">
                                <p>Heeft u problemen ten aanzien van het urineren?</p>
                                <div class="checkboxes">
                                    <p>
                                        <input type="radio" name="urineer_probleem" <?= isset($antwoorden['urineer_probleem']) ? "checked" : "" ?>>
                                        <label>Ja</label>
                                    </p>
                                    <p>
                                        <input type="radio" name="urineer_probleem" <?= !isset($antwoorden['urineer_probleem']) ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question">
                                <p>- Heeft u last van incontinentie?</p>
                                <div class="checkboxes">
                                    <p>
                                        <input type="radio" name="up_incontinentie" <?= isset($antwoorden['up_incontinentie']) ? "checked" : "" ?>>
                                        <label>Ja</label>
                                    </p>
                                    <p>
                                        <input type="radio" name="up_incontinentie" <?= !isset($antwoorden['up_incontinentie']) ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question">
                                <p>- Bent u hiervoor in behandeling?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="up_incontinentie_behandeling" <?= isset($antwoorden['up_incontinentie_behandeling']) ? "checked" : "" ?>>
                                        <label>Ja</label>
                                        <textarea rows="1" cols="25" id="checkfield" placeholder="en wel?" name="up_incontinentie_behandeling"><?= isset($antwoorden['up_incontinentie_behandeling_welke']) ? $antwoorden['up_incontinentie_behandeling_welke'] : '' ?></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="up_incontinentie_behandeling" <?= !isset($antwoorden['up_incontinentie_behandeling']) ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question">
                                <p>Hebt u last van transpiratie?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="transpiratie" <?= isset($antwoorden['transpiratie']) ? "checked" : "" ?>>
                                        <label>Ja</label>
                                        <textarea rows="1" cols="25" id="checkfield" placeholder="en wel?" name="transpiratie_welke"><?= isset($antwoorden['transpiratie_welke']) ? $antwoorden['transpiratie_welke'] : '' ?></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="transpiratie" <?= !isset($antwoorden['transpiratie']) ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>

                            <div class="observation">
                                <h2>Verpleegkundige observatie bij dit patroon</h2>
                                <?php
                                $labels = [
                                    "Colon-obstipatie", "Subjectief ervaren obstipatie", "Diarree", "Incontinentie van feces",
                                    "Verstoorde urine-uitscheiding", "Functionele urine-incontinentie", "Reflex-urine-incontinentie",
                                    "Stress-urine-incontinentie", "Volledige urine-incontinentie", "Urineretentie"
                                ];
                                for ($i = 0; $i < 10; $i++) {
                                    $checked = isset($boolArrayObservatie[$i]) && $boolArrayObservatie[$i] === '1' ? 'checked' : '';
                                    echo "<div class='question'><div class='observe'>
                                        <input type='checkbox' name='observatie" . ($i + 1) . "' $checked>
                                        <p>{$labels[$i]}</p></div></div>";
                                }
                                ?>
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
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
