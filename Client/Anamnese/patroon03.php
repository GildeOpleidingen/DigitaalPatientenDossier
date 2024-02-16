
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
            <div class="pages">3 Uitscheidingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Heeft u problemen met ontlasting?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-1">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Wat doet u om deze problemen te?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>- Gebruikt u iets om uw stoelgang te reguleren?</p>
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
                        <div class="question"><p>Heeft u problemen ten aanzien van het urineren?</p>
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
                        <div class="question"><p>- Heeft u last van incontinentie?</p>
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
                        <div class="question"><p>- Bent u hiervoor in behandeling?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-5">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-5">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Hebt u last van transpiratie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-6">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-6">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Colon-obstipatie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Subjectief ervaren obstipatie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Diarree</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Incontinentie van feces</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verstoorde urine-uitscheiding</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Functionele urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Reflex-urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Stress-urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Volledige urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Urineretentie</p></div></div>
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