<?php

class PatternFormHelper
{
    // 1. Keuzerondjes genereren (met behoud van geselecteerde waarde)
    public function radioGroup(string $question, string $name, array $options, mixed $selectedValue): string
    {
        $radios = '';
        foreach ($options as $val => $label) {
            $checked = ((string)$selectedValue === (string)$val && $selectedValue !== null && $selectedValue !== '') ? 'checked' : '';
            $radios .= "<p><input type='radio' name='$name' value='$val' $checked> <label>$label</label></p>";
        }

        return "<div class='question'>
            <p>$question</p>
            <div class='checkboxes'>$radios</div>
        </div>";
    }

    // 2. Ja/Nee keuzes met tekstvak voor toelichting
    public function yesNoWithText(string $question, string $radioName, string $textName, mixed $currentRadio, ?string $currentText, string $placeholder = 'en wel?'): string
    {
        $checkedJa  = ((string)$currentRadio === '1') ? 'checked' : '';
        $checkedNee = ((string)$currentRadio === '0') ? 'checked' : '';

        $safeText   = htmlspecialchars($currentText ?? '');

        return "<div class='question'>
            <p>$question</p>
            <div class='checkboxes'>
                <div class='question-answer'>
                    <input class='radio' type='radio' name='$radioName' value='1' $checkedJa>
                    <label>Ja</label>
                    <textarea rows='1' cols='25' class='checkfield' placeholder='$placeholder' name='$textName'>$safeText</textarea>
                </div>
                <p>
                    <input type='radio' name='$radioName' value='0' $checkedNee>
                    <label>Nee</label>
                </p>
            </div>
        </div>";
    }

    // Open tekstvak vraag
    public function textarea(string $question, string $name, ?string $value, int $rows = 1, int $cols = 25): string
    {
        $val = htmlspecialchars($value ?? '');
        return "<div class='question'>
            <p>$question</p>
            <textarea rows='$rows' cols='$cols' name='$name'>$val</textarea>
        </div>";
    }

    // 3. Verpleegkundig observatieblok (10 checkboxes)
    public function observationSection(array $items, ?string $binaryObservatie = ''): string
    {
        $checkboxes = '';
        foreach ($items as $i => $label) {
            $nr = $i + 1;
            $checked = (isset($binaryObservatie[$i]) && $binaryObservatie[$i] === '1') ? 'checked' : '';
            $checkboxes .= "<div class='question'>
                <div class='observe'>
                    <input type='checkbox' name='observatie$nr' value='1' $checked>
                    <p>$label</p>
                </div>
            </div>";
        }

        return "<div class='observation'>
            <h2>Verpleegkundige observatie bij dit patroon</h2>
            $checkboxes
        </div>";
    }

    // 4. Complete pagina layout met header, sidebar en Vorige/Volgende knoppen
    public function renderLayout(string $title, string $patternTitle, string $contentHtml, string $prevUrl = '', string $nextUrl = '', ?string $alert = null): string
    {
        ob_start();
        include __DIR__ . '/../includes/n-header.php';
        $header = ob_get_clean();

        ob_start();
        include __DIR__ . '/../includes/n-sidebar.php';
        $sidebar = ob_get_clean();

        $alertBox = $alert ? "<div class='alert alert-danger mb-3'>$alert</div>" : "";
        $prevBtn  = $prevUrl ? "<button name='navbutton' class='btn btn-secondary' type='submit' value='prev'>Vorige</button>" : "";
        $nextBtn  = $nextUrl ? "<button name='navbutton' class='btn btn-secondary' type='submit' value='next'>Volgende</button>" : "";

        return "<!DOCTYPE html>
<html lang='nl'>
<head>
    <meta charset='UTF-8'>
    <title>$title</title>
    <link rel='stylesheet' href='../../assets/css/client/patronen.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>
</head>
<body style='overflow: hidden;'>
    <form method='POST'>
        <div class='main'>
            $header
            $sidebar
            <div class='mt-5 pt-5 content'>
                <div class='mt-4 mb-3 bg-white p-3' style='height: 90%; overflow: auto;'>
                    $alertBox
                    <div class='form-content'>
                        <div class='h4 text-primary'>$patternTitle</div>
                        <div class='form'>
                            <div class='questionnaire'>$contentHtml</div>
                        </div>
                        <div class='submit'>$prevBtn $nextBtn</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>";
    }
}
