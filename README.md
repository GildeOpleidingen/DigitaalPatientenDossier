# DigitaalPatientenDossier

Voor de database connectie te fixen:
- maak een nieuwe file genaamd `config.php` in de root folder
- kopieer de content van `default-config.php` naar `config.php`
- vul de credentials van je database in `config.php`

## Push aub geen database credentials meer naar de repository

# Werkwijze

- Maak een eigen feature branch.
- Als de code akkoord is voor jouw een PR aanbieden naar de test branch.
- Als de PR akkoord is door het team. Dan mergen naar test
- Indien de klant / product owner akkoord heeft gegeven op test. 
- Een PR aanbieden naar main
- Dit controleren en indien akkoord door het team. Mergen naar main.

Als de code in test staat wordt dit beschikbaar op de volgende url.
test.digitaalpatientendossier.gds.local

Als de code in main staat wordt dit beschikbaar op de volgende url.
digitaalpatientendossier.gds.local

Om phpmyadmin te benaderen via de url.
pma.digitaalpatientendossier.gds.local

# Deployen

Deployment worden geregeld door GitHub Actions
Er zijn op dit moment twee workflows.
Eentje voor de test omgeving
Eentje voor de productie omgeving.

Deze staan in de map .github/workflows/
