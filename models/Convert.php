<?php
trait Convert
{
    /**
     * @param string $numbers
     * @param int $index
     * @return bool|null
     */
    public function convertNumToBool($numbers, $index = 0): ?bool
    {
        if ($index < mb_strlen($numbers)) {
            $num = str_split($numbers)[$index];

            if ($num == 1) {
                return true;
            }

            return false;
        }

        return null;
    }

    /**
     * @param string $numbers
     * @return bool[]
     */
    public function convertNumToBoolArray($numbers): array
    {
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

    /**
     * @param bool[] $boolArr
     * @return string
     */
    public function convertBoolArrayToString($boolArr): string
    {
        $numbers = '';
        foreach ($boolArr as $bool) {
            $numbers .= $bool ? '1' : '0';
        }
        return $numbers;
    }
}
