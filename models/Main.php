<?php

class Main
{
    // Roept alle classes aan zodat je overal $Main kan aanroepen
    use Anamnese, Client, Medewerker, Convert, Formulier, Patroon, Rapportage, Zorgplan, Afdeling;

    public ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    /**
     * Haal een cliënt op via het nieuwe ClientModel.
     */
    public function getById($clientId): ?array
    {
        return $this->clientModel->getById((int)$clientId);
    }
}
