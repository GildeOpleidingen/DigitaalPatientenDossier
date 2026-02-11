# Database fixes
```diff
- HIGH PRIORITY!
```
Graag deze kolommen aanpassen. 

## Fix voor issue #347
Ga naar de database van `dpd->medischoverzicht->opnamedatum` en zet op `null`
https://github.com/GildeOpleidingen/DigitaalPatientenDossier/issues/347

## Fix voor issue #371
Ga naar de database van `dpd->vragenlijst->afnamedatumtijd` en zet op `null`
https://github.com/GildeOpleidingen/DigitaalPatientenDossier/issues/371

# Credentials
```php
//    public static string $host = "10.250.0.103";
//    public static string $username = "dpd_user";
//    public static string $pass = "q220@Wgz0]I9uq!J"; 
//    public static string $db = "dpd_dev"; of dpd of dpv_test
```

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

# Digitaal Patienten Dossier

Voor de database connectie te fixen:
- maak een nieuwe file genaamd `config.php` in de root folder
- kopieer de content van `default-config.php` naar `config.php`
- vul de credentials van je database in `config.php`

```diff
! PUSH GEEN DATABASE CREDENTIALS NAAR DE REPOSITORY
```
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

# Backlog

 - Controleer de backlog dagelijks.
 - Overleg met elkaar als je iets vindt dat veranderd moet worden en maak zo nodig een nieuw to-do item aan.
 - Bij het aanmaken van een item, controleer of het geen "duplicate entry" is
 - geef een descriptieve titel/beschrijving
  - zet in de beschrijving neer hoe noodzakelijk deze taak is.
   - (hoge noodzaak) 1 - 5 (kan op het eind naar gekeken worden).
   - bespreek dit met groepsleden wanneer nodig.

# Deployment
Deployment worden geregeld door GitHub Actions
Er zijn op dit moment twee workflows.
Eentje voor de test omgeving
Eentje voor de productie omgeving.

Deze staan in de map .github/workflows/

# Inloggen Digitaal Patienten Dossier
Ga naar https://digitaalpatientendossier.gdcs.nl/index.php (LET OP: Je moet verbonden zijn met de Gilde 1.09 wifi, anders kom je niet op de site).

Als je wil inloggen als admin vul je de volgende gegevens in: 


E-mailadres = "admin@rocgilde.nl".

Wachtwoord = "admin".

Als je wil inloggen als medewerker vul je de volgende gegevens in:

E-mailadres 1 (Alvin Harrison) = "alvin.harrison@example.com".

E-mailadres 2 (Dawn James) = "dawn.james@example.com".

E-mailadres 3 (Jackie Williamson) = "jackie.williamson@example.com".

Wachtwoord (voor iedereen hetzelfde) = "Gilde123".

# Inloggen PHPmyAdmin
Ga naar https://phpmyadmin.gdcs.nl/index.php (LET OP: Je moet verbonden zijn met de Gilde 1.09 wifi, anders kom je niet op de site)

De username zou autmatisch al ingevuld moeten zijn als de pagina ingeladen is, als dit niet zo is vul je "dpd_user" in het username vakje in.

Voor het wachtwoord kopieer en plak je het volgende stukje tekst in het wachtwoord vakje: "q220@Wgz0]I9uq!J".

# Inloggen (niet meer relevant)
Deze hash kan je bij je medewerker als wachtwoord neerzetten.
Het wachtwoord is "admin".
$2y$10$7cuPDEMwyvZIZBBFoZujC.TqRAJewVoCTqigNy1MgdYp4x8XNS7Mm


