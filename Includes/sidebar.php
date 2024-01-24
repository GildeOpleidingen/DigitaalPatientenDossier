
<html>
	<head>
		<meta charset="utf-8">
		<style>
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Poppins', sans-serif;
            }

            ::-webkit-scrollbar {
                width: 10px;
            }
            
            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }
            
            ::-webkit-scrollbar-thumb {
                background: #888;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: #555;
            }


            .sidebar{
                height: 100vh;
                min-width: 200px;
                padding-top: 80px;
                background-color: white;
                text-align: center;
                display: flex;
                flex-direction: column;
                gap: 20px;
                color: #00365E;
                overflow: auto;
            }

            .personal-information{
                font-weight: bold;
            }

            #birth{
                font-weight: normal;
            }

            .profile-img{
                height: 200px;
                padding: 20px;
            }

            .profile-img img{
                height: 160px;
                border-radius: 50%;
                border: 1px solid #00365E;
            }

            .cpr{
                height: 60px;
                padding: 20px;
                display: flex;
                justify-content: space-around;
                align-items: center;
            }

            .cpr-btn{
                color: white;
                cursor: pointer;
                border: none;
                background-color: #17C542;
            }

            .cpr div{
                height: 40px;
            }

            .cpr img{
                height: 40px;
            }

            ul{
                display: flex;
                padding: 20px;
                flex-direction: column;
                gap: 20px;
                list-style-type: none;
            }

            a{
                color: #00365E;
                text-decoration: none;
                list-style-type: none;
            }

            .copyright{
                font-size: 10px;
            }

            </style>
	</head>
  
	<body>
            <div class="sidebar">
            <?php
            $id = $_SESSION['clientId'];

            $result = DatabaseConnection::getConn()->query("SELECT naam, geboortedatum, reanimatiestatus, foto FROM client WHERE id = '$id'");
            $row1 = mysqli_fetch_array($result);
?>
                <div class="profile">
                    <div class="profile-img">
                        <?php
                        if ($row1[3] != null) {
                            echo "<img src='data:image/jpeg;base64, " . base64_encode($row1[3]) . "' alt='profile picture'>";
                        } else {
                            echo "<img src=' data:image/webp;base64,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' alt='profile picture'>";
                        }
                        ?>
                    </div>
                    <div class="personal-information">
                        <p><?php echo $row1[0];?></p>
                        <p id="birth"><?php 
                        echo $row1[1];
                        $geboortedatum = $row1[1];
                        $vandaag = date("Y-m-d");
                        $leeftijd = date_diff(date_create($geboortedatum), date_create($vandaag))->format('%Y');
                         echo " ($leeftijd)";
                        ?></p>
                    </div>
                </div>
                    <button class="cpr-btn"><div class="cpr">
                        <div><img src=" data:image/png;base64,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"></div><p>Wel reanimeren</p>
                    </div></button> 
                
                <ul>
                    <?php
                    $id = $_GET['id'];

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
                        echo "<a href='../$value/$key.php?id=$id' class='$selected' id='$key'>$value</a>";
                    }
                    ?>
                </ul>
                <p class="copyright">©copyright 2023 Gildezorgcollege</p>
            </div>
	</body>
</html>