# Credentials
```php
//    public static string $host = "10.250.0.103";
//    public static string $username = "dpd";
//    public static string $pass = "A]3__CQB0klPDyd4";
//    public static string $db = "dpd";
```

# Digitaal Patienten Dossier

Voor de database connectie te fixen:
- maak een nieuwe file genaamd `config.php` in de root folder
- kopieer de content van `default-config.php` naar `config.php`
- vul de credentials van je database in `config.php`
- Verwijder alle comments en whitespaces voor de <?php tag

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

# Deployment
Deployment worden geregeld door GitHub Actions
Er zijn op dit moment twee workflows.
Eentje voor de test omgeving
Eentje voor de productie omgeving.

Deze staan in de map .github/workflows/

# Inloggen Digitaal Patienten Dossier
Ga naar https://digitaalpatientendossier.gdcs.gildedevops.it/index.php (LET OP: Je moet verbonden zijn met de Gilde 1.09 wifi, anders kom je niet op de site)
Als je wil inloggen als admin vul je de volgende gegevens in: 
E-mailadres = "admin@admin.com"
Wachtwoord = "admin"
Als je wil inloggen als medewerker vul je de volgende gegevens in:
E-mailadres 1 (Alvin Harrison) = alvin.harrison@example.com
E-mailadres 2 (Dawn James) = dawn.james@example.com
E-mailadres 3 (Jackie Williamson) = jackie.williamson@example.com
Wachtwoord (voor iedereen hetzelfde) = Gilde123

# Inloggen PHPmyAdmin
Ga naar https://phpmyadmin.gdcs.gildedevops.it/index.php (LET OP: Je moet verbonden zijn met de Gilde 1.09 wifi, anders kom je niet op de site)
De username zou autmatisch al ingevuld moeten zijn als de pagina ingeladen is, als dit niet zo is vul je "dpd" in het username vakje in.
Voor het wachtwoord kopieer en plak je het volgende stukje tekst in het wachtwoord vakje: "A]3__CQB0klPDyd4".

# Inloggen (niet meer relevant)
Deze hash kan je bij je medewerker als wachtwoord neerzetten.
Het wachtwoord is "admin".
$2y$10$7cuPDEMwyvZIZBBFoZujC.TqRAJewVoCTqigNy1MgdYp4x8XNS7Mm
