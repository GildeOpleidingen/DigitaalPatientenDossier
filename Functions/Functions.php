<?php

function convertNumToBool($numbers, $index=0): ?bool {
    if ($index < mb_strlen($numbers)) {
        $num = str_split($numbers)[$index];

        if ($num == 1) {
            return true;
        }

        return false;
    }

    return null;
}

function convertNumToBoolArray($numbers): array {
    $numArr = str_split($numbers);
    $boolArr = array();

    foreach ($numArr as $num) {
        if ($num == 1) {
            $boolArr[] = true;
            continue;
        }
        $boolArr[] = false;
    }

    return $boolArr;
}
