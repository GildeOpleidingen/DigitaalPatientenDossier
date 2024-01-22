<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="Stylesheet" href="vragenlijst.css">
    <title>Formulieren</title>
</head>
<body>
        <?php
            include 'Includes/header.php';
        ?>
    <div class="main">
        <?php
            include 'Includes/sidebar.php';
        ?>

        <div class="content">
            <div class="form-content">
            <div class="pages">1 Patroon van gezondheidsbeleving en -instandhouding</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Hoe is uw gezondheid in het algemeen?</p><input type="text"></div>
                        <div class="question"><p>Wat doet u om gezond te blijven?</p><input type="text"></div>
                        <div class="question"><p>- Rookt u?</p>
                            <div class="checkboxes">
                                <div><input type="checkbox"> Ja</div>
                                <div><input type="checkbox"> Nee</div>
                            </div>
                        </div>
                        <div class="question"><p>- Drinkt u?</p>
                            <div class="checkboxes">
                                <div><input type="checkbox"> Ja</div>
                                <div><input type="checkbox"> Nee</div>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u momenteel een infectie of overdraagbare besmettelijke aandoening?</p>
                            <div class="checkboxes">
                                <div><input type="checkbox"> Ja</div>
                                <div><input type="checkbox"> Nee</div>
                            </div>
                        </div>
                        <div class="question"><p>Bent u ergens alergisch voor?</p>
                            <div class="checkboxes">
                                <div><input type="checkbox"> Ja</div>
                                <div><input type="checkbox"> Nee</div>
                            </div>
                        </div>
                        <div class="question"><p>Wat denkt u dat de oorzaak is van uw huidige situatie/toestand?</p><input type="text"></div>
                        <div class="question"><p>- Wat heeft u eraan gedaan?</p><input type="text"></div>
                        <div class="question"><p>- Hoe effectief was dat?</p><input type="text"></div>
                        <div class="question"><p>- Hoe kunnen wij u helpen?</p><input type="text"></div>
                        <div class="question"><p>- Wat is voor u belangrijk tijdens het verblijf op deze afdeling?</p><input type="text"></div>
                        <div class="question"><p>- Vind u het gemakkelijk om dingen te doen of te laten op advies van de arts of verpleegkundige?</p><input type="text"></div>
                        <div class="question"><p>Wat moet u in de toekomst doen ter voorkoming van het weer ziek worden?</p><input type="text"></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Gezondheidszoekend gedrag</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Tekort in gezondheidsonderhoud</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigende) inadequate opvolging van de behandeling</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigend) tekort in gezondheidsinstandhouding</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigende) therapieontrouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Vergiftigingsgevaar</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Infectiegevaar</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Gevaar voor letsel (trauma)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verstikkingsgevaar</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Beschermingstekort</p></div></div>
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

</body>
</html>