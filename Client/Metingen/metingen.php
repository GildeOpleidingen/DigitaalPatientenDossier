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
<<<<<<< HEAD
        <div class="btns">
            <?php echo '<a href="metingen.php?id=' . $id . '"><button type="button" class="MetingenInvul">Metingen invullen</button></a>'; ?>
            <?php echo '<a href="metingenTabel.php?id=' . $id . '"><button type="button" class="MetingenTabel">Metingen bekijken</button></a>'; ?>
        </div>
        <form id="patientForm" method="POST">
            <!-- metingen -->
            <label for="Hartslag">Hartslag:</label>
            <input type="number" id="hartslag" name="hartslag" placeholder="slagen per minuut" required min="0"
                   max="200"> <!-- o tot 200 -->

            <label for="Ademhaling">Ademhaling:</label>
            <input type="number" id="ademhaling" name="ademhaling" placeholder="tussen 0 , 80" required min="0"
                   max="80"> <!-- o tot 80 -->

            <div class="bloeddrukken">
                <div class="bloeddruk-lengte">
                    <label for="Bloed druk">Bloeddruk Laag:</label>
                    <input type="text" id="bloeddruk" name="bloeddruk" placeholder="Laag" required min="0" max="140">
                    <!-- o tot 140 -->
                </div>
                <div class="bloeddruk-lengte">
                    <label for="Bloed druk">Bloeddruk Hoog:</label>
                    <input type="text" id="bloeddruk2" name="bloeddruk2" placeholder="Hoog" required min="0" max="140">
                    <!-- o tot 140 -->
                </div>
            </div>

            <label for="Temperatuur">Temperatuur:</label>
            <input type="number" id="temperatuur" name="temperatuur" placeholder="b.v.b, 37.9" required min="34"
                   max="42"> <!-- 34° tot 42° -->

            <label for="Vochtinname">Vochtinname:</label>
            <input type="number" id="vochtinname" name="vochtinname" placeholder="Invoeren in aantal milliliters"
                   required min="0" max="5000"> <!-- o tot 5000 -->

            <div class="Uitscheidingen">
                <div class="Uitscheiding2">
                    <label for="Uitscheiding">Uitscheiding:</label>
                    <input type="number" id="uitscheiding" name="uitscheiding"
                           placeholder="Invoeren in frequentie per dag">
                </div>
                <div class="Uitscheiding2">
                    <label for="Uitscheiding">Uitscheiding bristol stool chart:</label>
                    <input type="number" id="uitscheiding2" name="uitscheiding2"
                           placeholder="Invoeren in frequentie per dag">
                </div>
            </div>

            <label for="Uitscheidingplas">Uitscheiding plas:</label>
            <input type="number" id="uitscheidingplas" name="uitscheidingPlas"
                   placeholder="Invoeren in aantal milliliters" required>

            <label for="UitscheidingSamenstelling">Uitscheiding samenstelling:</label>
            <select id="uitscheidingSamenstelling" name="uitscheidingSamenstelling" required>
                <?php
                foreach ($samenStellingen as $samenStelling) {
                    echo "<option value='$samenStelling[id]'>$samenStelling[uiterlijk]</option>";
                }
                ?>
            </select>

            <label for="Pijnschaal">Pijnschaal:</label>
            <input type="number" id="pijnschaal" name="pijnschaal" placeholder="van 1 tot 10" required min="1" max="10">
            <br/>
            <button class="metingButton" type="button" onclick="submit()">Submit</button>
        </form>

    </div>
    <script>
        const submitButton = document.getElementById('submitButton');
        const hartslag = document.getElementById('hartslag');
        const ademhaling = document.getElementById('ademhaling');
        const bloeddruk = document.getElementById('bloeddruk');
        const bloeddruk2 = document.getElementById('bloeddruk2');
        const temperatuur = document.getElementById('temperatuur');
        const vochtinname = document.getElementById('vochtinname');
        const uitscheiding = document.getElementById('uitscheiding');
        const uitscheiding2 = document.getElementById('uitscheiding2');
        const uitscheidingplas = document.getElementById('uitscheidingplas');
        const pijnschaal = document.getElementById('pijnschaal');


        hartslag.addEventListener('input', hartslagUpdate);
        ademhaling.addEventListener('input', ademHalingUpdate);
        bloeddruk.addEventListener('input', bloeddrukUpdate);
        bloeddruk2.addEventListener('input', bloeddruk2Update);
        temperatuur.addEventListener('input', temperatuurUpdate);
        vochtinname.addEventListener('input', vochtinnameUpdate);
        uitscheiding.addEventListener('input', uitscheidingUpdate);
        uitscheiding2.addEventListener('input', uitscheiding2Update);
        uitscheidingplas.addEventListener('input', uitscheidingplasUpdate);
        pijnschaal.addEventListener('input', pijnschaalUpdate);

        function hartslagUpdate() {
            if (hartslag) {
                if (hartslag.value < 0 || hartslag.value > 200) {
                    hartslag.style.border = '5px solid red';
                } else {
                    hartslag.style.border = '1px solid black';
                }
            } else {
                console.log("error");
            }
        }

        function ademHalingUpdate() {
            if (ademhaling) {
                if (ademhaling.value < 0 || ademhaling.value > 80) {
                    ademhaling.style.border = '5px solid red';
                } else {
                    ademhaling.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }

        function bloeddrukUpdate() {
            if (bloeddruk) {
                if (bloeddruk.value < 0 || bloeddruk.value > 140) {
                    bloeddruk.style.border = '5px solid red';
                } else {
                    bloeddruk.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }

        function bloeddruk2Update() {
            if (bloeddruk2) {
                if (bloeddruk2.value < 0 || bloeddruk2.value > 140) {
                    bloeddruk2.style.border = '5px solid red';
                } else {
                    bloeddruk2.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }

        function temperatuurUpdate() {
            if (temperatuur) {
                if (temperatuur.value < 34 || temperatuur.value > 42) {
                    temperatuur.style.border = '5px solid red';
                } else {
                    temperatuur.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }

        function vochtinnameUpdate() {
            if (vochtinname) {
                if (vochtinname.value < 0 || vochtinname.value > 5000) {
                    vochtinname.style.border = '5px solid red';
                } else {
                    vochtinname.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }

        function uitscheidingUpdate() {
            if (uitscheiding) {
                if (!uitscheiding.value) {
                    uitscheiding.style.border = '5px solid red';
                } else {
                    uitscheiding.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }

        function uitscheiding2Update() {
            if (uitscheiding2) {
                if (uitscheiding2.value < 1 || uitscheiding2.value > 7) {
                    uitscheiding2.style.border = '5px solid red';
                } else {
                    uitscheiding2.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }

        function uitscheidingplasUpdate() {
            if (uitscheidingplas) {
                if (!uitscheidingplas.value) {
                    uitscheidingplas.style.border = '5px solid red';
                } else {
                    uitscheidingplas.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }

        function pijnschaalUpdate() {
            if (pijnschaal) {
                if (pijnschaal.value < 0 || pijnschaal.value > 10) {
                    pijnschaal.style.border = '5px solid red';
                } else {
                    pijnschaal.style.border = '1px solid black';
                }
            } else {
                console.log('error');
            }
        }
    </script>
=======
        <div class="form-content">
            <div class="btns">
                <?php echo '<a href="metingeninvullen.php?id=' . $id . '"><button type="button" class="MetingenInvul">Metingen invullen</button></a>'; ?>
                <?php echo '<a href="metingen.php?id=' . $id . '"><button type="button" class="MetingenTabel">Metingen bekijken</button></a>'; ?>
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
                                            echo "<td>Index $waarde</td>";
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
>>>>>>> ef85ef39823d0a0596d5b0db940ab98d7a0b83e7
</body>
</html>