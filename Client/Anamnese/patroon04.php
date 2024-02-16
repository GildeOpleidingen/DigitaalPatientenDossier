
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
            <div class="pages">4 Activiteitenpatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>In hoeverre bent u in staat de volgende activiteiten te doen?</p></div>
                        <div class="question"><p><i>0 - volledige zelfzorg<br>1 - gebruik van hulpmiddelen of plan<br>2 - vereist assistentie/supervisie van anderen<br>3 - Vereidst gebruik van hulpmiddelen of plan/methoden en assistentie van anderen<br>4 - is afhankelijk en/of participeert niet)</i></p></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Voeding</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Aankleden</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Algemene mobiliteit</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Koken</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Huishouden</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Financiën</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Verzorging</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Baden</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Toiletgang</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Uit bed komen</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><div class="observe"><input type="checkbox"><p>Winkelen</p></div><input type="number" min="0" max="4"></div>
                        <div class="question"><p>Neemt u meer tijd voor uzelf wanneer u dat nodig heeft?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-1">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="blijkt uit?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Wat zijn uw belangrijkste dagelijkse activiteiten?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>- Heeft u dagelijkse gewoonten?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-2">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-2">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er lichamelijke beperkingen waardoor u in uw activiteiten wordt belemmerd?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-3">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-3">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u vermoeidheidsklachten?</p>
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
                        <div class="question"><p>- Bent u de afgelopen tijd passiever geworden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-5">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="blijkt uit?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-5">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u problemen met het starten van de dag?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-6">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="blijkt uit?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-6">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u hobby's, doet u aan sport?</p>
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
                        <div class="question"><p>- Hoeveel tijd per dag besteedt u aan hobby's, vrijetijdsinvulling?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>Zijn er activiteiten weggevallen  als gevolg van uw huidige problemen?</p>
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

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigend) verminderd activiteitsvermogen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Oververmoeidheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Mobiliteitstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Ontspanningstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Moeheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verminderd huishoudvermogen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Volledig tekort aan persoonlijke zorg</p></div></div>
                        </div>
                        <div class="observation">
                            <div class="question"><p>Zelfstandigheidstekort in:</p></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Wassen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Kleding/verzorging</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Eten</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Toiletgang</p></div></div>
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