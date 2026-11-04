<?php
trait Afdeling
{
    /**
     * @param string $afdeling
     * @return int
     */
    public function getAfdelingIDByName($afdeling): int
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT id
            FROM afdelingen
            WHERE naam = :naam
            LIMIT 1
        ");
        $stmt->execute(['naam' => $afdeling]);
        $id = $stmt->fetchColumn();

        return $id !== false ? (int)$id : 0;
    }
}