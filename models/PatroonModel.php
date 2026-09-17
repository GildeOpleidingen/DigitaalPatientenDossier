<?php

class PatroonModel
{
    private static function findQuestionnaireId(int $clientId): ?int
    {
        try {
            $conn = DatabaseConnection::getConn();

            $stmt = $conn->prepare("
                SELECT vragenlijst.id
                FROM vragenlijst
                INNER JOIN verzorgerregel ON verzorgerregel.id = vragenlijst.verzorgerregelid
                WHERE verzorgerregel.clientid = ?
            ");
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result ? (int) $result['id'] : null;
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::findQuestionnaireId error: " . $e->getMessage());
            return null;
        }
    }

    private static function createQuestionnaire(int $clientId, int $employeeId): ?int
    {
        try {
            $conn = DatabaseConnection::getConn();

            $stmt = $conn->prepare("
                INSERT INTO `vragenlijst` (`verzorgerregelid`)
                VALUES ((SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))
            ");
            $stmt->bind_param("ii", $clientId, $employeeId);
            $stmt->execute();

            return $conn->insert_id ? (int) $conn->insert_id : null;
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::createQuestionnaire error: " . $e->getMessage());
            return null;
        }
    }

    public static function getQuestionnaireId(int $clientId, int $employeeId): ?int
    {
        $id = self::findQuestionnaireId($clientId);
        if ($id !== null) {
            return $id;
        }

        return self::createQuestionnaire($clientId, $employeeId);
    }

    private static array $patterns = [
        1 => [
            'table' => 'patroon01gezondheidsbeleving',
            'fields' => [
                'algemene_gezondheid'            => 's',
                'gezondheids_bezigheid'          => 's',
                'rookt'                          => 'i',
                'rookt_hoeveelheid'              => 's',
                'drinkt'                         => 'i',
                'drinkt_hoeveelheid'             => 's',
                'besmettelijke_aandoening'       => 'i',
                'besmettelijke_aandoening_welke' => 's',
                'alergieen'                      => 'i',
                'alergieen_welke'                => 's',
                'oorzaak_huidige_toestand'       => 's',
                'oht_actie'                      => 's',
                'oht_hoe_effectief'              => 's',
                'oht_wat_nodig'                  => 's',
                'oht_wat_belangrijk'             => 's',
                'oht_reactie_op_advies'          => 's',
                'preventie'                      => 's',
                'observatie'                     => 's',
            ],
        ],
        2 => [
            'table' => 'patroon02voedingstofwisseling',
            'fields' => [
                'eetlust'           => 'i',
                'dieet'             => 'i',
                'dieet_welk'        => 's',
                'gewicht_verandert' => 'i',
                'moeilijk_slikken'  => 'i',
                'gebitsproblemen'   => 'i',
                'gebitsprothese'    => 'i',
                'huidproblemen'     => 'i',
                'gevoel'            => 'i',
                'observatie'        => 's',
            ],
        ],
        3 => [
            'table' => 'patroon03uitscheiding',
            'fields' => [
                'ontlasting_probleem'                => 'i',
                'op_welke'                           => 's',
                'op_preventie'                       => 's',
                'op_medicijnen'                      => 'i',
                'op_medicijnen_welke'                => 's',
                'urineer_probleem'                   => 'i',
                'up_incontinentie'                   => 'i',
                'up_incontinentie_behandeling'       => 'i',
                'up_incontinentie_behandeling_welke' => 's',
                'transpiratie'                       => 'i',
                'transpiratie_welke'                 => 's',
                'observatie'                         => 's',
            ],
        ],
        4 => [
            'table' => 'patroon04activiteiten',
            'fields' => [
                'voeding'                         => 'i',
                'aankleden'                       => 'i',
                'alg_mobiliteit'                  => 'i',
                'koken'                           => 'i',
                'huishouden'                      => 'i',
                'financien'                       => 'i',
                'verzorging'                      => 'i',
                'baden'                           => 'i',
                'toiletgang'                      => 'i',
                'uit_bed_komen'                   => 'i',
                'winkelen'                        => 'i',
                'tijd_voor_uzelf_nodig'           => 'i',
                'tijd_voor_uzelf_nodig_blijktuit' => 's',
                'dagelijkse_activiteiten'         => 's',
                'dagelijkse_gewoontes'            => 'i',
                'dagelijkse_gewoontes_welke'      => 's',
                'lichamelijke_beperking'          => 'i',
                'lichamelijke_beperking_welke'    => 's',
                'vermoeidheids_klachten'          => 'i',
                'passiever'                       => 'i',
                'passiever_blijktuit'             => 's',
                'problemen_starten_dag'           => 'i',
                'problemen_starten_dag_blijktuit' => 's',
                'hobbys'                          => 'i',
                'hobbys_bestedingstijd'           => 's',
                'activiteiten_weggevallen'        => 'i',
                'activiteiten_weggevallen_welke'  => 's',
                'observatie'                      => 's',
            ],
        ],
        5 => [
            'table' => 'patroon05slaaprust',
            'fields' => [
                'verandering_inslaaptijd'                => 'i',
                'verandering_inslaaptijd_blijktuit'      => 's',
                'verandering_kwaliteit_slapen'           => 'i',
                'verandering_kwaliteit_slapen_blijktuit' => 's',
                'gebruik_inslaapmiddel'                  => 'i',
                'gebruik_inslaapmiddel_welke'            => 's',
                'gebruik_inslaapmiddel_anders'           => 's',
                'slaapduur'                              => 'd',
                'uitgerust_wakker'                       => 'i',
                'dromen_nachtmerries'                    => 'i',
                'rustperiodes_overdag'                   => 'i',
                'gemakkelijk_ontspannen'                 => 'i',
                'observatie'                             => 's',
            ],
        ],
        6 => [
            'table' => 'patroon06cognitiewaarneming',
            'fields' => [
                'moeilijk_horen'                    => 'i',
                'hoort_stemmen'                     => 'i',
                'hoort_stemmen_wat'                 => 's',
                'moeite_met_zien'                   => 's',
                'moeite_met_zien_wat'               => 's',
                'ruikt_iets_onverklaarbaar'         => 'i',
                'ruikt_iets_onverklaarbaar_wat'     => 's',
                'verandering_denken'                => 'i',
                'moeite_spreken'                    => 'i',
                'taal_thuis'                        => 's',
                'verandering_concentratievermogen'  => 'i',
                'moeilijker_beslissen'              => 'i',
                'verandering_geheugen'              => 'i',
                'verandering_orientatie'            => 'i',
                'invloed_medicatie'                 => 'i',
                'invloed_medicatie_welke'           => 's',
                'gebruikt_middelen'                 => 'i',
                'gebruikt_middelen_softdrugs'       => 's',
                'gebruikt_middelen_harddrugs'       => 's',
                'gebruikt_middelen_alcohol'         => 's',
                'gebruikt_middelen_anders'          => 's',
                'pijnklachten'                      => 'i',
                'pijnklachten_waar_wanneer_soort'   => 's',
                'pijnklachten_tegengaan_pijn'       => 's',
                'pijnklachten_preventie'            => 's',
                'observatie'                        => 's',
                'ziet_dingen'                       => 'i',
                'ziet_dingen_wat'                   => 's',
                'gebruikt_middelen_softdrugs_welke' => 's',
                'gebruikt_middelen_harddrugs_welke' => 's',
                'gebruikt_middelen_alcohol_welke'   => 's',
                'gebruikt_middelen_anders_welke'    => 's',
            ],
        ],
        7 => [
            'table' => 'patroon07zelfbeleving',
            'fields' => [
                'zelfbeschrijving'            => 's',
                'opkomen_voor_uzelf'          => 'i',
                'wel_niet_opkomen_blijktuit'  => 's',
                'verandering_stemming'        => 'i',
                'verandering_stemming_welke'  => 's',
                'gevoel_op_dit_moment'        => 's',
                'gevoel_op_dit_moment_anders' => 's',
                'verandering_concentratie'    => 'i',
                'verandering_denkpatroon'     => 'i',
                'ervaring_voorheen'           => 'i',
                'verandering_uiterlijk'       => 'i',
                'sensaties'                   => 'i',
                'sensaties_welk_gevoel'       => 's',
                'gevoel_momenteel'            => 's',
                'lichamelijke_energie'        => 's',
                'zelfverzorging'              => 's',
                'observatie'                  => 's',
            ],
        ],
        8 => [
            'table' => 'patroon08rollenrelatie',
            'fields' => [
                'getrouwd_samenwonend'                => 'i',
                'kinderen'                            => 'i',
                'tevreden_thuissituatie'              => 'i',
                'steun_vrienden_familie'              => 'i',
                'inkomstenbron'                       => 's',
                'verandering_fin_sit_vroeger'         => 'i',
                'verandering_fin_sit_vroeger_welke'   => 's',
                'verandering_fin_sit_toekomst'        => 'i',
                'verandering_fin_sit_toekomst_welke'  => 's',
                'opleiding'                           => 's',
                'verandering_sociale_contacten'       => 'i',
                'verandering_sociale_contacten_welke' => 's',
                'groot_gezin'                         => 'i',
                'plaats_in_gezin'                     => 's',
                'onderlinge_contacten_gezin'          => 's',
                'agressie_gezin'                      => 'i',
                'verenigingslid'                      => 'i',
                'vereniging_welke'                    => 's',
                'contact_met_derden'                  => 's',
                'verlies_geleden'                     => 'i',
                'verlies_geleden_welke'               => 's',
                'observatie'                          => 's',
            ],
        ],
        9 => [
            'table' => 'patroon09seksualiteitvoorplanting',
            'fields' => [
                'verandering_seksuele_beleving'      => 'i',
                'verandering_seksuele_beleving_door' => 's',
                'verandering_seksueel_gedrag'        => 'i',
                'wisselende_contacten'               => 'i',
                'veilig_vrijen'                      => 'i',
                'anticonceptiemiddel'                => 'i',
                'anticonceptiemiddel_welke'          => 's',
                'anticonceptiemiddel_problemen'      => 'i',
                'seksuele_gerichtheid'               => 's',
                'seksuele_gerichtheid_problemen'     => 'i',
                'soa'                                => 'i',
                'soa_welke'                          => 's',
                'observatie'                         => 's',
            ],
        ],
        10 => [
            'table' => 'patroon10stressverwerking',
            'fields' => [
                'reactie_spanningen'                     => 's',
                'reactie_anders'                         => 's',
                'spanningsvolle_situaties_voorkomen'     => 'i',
                'spanningsvolle_situaties_voorkomen_hoe' => 's',
                'spanningsvolle_situaties_oplossen'      => 'i',
                'spanningsvolle_situaties_oplossen_hoe'  => 's',
                'omstandigheden_in_war_raken'            => 'i',
                'omstandigheden_in_war_raken_welke'      => 's',
                'angstig_paniek'                         => 'i',
                'angstig_paniek_actie'                   => 's',
                'angstig_paniek_lukt_voorkomen'          => 'i',
                'suicidaal'                              => 'i',
                'suicidaal_momenteel'                    => 'i',
                'agressief'                              => 'i',
                'anderen_iets_aan_willen_doen'           => 'i',
                'maatregelen_veiligheid'                 => 'i',
                'maatregelen_veiligheid_door'            => 's',
                'moeite_uiten_gevoelens'                 => 'i',
                'bespreken_gevoelens_met'                => 's',
                'observatie'                             => 's',
            ],
        ],
        11 => [
            'table' => 'patroon11waardelevensovertuiging',
            'fields' => [
                'gelovig'                                    => 'i',
                'geloof_welk'                                => 's',
                'geloof_anders'                              => 's',
                'behoefte_religieuze_activiteit'             => 'i',
                'gebruiken_tav_geloofsovertuiging'           => 'i',
                'gebruiken_tav_geloofsovertuiging_welke'     => 's',
                'gebruiken_tav_geloofsovertuiging_wanneer'   => 's',
                'overeenkomst_waarden_normen'                => 'i',
                'etnische_achtergrond'                       => 's',
                'gebruiken_mbt_etnische_achtergrond'         => 'i',
                'gebruiken_mbt_etnische_achtergrond_welke'   => 's',
                'gebruiken_mbt_etnische_achtergrond_wanneer' => 's',
                'observatie'                                 => 's',
            ],
        ],
    ];

    public static function getPatternTable(int $patternType): ?string
    {
        return self::$patterns[$patternType]['table'] ?? null;
    }

    public static function getTableMetadata(int $patternType): ?array
    {
        return self::$patterns[$patternType]['fields'] ?? null;
    }

    public static function saveAnswers(int $clientId, int $medewerkerId, int $patternNum, array $data): bool
    {
        $tableName = self::getPatternTable($patternNum);
        $metadata = self::getTableMetadata($patternNum);
        $questionnaireId = self::getQuestionnaireId($clientId, $medewerkerId);

        if (!$tableName || !$metadata || !$questionnaireId) {
            return false;
        }

        [$columns, $types, $values] = self::filterFields($metadata, $data);
        if (empty($columns)) {
            return false;
        }

        try {
            $conn = DatabaseConnection::getConn();
            return self::hasRecord($conn, $tableName, $questionnaireId)
                ? self::executeUpdate($conn, $tableName, $questionnaireId, $columns, $types, $values)
                : self::executeInsert($conn, $tableName, $questionnaireId, $columns, $types, $values);
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::saveAnswers error: " . $e->getMessage());
            return false;
        }
    }

    private static function filterFields(array $metadata, array $data): array
    {
        $columns = [];
        $types = "";
        $values = [];

        foreach ($metadata as $column => $type) {
            // Check if the form actually submitted this column
            if (array_key_exists($column, $data)) {
                $val = $data[$column];

                // Convert/sanitize value based on the expected database type
                if ($type === 'i') {
                    $cleanedValue = ($val === '' || $val === null) ? 0 : (int)$val;
                } elseif ($type === 'd') {
                    $cleanedValue = ($val === '' || $val === null) ? 0.0 : (float)$val;
                } else {
                    $cleanedValue = ($val === null) ? '' : trim((string)$val);
                }

                $columns[] = $column;
                $types .= $type;
                $values[] = $cleanedValue;
            }
        }

        return [$columns, $types, $values];
    }

    private static function hasRecord(mysqli $conn, string $tableName, int $questionnaireId): bool
    {
        $stmt = $conn->prepare("SELECT id FROM `$tableName` WHERE `vragenlijstid` = ? LIMIT 1");
        $stmt->bind_param("i", $questionnaireId);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row !== null;
    }

    private static function executeUpdate(mysqli $conn, string $tableName, int $questionnaireId, array $columns, string $types, array $values): bool
    {
        // 1. Build the "`column` = ?" pairs
        $setParts = [];
        foreach ($columns as $column) {
            $setParts[] = "`$column` = ?";
        }
        $setClause = implode(", ", $setParts);

        // 2. Prepare the UPDATE query
        $sql = "UPDATE `$tableName` SET $setClause WHERE `vragenlijstid` = ?";
        $stmt = $conn->prepare($sql);

        // 3. Attach questionnaireId as the final integer parameter for WHERE
        $types = $types . "i";
        $values[] = $questionnaireId;

        // 4. Bind parameters and execute
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    private static function executeInsert(mysqli $conn, string $tableName, int $questionnaireId, array $columns, string $types, array $values): bool
    {
        // 1. Add 'vragenlijstid' as the very first column and value
        array_unshift($columns, 'vragenlijstid');
        array_unshift($values, $questionnaireId);
        $types = "i" . $types;

        // 2. Build column names (`col1`, `col2`) and question mark placeholders (?, ?)
        $columnNames = [];
        $questionMarks = [];
        foreach ($columns as $col) {
            $columnNames[] = "`$col`";
            $questionMarks[] = "?";
        }

        $columnList = implode(", ", $columnNames);
        $placeholders = implode(", ", $questionMarks);

        // 3. Prepare the INSERT query
        $sql = "INSERT INTO `$tableName` ($columnList) VALUES ($placeholders)";
        $stmt = $conn->prepare($sql);

        // 4. Bind parameters and execute
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    public static function getDefaultAnswers(int $patternType): array
    {
        $metadata = self::getTableMetadata($patternType);
        $defaults = [];
        if ($metadata) {
            foreach ($metadata as $col => $type) {
                $defaults[$col] = ($col === 'observatie') ? "00000000000000000000" : "";
            }
        }
        return $defaults;
    }

    public static function getAnswers(int $clientId, int $patternType): array
    {
        $tableName = self::getPatternTable($patternType);
        if (!$tableName) {
            return [];
        }

        $questionnaireId = self::findQuestionnaireId($clientId);
        if ($questionnaireId === null) {
            return self::getDefaultAnswers($patternType);
        }

        try {
            $stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM `$tableName` WHERE `vragenlijstid` = ?");
            $stmt->bind_param("i", $questionnaireId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result ?: self::getDefaultAnswers($patternType);
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::getAnswers error: " . $e->getMessage());
            return self::getDefaultAnswers($patternType);
        }
    }

    public static function getPatternTypes(): ?array
    {
        try {
            $result = DatabaseConnection::getConn()->query("SELECT * FROM `patroontype`");
            return $result ? $result->fetch_all(MYSQLI_NUM) : null;
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::getPatternTypes error: " . $e->getMessage());
            return null;
        }
    }

    public static function getPatternType(int $patternId): ?array
    {
        try {
            $stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM `zorgplan` WHERE patroontypeid = ?");
            $stmt->bind_param("i", $patternId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result ?: null;
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::getPatternType error: " . $e->getMessage());
            return null;
        }
    }

    public static function checkValue(int $value, int $min, int $max): bool
    {
        return $value >= $min && $value <= $max;
    }
}
