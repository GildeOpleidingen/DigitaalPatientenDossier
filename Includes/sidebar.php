<html>
	<head>
		<meta charset="utf-8">
		<link rel="Stylesheet" href="includes/sidebar.css">
	</head>
  
	<body>
        <div class='container'>
            <div class="sidebar">
                <div class="profile">
                    <div class="profile-img">
                        <img src="Images\blank-avatar-photo-place-holder-600nw-1095249842.webp" alt="profile picture">
                    </div>
                    <div class="personal-information">
                        <p>Voornaam Achternaam</p>
                        <p id="birth">00-00-0000 (00 Jaar)</p>
                    </div>
                </div>
                    <button class="cpr-btn"><div class="cpr">
                        <div><img src="Images\wel_reanimeren.png"></div><p>Wel reanimeren</p>
                    </div></button> 
                
                <ul>
                    <?php
                    $pages = array(
                            "overzicht" => "Overzicht",
                            "patiëntgegevens" => "Patiëntgegevens",
                            "anamnese" => "Anamnese",
                            "zorgplan" => "Zorgplan",
                            "rapportage" => "Rapportage",
                            "metingen" => "Metingen",
                            "formulieren" => "Formulieren"
                    );

                    $currentPage = basename($_SERVER['PHP_SELF'], ".php");

                    foreach ($pages as $key => $value) {
                        $selected = ($key === $currentPage) ? "selected" : "";
                        echo "<a href='Cliënt/$value/$key.php' class='$selected' id='$key'>$value</a>";
                    }
                    ?>
                </ul>
            </div>
        </div>
	</body>
</html>