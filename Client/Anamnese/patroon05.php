
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
            <div class="pages">5 Slaap- en rustpatroon</div>
                <div class="form">
                    <div class="questionnaire">
                    <div class="question"><p>Is er in de afgelopen periode verandering in de de duur van uw slaap gekomen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-1">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Is er verandering ontstaan in de kwaliteit van uw slaap (in- en/of doorslaapprobleem)?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-2">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-2">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Doet u iets om (in) te kunnen slapen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-3">
                                    <label>Ja</label>
                                    <div id="checkfield">
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Medicijngebruik</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Beweging</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Alcohol/drugs</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Eten/drinken</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Douche/bad</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                                    </div>
                                </div>
                                <p>
                                    <input type="radio" name="radio-3">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe lang slaapt u nomaal?</p><p><input type="number" step=0.5 min="0" max="24"> uur</p></div>
                        <div class="question"><p>- Voelt u zich uitgerust als u wakker wordt?</p>
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
                        <div class="question"><p>Heeft u last van dromen, nachtmerries?</p>
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
                        <div class="question"><p>- Neemt u rustperioden overdag?</p>
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
                        <div class="question"><p>- Kunt u zich gemakkelijk ontspannen?</p>
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

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verstoord slaap- en rustpatroon</p></div></div>
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