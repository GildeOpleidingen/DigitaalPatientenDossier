<?php
session_start();
require_once('../../includes/auth.php');
include '../../database/DatabaseConnection.php';
require '../../models/autoload.php';
$Meting = new Meting();

$clientId = $_SESSION['clientId'];

if (!isset($clientId) || !isset($_SESSION['loggedin_id'])) {
    header("Location: ../../index.php");
}

$medewerkers = [];
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

$metingtijden = DatabaseConnection::getConn()->prepare("SELECT m.id, m.datumtijd, vr.id as verzorgerregelid, vr.medewerkerid as medewerkerid
                                                            FROM meting m
                                                            LEFT JOIN verzorgerregel vr on m.verzorgerregelid = vr.id 
                                                            WHERE clientid = ? ORDER BY datumtijd ASC");
$metingtijden->bind_param("i", $_SESSION['clientId']);
$metingtijden->execute();
$metingtijden = $metingtijden->get_result()->fetch_all(MYSQLI_ASSOC);

$metingen = $Meting->getMeting($metingtijden);

foreach ($metingen[1] as $meting) {
    foreach ($meting as $data) {
        switch ($data['meting']) {
            case 'naam':
                $medewerkers[] = $data;
                break;
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
    <link rel="stylesheet" href="../../assets/css/client/metingen.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    include_once '../../includes/n-header.php';
    ?>
    <div class="main">
        <?php
        include_once '../../includes/n-sidebar.php';
        ?>
        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <?php if(isset($_SESSION['succes'])){ ?>
                <div class="mb-3 alert alert-success" role="alert">
                    <?php echo $_SESSION['succes']; ?>
                </div>
                <?php unset($_SESSION['succes']); } ?>
                <p class="card-text">
                <div>
                    <?php echo '<a href="metingen-invullen.php"><button type="button" class="btn btn-primary mb-3">Metingen invullen</button></a>'; ?>
                </div>
                <form id="patientForm">
                    <div class="table table-bordered">
                        <table>
                            <th>Tijd</th>
                            <?php
                            foreach ($metingen[0] as $tijd) {
                                echo "<th class='p-3 text-center'>$tijd</th>";
                            }
                            ?>

                            <tr>
                                <td>Medewerker</td>
                                <?php
                                foreach ($medewerkers as $medewerker) {
                                    foreach ($medewerker as $tijd => $waarde) {
                                        if ($waarde != "naam") {
                                            if ($waarde == 0) {
                                                echo "<td></td>";
                                            } else {
                                                echo "<td class='text-center'>$waarde</td>";
                                            }
                                        }
                                    }
                                }
                                ?>
                            </tr>

                            <tr>
                                <td>Hartslag</td>
                                <?php
                                foreach ($hartslag as $hart) {
                                    foreach ($hart as $tijd => $waarde) {
                                        if ($waarde != "hartslag") {
                                            if ($waarde == 0) {
                                                echo "<td></td>";
                                            } else {
                                                echo "<td class='text-center'>$waarde bpm</td>";
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
                                                echo "<td class='text-center'>$waarde rpm</td>";
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
                                                $matchingValue = $Meting->vindGelijkeWaarde($bloeddrukhoog, $time);
                                                echo "<td class='text-center'>$matchingValue/$value</td>";
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
                                                echo "<td class='text-center'>$waarde °C</td>";
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
                                                echo "<td class='text-center'>$waarde</td>";
                                            }
                                        }
                                    }
                                }
                                ?>
                            </tr>
                            <tr>
                                <th>
                                <td>⠀</td>
                                </th>
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
                                                echo "<td class='text-center'>$waarde ml</td>";
                                            }
                                        }
                                    }
                                }
                                ?>
                            </tr>
                            <tr>
                                <td>Ontlasting</td>
                                <?php
                                foreach ($samenstelling as $samen) {
                                    foreach ($samen as $tijd => $waarde) {
                                        if ($waarde != "samenstelling") {
                                            if ($waarde == 0) {
                                                echo "<td></td>";
                                            } else {
                                                echo "<td class='text-center'>Type $waarde</td>";
                                            }
                                        }
                                    }
                                }
                                ?>
                            </tr>
                            <tr>
                                <td>Hoeveelheid ontlasting</td>
                                <?php
                                foreach ($uitscheiding as $value) {
                                    foreach ($value as $tijd => $waarde) {
                                        if ($waarde != "uitscheiding") {
                                            if ($waarde == "") {
                                                echo "<td></td>";
                                            } else {
                                                echo "<td class='text-center'>$waarde</td>";
                                            }
                                        }
                                    }
                                }
                                ?>
                            </tr>
                            <tr>
                                <td>Hoeveelheid urine</td>
                                <?php
                                foreach ($hoeveelheid as $hoeveel) {
                                    foreach ($hoeveel as $tijd => $waarde) {
                                        if ($waarde != "hoeveelheid") {
                                            if ($waarde == 0) {
                                                echo "<td></td>";
                                            } else {
                                                echo "<td class='text-center'>$waarde ml</td>";
                                            }
                                        }
                                    }
                                }
                                ?>

                            </tr>
                            <tr>
                                <th>
                                <?php 
                                foreach ($metingtijden as $meting) {
                                    echo "<td class='text-center'><a href='meting.php?m=".$meting['id']."' class='text-decoration-none'><i class='fa-solid fa-pen-to-square'></i></a></td>";
                                }
                                ?>
                                </th>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
</body>

</html>