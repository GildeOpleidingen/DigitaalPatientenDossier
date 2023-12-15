<html>
	<head>
		<meta charset="utf-8">
		<link rel="Stylesheet" href="Includes/header.css">
	</head>
  
	<body>
        <div class='container'>
            <header>
                <div class="logo">
                    <img src="Images\Untitled.png" alt="logo">
                </div>
                <div class="navbar">
                    <ul>
                        <?php
                        $pages = array("dashboard" => "Dashboard",
                        "cliënt" => "Cliënt",
                        "medewerker" => "Medewerker");

                        $currentPage = basename($_SERVER['PHP_SELF'], ".php");

                        foreach ($pages as $key => $value) {
                            $selected = ($key === $currentPage) ? "selected" : "";
                            echo "<li><a href='$value/$key.php' class='$selected' id='$key'>$value</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </header>
        </div>
	</body>
</html>