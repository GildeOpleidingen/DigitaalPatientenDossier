<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/MetingenFunctions.php';

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$hartslag = [];
$ademhaling = [];
$bloeddruklaag = [];
$temperatuur = [];
$vochtinname = [];
$pijn = [];
$bloeddrukhoog = [];
$samenstelling = [];
$hoeveelheid = [];

if (!isset($id)) {
    header("Location: ../../index.php");
}

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: ../../index.php");
}

$samenStellingen = DatabaseConnection::getConn()->query("SELECT id, type, uiterlijk FROM samenstelling")->fetch_all(MYSQLI_ASSOC);

$verzorgerregelid = DatabaseConnection::getConn()->query("SELECT id FROM verzorgerregel WHERE medewerkerid = $id")->fetch_array()[0];

$metingtijden = DatabaseConnection::getConn()->query("SELECT id, datumtijd FROM meting WHERE verzorgerregelid = $verzorgerregelid ORDER BY datumtijd ASC")->fetch_all(MYSQLI_ASSOC);

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
            <?php echo '<a href="metingeninvullen.php?id='.$id.'"><button type="button" class="MetingenInvul">Metingen invullen</button></a>'; ?>
            <?php echo '<a href="metingen.php?id='.$id.'"><button type="button" class="MetingenTabel">Metingen bekijken</button></a>'; ?>
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
                                    echo "<td>$waarde bpm</td>";
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
                                    echo "<td>$waarde rpm</td>";
                                }
                            }
                        }
                        ?>
                    </tr>
                    <tr>
                        <td>Bloeddruk laag</td>
                        <?php
                        foreach ($bloeddruklaag as $bloeddruk) {
                            foreach ($bloeddruk as $tijd => $waarde) {
                                if ($waarde != "bloeddruklaag") {
                                    echo "<td>$waarde</td>";
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
                                    echo "<td>$waarde °C</td>";
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
                                    echo "<td>$waarde ml</td>";
                                }
                            }
                        }
                        ?>
                    </tr>
                    <tr>
                        <td>Pijn</td>
                        <?php
                        foreach ($pijn as $pijn) {
                            foreach ($pijn as $tijd => $waarde) {
                                if ($waarde != "pijn") {
                                    echo "<td>$waarde</td>";
                                }
                            }
                        }
                        ?>
                    </tr>
                    <tr>
                        <td>Bloeddruk hoog</td>
                        <?php
                        foreach ($bloeddrukhoog as $bloeddruk) {
                            foreach ($bloeddruk as $tijd => $waarde) {
                                if ($waarde != "bloeddrukhoog") {
                                    echo "<td>$waarde</td>";
                                }
                            }
                        }
                        ?>
                    </tr>
                    <tr>
                        <td>Samenstelling</td>
                        <?php
                        foreach ($samenstelling as $samenstelling) {
                            foreach ($samenstelling as $tijd => $waarde) {
                                if ($waarde != "samenstelling") {
                                    echo "<td>$waarde</td>";
                                }
                            }
                        }
                        ?>
                    </tr>
                    <tr>
                        <td>Hoeveelheid</td>
                        <?php
                        foreach ($hoeveelheid as $hoeveelheid) {
                            foreach ($hoeveelheid as $tijd => $waarde) {
                                if ($waarde != "hoeveelheid") {
                                    echo "<td>$waarde ml</td>";
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