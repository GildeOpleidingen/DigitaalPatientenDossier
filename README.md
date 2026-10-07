Credentials voor Single Sign On

Toepassing id: 80f1311f-f684-42da-ae68-187bdef53c79
Object id: ffa71678-5102-4fcc-8991-5ccdc45f7abb
Email:wessel@devgdcsxyz.onmicrosoft.com
Wachtwoord: Gocu576574
CallBack URL: http://localhost/oauth/callback.php

Map-id (tenant-id):9b017957-8a64-4bca-9b6e-88b47b5f0b40
Geheim-ID : 31dd2480-8bd4-44ec-b49b-b74f9d927073
secret: q7K8Q~MwdFex6q2lpyoKA~1-7rzZyiGyWLi1iaf6

OAuth 2.0-verificatie-eindpunt (v2): https://login.microsoftonline.com/9b017957-8a64-4bca-9b6e-88b47b5f0b40/oauth2/v2.0/authorize
Token-eindpunt OAuth 2.0 (v2): https://login.microsoftonline.com/9b017957-8a64-4bca-9b6e-88b47b5f0b40/oauth2/v2.0/token


# DigitaalPatientenDossier

Voor de database connectie te fixen:
- maak een nieuwe file genaamd `config.php` in de root folder
- kopieer de content van `default-config.php` naar `config.php`
- vul de credentials van je database in `config.php`
- Verwijder alle comments en whitespaces voor de <?php tag

## Push aub geen database credentials meer naar de repository

# Werkwijze

- Maak een eigen feature branch.
- Als de code akkoord is voor jouw een PR aanbieden naar de test branch.
- Als de PR akkoord is door het team. Dan mergen naar test
- Indien de klant / product owner akkoord heeft gegeven op test. 
- Een PR aanbieden naar main
- Dit controleren en indien akkoord door het team. Mergen naar main.

Als de code in test staat wordt dit beschikbaar op de volgende url.
digitaalpatientendossier-test.gdcs.nl

Als de code in main staat wordt dit beschikbaar op de volgende url.
digitaalpatientendossier.gdcs.nl

Om phpmyadmin te benaderen via de url.
phpmyadmin.gdcs.nl

# Deployment
Deployment worden geregeld door jenkins (jenkins.gdcs.nl)
Er zijn op dit moment drie workflows.
Eentje voor de dev omgeving https://digitaalpatientendossier-dev.gdcs.nl/
Eentje voor de test omgeving https://digitaalpatientendossier-test.gdcs.nl/
Eentje voor de productie omgeving. https://digitaalpatientendossier.gdcs.nl/
