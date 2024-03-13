<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/MetingenFunctions.php';

$clientId = $_SESSION['clientId'];

if (!isset($clientId) || !isset($_SESSION['loggedin_id'])) {
    header("Location: ../../index.php");
}

$hartslag = [];
$ademhaling = [];
$bloeddruklaag = [];
$temperatuur = [];
$vochtinname = [];
$pijn = [];
$bloeddrukhoog = [];
$uitscheiding = [];
$samenstelling = [];
$hoeveelheid = [];

$samenStellingen = DatabaseConnection::getConn()->query("SELECT id, type, uiterlijk FROM samenstelling")->fetch_all(MYSQLI_ASSOC);

$metingtijden = DatabaseConnection::getConn()->prepare("SELECT m.id, m.datumtijd, vr.id as verzorgerregelid
                                                            FROM meting m
                                                            LEFT JOIN verzorgerregel vr on m.verzorgerregelid = vr.id 
                                                            WHERE clientid = ? ORDER BY datumtijd ASC");
$metingtijden->bind_param("i", $_SESSION['clientId']);
$metingtijden->execute();
$metingtijden = $metingtijden->get_result()->fetch_all(MYSQLI_ASSOC);

$metingen = getMeting($metingtijden);

foreach ($metingen[1] as $meting) {
    foreach ($meting as $data) {
        switch ($data['meting']) {
            case 'hartslag':
                $hartslag[] = $data;
                break;
            case 'ademhaling':
                $ademhaling[] = $data;
                break;
            case 'bloeddruklaag':
                $bloeddruklaag[] = $data;
                break;
            case 'temperatuur':
                $temperatuur[] = $data;
                break;
            case 'vochtinname':
                $vochtinname[] = $data;
                break;
            case 'pijn':
                $pijn[] = $data;
                break;
            case 'bloeddrukhoog':
                $bloeddrukhoog[] = $data;
                break;
            case 'uitscheiding':
                $uitscheiding[] = $data;
                break;
            case 'samenstelling':
                $samenstelling[] = $data;
                break;
            case 'hoeveelheid':
                $hoeveelheid[] = $data;
                break;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Metingen</title>
    <link rel="stylesheet" href="metingen.css">
</head>
<body>
<?php
include_once '../../Includes/header.php';
?>
<div class="main">
    <?php
    include_once '../../Includes/sidebar.php';
    ?>
    <div class="main2">
        <div class="form-content">
            <div class="btns">
                <?php echo '<a href="metingeninvullen.php?id=' . $clientId . '"><button type="button" class="MetingenInvul">Metingen invullen</button></a>'; ?>
                <?php echo '<a href="metingen.php?id=' . $clientId . '"><button type="button" class="MetingenTabel">Metingen bekijken</button></a>'; ?>
            </div>
            <form id="patientForm">
                <div class="tabel">
                    <table>
                        <th></th>
                        <?php
                        foreach ($metingen[0] as $tijd) {
                            echo "<th>$tijd</th>";
                        }
                        ?>

                        <tr>
                            <td>Hartslag</td>
                            <?php
                            foreach ($hartslag as $hart) {
                                foreach ($hart as $tijd => $waarde) {
                                    if ($waarde != "hartslag") {
                                        if ($waarde == 0) {
                                            echo "<td></td>";
                                        } else {
                                            echo "<td>$waarde bpm</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>Ademhaling</td>
                            <?php
                            foreach ($ademhaling as $adem) {
                                foreach ($adem as $tijd => $waarde) {
                                    if ($waarde != "ademhaling") {
                                        if ($waarde == 0) {
                                            echo "<td></td>";
                                        } else {
                                            echo "<td>$waarde rpm</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>Bloeddruk</td>
                            <?php
                            foreach ($bloeddruklaag as $bloed) {
                                foreach ($bloed as $time => $value) {
                                    if ($time !== "meting") {
                                        if ($value == 0) {
                                            echo "<td></td>";
                                        } else {
                                            $matchingValue = vindGelijkeWaarde($bloeddrukhoog, $time);
                                            echo "<td>$matchingValue/$value</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>Temperatuur</td>
                            <?php
                            foreach ($temperatuur as $temp) {
                                foreach ($temp as $tijd => $waarde) {
                                    if ($waarde != "temperatuur") {
                                        if ($waarde == 0) {
                                            echo "<td></td>";
                                        } else {
                                            echo "<td>$waarde °C</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>Vochtinname</td>
                            <?php
                            foreach ($vochtinname as $vocht) {
                                foreach ($vocht as $tijd => $waarde) {
                                    if ($waarde != "vochtinname") {
                                        if ($waarde == 0) {
                                            echo "<td></td>";
                                        } else {
                                            echo "<td>$waarde ml</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>Pijn</td>
                            <?php
                            foreach ($pijn as $value) {
                                foreach ($value as $tijd => $waarde) {
                                    if ($waarde != "pijn") {
                                        if ($waarde == "") {
                                            echo "<td></td>";
                                        } else {
                                            echo "<td>$waarde</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>Uitscheiding</td>
                            <?php
                            foreach ($uitscheiding as $value) {
                                foreach ($value as $tijd => $waarde) {
                                    if ($waarde != "uitscheiding") {
                                        if ($waarde == "") {
                                            echo "<td></td>";
                                        } else {
                                            echo "<td>$waarde</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>Samenstelling</td>
                            <?php
                            foreach ($samenstelling as $samen) {
                                foreach ($samen as $tijd => $waarde) {
                                    if ($waarde != "samenstelling") {
                                        if ($waarde == 0) {
                                            echo "<td></td>";
                                        } else {
                                            echo "<td>Type $waarde</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>Hoeveelheid</td>
                            <?php
                            foreach ($hoeveelheid as $hoeveel) {
                                foreach ($hoeveel as $tijd => $waarde) {
                                    if ($waarde != "hoeveelheid") {
                                        if ($waarde == 0) {
                                            echo "<td></td>";
                                        } else {
                                            echo "<td>$waarde ml</td>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</body>
</html>