
<?php
session_start();
include '../../Database/DatabaseConnection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="Stylesheet" href="patronen.css">
    <title>Anamnese</title>
</head>
<body>
    <div class="main">
    <div class="main-content">
        <?php
            include '../../Includes/header.php';
        ?>
        <?php
            include '../../Includes/sidebar.php';
        ?>

        <div class="content">
            <div class="form-content">
            <div class="pages">2 Voedings- en stofwisselingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Hoe is uw eetlust nu?</p>
                            <div class="checkboxes">
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Normaal</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Slecht</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Overmatig</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u een dieet?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-2">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-2">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Is uw gewicht de laatste tijd veranderd?</p>
                            <div class="checkboxes">
                                <p>
                                    <input type="radio" name="radio-3">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-3">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u moeite met slikken?</p>
                            <div class="checkboxes">
                                <p>
                                    <input type="radio" name="radio-4">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-4">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u gebitsproblemen?</p>
                            <div class="checkboxes">
                                <p>
                                    <input type="radio" name="radio-5">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-5">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u een gebitsprothese?</p>
                            <div class="checkboxes">
                                <p>
                                    <input type="radio" name="radio-6">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-6">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u huidproblemen?</p>
                            <div class="checkboxes">
                                <p>
                                    <input type="radio" name="radio-7">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-7">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u het koud of warm?</p>
                            <div class="checkboxes">
                                <p>
                                    <input type="radio" name="radio-8">
                                    <label>Normaal</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-8">
                                    <label>Koud</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-8">
                                    <label>Warm</label>
                                </p>
                            </div>
                        </div>
                        

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigend) voedingsteveel (zwaarlijvigheid)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Voedingstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigend) vochttekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Falende warmteregulatie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Aspiratiegevaar</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigende) huiddefect</p></div></div>
                        </div>
                    </div>
                </div>
                <div class="submit">
                    <button id="vorige">< Vorige</button>
                    <button>Volgende ></button>
                </div>
            </div>
        </div>
    </div>
    </div>

</body>
</html>