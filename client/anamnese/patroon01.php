<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();
$form = new PatternFormHelper();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 1);
$answers = $antwoorden;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        1,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon02.php");
            exit;
        case 'prev':
            header("Location: patroon11.php");
            exit;
    }
}

  $observatieLijst = [
      "Gezondheidszoekend gedrag",
      "Tekort in gezondheidsonderhoud",
      "(Dreigende) inadequate opvolging van de behandeling",
      "(Dreigend) tekort in gezondheidsinstandhouding",
      "(Dreigende) therapieontrouw",
      "Vergiftigingsgevaar",
      "Infectiegevaar",
      "Gevaar voor letsel (trauma)",
      "Verstikkingsgevaar",
      "Beschermingstekort"
  ];

  $content = '';
  $content .= $form->textarea("Hoe is uw gezondheid in het algemeen?", "algemene_gezondheid", $antwoorden['algemene_gezondheid'] ?? null);
  $content .= $form->textarea("Wat doet u om gezond te blijven?", "gezondheids_bezigheid", $antwoorden['gezondheids_bezigheid'] ?? null);
  $content .= $form->yesNoWithText("- Rookt u?", "rookt", "rookt_hoeveelheid", $antwoorden['rookt'] ?? null, $antwoorden['rookt_hoeveelheid'] ?? null, "hoeveel?");
  $content .= $form->yesNoWithText("- Drinkt u?", "drinkt", "drinkt_hoeveelheid", $antwoorden['drinkt'] ?? null, $antwoorden['drinkt_hoeveelheid'] ?? null, "hoeveel?");
  $content .= $form->yesNoWithText("Heeft u momenteel een infectie of overdraagbare besmettelijke aandoening?", "besmettelijke_aandoening", "besmettelijke_aandoening_welke", $antwoorden['besmettelijke_aandoening'] ?? null, $antwoorden['besmettelijke_aandoening_welke'] ?? null, "en wel?");
  $content .= $form->yesNoWithText("Bent u ergens allergisch voor?", "alergieen", "alergieen_welke", $antwoorden['alergieen'] ?? null, $antwoorden['alergieen_welke'] ?? null, "en wel?");
  $content .= $form->textarea("Wat denkt u dat de oorzaak is van uw huidige situatie/toestand?", "oorzaak_huidige_toestand", $antwoorden['oorzaak_huidige_toestand'] ?? null);
  $content .= $form->textarea("- Wat heeft u eraan gedaan?", "oht_actie", $antwoorden['oht_actie'] ?? null);
  $content .= $form->textarea("- Hoe effectief was dat?", "oht_hoe_effectief", $antwoorden['oht_hoe_effectief'] ?? null);
  $content .= $form->textarea("- Hoe kunnen wij u helpen?", "oht_wat_nodig", $antwoorden['oht_wat_nodig'] ?? null);
  $content .= $form->textarea("- Wat is voor u belangrijk tijdens het verblijf op deze afdeling?", "oht_wat_belangrijk", $antwoorden['oht_wat_belangrijk'] ?? null);
  $content .= $form->textarea("- Vind u het gemakkelijk om dingen te doen of te laten op advies van de arts of verpleegkundige?", "oht_reactie_op_advies", $antwoorden['oht_reactie_op_advies'] ?? null);
  $content .= $form->textarea("Wat moet u in de toekomst doen ter voorkoming van het weer ziek worden?", "preventie", $antwoorden['preventie'] ?? null);
  $content .= $form->observationSection($observatieLijst, $antwoorden['observatie'] ?? '');

  echo $form->renderLayout(
      "Anamnese – Patroon 1",
      "1. Patroon van gezondheidsbeleving en -instandhouding",
      $content,
      "patroon11.php",
      "patroon02.php"
);
