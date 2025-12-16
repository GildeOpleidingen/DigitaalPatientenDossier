<?php
class Main {
    // Roept alle classes aan zodat je overal $Main kan aanroepen
    use Anamnese, Client, Medewerker, Convert, Formulier, Patroon, Rapportage, Zorgplan, Afdeling;
}