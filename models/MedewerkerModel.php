<?php

class MedewerkerModel
{
    /**
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        try {
            $stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM `medewerker` WHERE `id` = ?;");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result ?: null;
        } catch (mysqli_sql_exception $e) {
            error_log("MedewerkerModel::findById error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $email
     * @return array|null
     */
    public static function findByEmail(string $email): ?array
    {
        try {
            $stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM `medewerker` WHERE `email` = ?;");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result ?: null;
        } catch (mysqli_sql_exception $e) {
            error_log("MedewerkerModel::findByEmail error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $email
     * @param string $wachtwoord
     * @return array|null
     */
    public static function authenticate(string $email, string $wachtwoord): ?array
    {
        $user = self::findByEmail($email);

        if ($user && !empty($user['wachtwoord']) && password_verify($wachtwoord, $user['wachtwoord'])) {
            return $user;
        }

        return null;
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $class
     * @param string|null $photo
     * @param string $email
     * @param string $phoneNumber
     * @param string $password
     * @return bool
     */
    public static function update(int $id, string $name, string $class, ?string $photo, string $email, string $phoneNumber, string $password): bool
    {
        try {
            $stmt = DatabaseConnection::getConn()->prepare(
                "UPDATE `medewerker` SET `naam`=?, `klas`=?, `foto`=?, `email`=?, `telefoonnummer`=?, `wachtwoord`=? WHERE `id`=?;"
            );
            $stmt->bind_param("ssssssi", $name, $class, $photo, $email, $phoneNumber, $password, $id);
            $stmt->execute();

            return $stmt->affected_rows >= 0;
        } catch (mysqli_sql_exception $e) {
            error_log("MedewerkerModel::update error: " . $e->getMessage());
            return false;
        }
    }
}
