<?php

function getMeting($metingtijden){
    $metingen = [];
    $tijden = [];
    foreach ($metingtijden as $metingtijd) {
        $datumtijd = $metingtijd['datumtijd'];
        $metingid = $metingtijd['id'];
        $tijd = date('H:i', strtotime($datumtijd));
        $tijden[] = $tijd;
        $query = "(SELECT
            'hartslag' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN hartslag ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = 1)

            UNION
            
            (SELECT
            'ademhaling' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN ademhaling ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = 1)

            UNION
            
            (SELECT
            'bloeddruklaag' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN bloeddruklaag ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = 1)

            UNION
            
            (SELECT
            'temperatuur' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN temperatuur ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = 1)

            UNION
            
            (SELECT
            'vochtinname' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN vochtinname ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = 1)

            UNION
            
            (SELECT
            'pijn' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN pijn ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = 1)

            UNION
            
            (SELECT
            'bloeddrukhoog' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN bloeddrukhoog ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = 1)

            UNION
            
            (SELECT
            'samenstelling' AS metingontlasting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN samenstellingid ELSE null END) AS '$tijd'
            FROM metingontlasting
            WHERE metingid = $metingid)

            UNION
            
            (SELECT
            'hoeveelheid' AS metingurine,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN hoeveelheid ELSE null END) AS '$tijd'
            FROM metingurine
            WHERE metingid = $metingid)";
        $result = DatabaseConnection::getConn()->query($query);
        array_push($metingen, $result->fetch_all(MYSQLI_ASSOC));
    }
    $arrays = [];
    array_push($arrays, $tijden);
    array_push($arrays, $metingen);
    return $arrays;
}

function vindGelijkeWaarde($array, $time)
{
    foreach ($array as $measurement) {
        if (isset($measurement[$time]) && $measurement["meting"] === "bloeddrukhoog") {
            return $measurement[$time];
        }
    }
    return "";
}   



