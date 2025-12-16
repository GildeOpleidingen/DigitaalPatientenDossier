<?php
trait Afdeling
{
    public function getAfdelingIDByName($afdeling): int
    {
        $result = DatabaseConnection::getConn()->prepare("
            SELECT id
            FROM afdelingen
            where naam =  ?");

        $result->bind_param("s", $afdeling);
        $result->execute();

        $afdeling = $result->get_result()->fetch_assoc();

        return $afdeling['id'];
        
    }

}