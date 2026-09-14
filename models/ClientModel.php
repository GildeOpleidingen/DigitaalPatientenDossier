<?php

class ClientModel
{
    private mysqli $db;

    public function __construct(?mysqli $db = null)
    {
        $this->db = $db ?? DatabaseConnection::getConn();
    }

    /**
     * Haal één cliënt op basis van ID op, inclusief afdelingsnaam.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, a.naam AS afdeling
            FROM client c
            LEFT JOIN afdelingen a ON a.id = c.afdeling_id
            WHERE c.id = ?
            LIMIT 1
        ");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $client ?: null;
    }

    public function getClientById($clientId): array
    {
        return $this->getById((int)$clientId) ?? [];
    }
}
