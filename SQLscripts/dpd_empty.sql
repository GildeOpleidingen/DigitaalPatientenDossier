-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 11 dec 2023 om 11:08
-- Serverversie: 10.4.22-MariaDB
-- PHP-versie: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dpd`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `naam` varchar(40) NOT NULL,
  `geslacht` char(1) NOT NULL,
  `adres` varchar(35) NOT NULL,
  `postcode` varchar(7) NOT NULL,
  `woonplaats` varchar(35) NOT NULL,
  `telefoonnummer` varchar(12) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `geboortedatum` date NOT NULL,
  `reanimatiestatus` tinyint(1) NOT NULL,
  `nationaliteit` varchar(25) NOT NULL,
  `afdeling` varchar(10) DEFAULT NULL,
  `burgelijkestaat` varchar(15) DEFAULT NULL,
  `foto` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clientverhaal`
--

CREATE TABLE `clientverhaal` (
  `id` int(11) NOT NULL,
  `medischoverzichtid` int(11) NOT NULL,
  `foto` blob DEFAULT NULL,
  `introductie` text DEFAULT NULL,
  `gezinfamilie` text DEFAULT NULL,
  `belangrijkeinfo` text DEFAULT NULL,
  `hobbies` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `medewerker`
--

CREATE TABLE `medewerker` (
  `id` int(11) NOT NULL,
  `naam` varchar(40) NOT NULL,
  `klas` varchar(15) NOT NULL,
  `foto` blob DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefoonnummer` varchar(12) DEFAULT NULL,
  `wachtwoord` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



--
-- Tabelstructuur voor tabel `medischoverzicht`
--

CREATE TABLE `medischoverzicht` (
  `id` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `opnamedatum` datetime NOT NULL,
  `opnameindicatie` text NOT NULL,
  `medischevoorgeschiedenis` text DEFAULT NULL,
  `alergieen` text DEFAULT NULL,
  `medicatie` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `meting`
--

CREATE TABLE `meting` (
  `id` int(11) NOT NULL,
  `verzorgerregelid` int(11) NOT NULL,
  `datumtijd` datetime NOT NULL,
  `hartslag` int(11) DEFAULT NULL,
  `ademhaling` int(11) DEFAULT NULL,
  `bloeddruklaag` int(11) DEFAULT NULL,
  `temperatuur` float DEFAULT NULL,
  `vochtinname` int(11) DEFAULT NULL,
  `pijn` int(11) DEFAULT NULL,
  `bloeddrukhoog` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `metingontlasting`
--

CREATE TABLE `metingontlasting` (
  `id` int(11) NOT NULL,
  `metingid` int(11) NOT NULL,
  `samenstellingid` int(11) NOT NULL,
  `datumtijd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `metingurine`
--

CREATE TABLE `metingurine` (
  `id` int(11) NOT NULL,
  `metingid` int(11) NOT NULL,
  `datumtijd` datetime NOT NULL,
  `hoeveelheid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon01gezondheidsbeleving`
--

CREATE TABLE `patroon01gezondheidsbeleving` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `algemene_gezondheid` text NOT NULL,
  `gezondheids_bezigheid` text DEFAULT NULL,
  `rookt` tinyint(1) NOT NULL,
  `rookt_hoeveelheid` tinytext DEFAULT NULL,
  `drinkt` tinyint(1) NOT NULL,
  `drinkt_hoeveelheid` tinytext DEFAULT NULL,
  `besmettelijke_aandoening` tinyint(1) NOT NULL,
  `besmettelijke_aandoening_welke` tinytext DEFAULT NULL,
  `alergieen` tinyint(1) NOT NULL,
  `alergieen_welke` tinytext DEFAULT NULL,
  `oorzaak_huidige_toestand` tinytext DEFAULT NULL,
  `oht_actie` tinytext DEFAULT NULL,
  `oht_hoe_effectief` tinytext DEFAULT NULL,
  `oht_wat_nodig` tinytext DEFAULT NULL,
  `oht_wat_belangrijk` tinytext DEFAULT NULL,
  `oht_reactie_op_advies` tinytext DEFAULT NULL,
  `preventie` tinytext DEFAULT NULL,
  `observatie` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon02voedingstofwisseling`
--

CREATE TABLE `patroon02voedingstofwisseling` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `eetlust` int(11) NOT NULL,
  `dieet` tinyint(1) NOT NULL,
  `dieet_welk` tinytext DEFAULT NULL,
  `gewicht_verandert` tinyint(1) NOT NULL,
  `moeilijk_slikken` tinyint(1) NOT NULL,
  `gebitsproblemen` tinyint(1) NOT NULL,
  `gebitsprothese` tinyint(1) NOT NULL,
  `huidproblemen` tinyint(1) NOT NULL,
  `gevoel` int(11) NOT NULL,
  `observatie` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon03uitscheiding`
--

CREATE TABLE `patroon03uitscheiding` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `ontlasting_probleem` tinyint(1) NOT NULL,
  `op_welke` tinytext DEFAULT NULL,
  `op_preventie` tinytext DEFAULT NULL,
  `op_medicijnen` tinyint(1) NOT NULL,
  `op_medicijnen_welke` tinytext DEFAULT NULL,
  `urineer_probleem` tinyint(1) NOT NULL,
  `up_incontinentie` tinyint(1) NOT NULL,
  `up_incontinentie_behandeling` tinyint(1) NOT NULL,
  `up_incontinentie_behandeling_welke` tinytext DEFAULT NULL,
  `transpiratie` tinyint(1) NOT NULL,
  `transpiratie_welke` tinytext DEFAULT NULL,
  `observatie` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon04activiteiten`
--

CREATE TABLE `patroon04activiteiten` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `voeding` int(11) DEFAULT NULL,
  `aankleden` int(11) DEFAULT NULL,
  `alg_mobiliteit` int(11) DEFAULT NULL,
  `koken` int(11) DEFAULT NULL,
  `huishouden` int(11) DEFAULT NULL,
  `financien` int(11) DEFAULT NULL,
  `verzorging` int(11) DEFAULT NULL,
  `baden` int(11) DEFAULT NULL,
  `toiletgang` int(11) DEFAULT NULL,
  `uit_bed_komen` int(11) DEFAULT NULL,
  `winkelen` int(11) DEFAULT NULL,
  `tijd_voor_uzelf_nodig` tinyint(1) NOT NULL,
  `tijd_voor_uzelf_nodig_blijktuit` tinytext DEFAULT NULL,
  `dagelijkse_activiteiten` tinytext DEFAULT NULL,
  `dagelijkse_gewoontes` tinyint(1) NOT NULL,
  `dagelijkse_gewoontes_welke` tinytext DEFAULT NULL,
  `lichamelijke_beperking` tinyint(1) NOT NULL,
  `lichamelijke_beperking_welke` tinytext DEFAULT NULL,
  `vermoeitheids_klachten` tinyint(1) NOT NULL,
  `passiever` tinyint(1) NOT NULL,
  `passiever_blijktuit` tinytext DEFAULT NULL,
  `problemen_starten_dag` tinyint(1) NOT NULL,
  `problemen_starten_dag_blijktuit` tinytext DEFAULT NULL,
  `hobbys` tinyint(1) NOT NULL,
  `hobbys_bestedingstijd` tinytext DEFAULT NULL,
  `activiteiten_weggevallen` tinyint(1) NOT NULL,
  `activiteiten_weggevallen_welke` tinytext DEFAULT NULL,
  `observatie` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon05slaaprust`
--

CREATE TABLE `patroon05slaaprust` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `verandering_inslaaptijd` tinyint(1) NOT NULL,
  `verandering_inslaaptijd_blijktuit` tinytext DEFAULT NULL,
  `verandering_kwaliteit_slapen` tinyint(1) NOT NULL,
  `verandering_kwaliteit_slapen_blijktuit` tinytext DEFAULT NULL,
  `gebruik_inslaapmiddel` tinyint(1) NOT NULL,
  `gebruik_inslaapmiddel_welke` varchar(6) DEFAULT NULL,
  `gebruik_inslaapmiddel_anders` tinytext DEFAULT NULL,
  `slaapduur` float NOT NULL,
  `uitgerust_wakker` tinyint(1) NOT NULL,
  `dromen_nachtmerries` tinyint(1) NOT NULL,
  `rustperiodes_overdag` tinyint(1) NOT NULL,
  `gemakkelijk_ontspannen` tinyint(1) NOT NULL,
  `observatie` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon06cognitiewaarneming`
--

CREATE TABLE `patroon06cognitiewaarneming` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `moeilijk_horen` tinyint(1) NOT NULL,
  `hoort_stemmen` tinyint(1) NOT NULL,
  `hoort_stemmen_wat` tinytext DEFAULT NULL,
  `moete_met_zien` tinyint(1) NOT NULL,
  `moete_met_zien_wat` tinytext DEFAULT NULL,
  `ruikt_iets_onverklaarbaar` tinyint(1) NOT NULL,
  `ruikt_iets_onverklaarbaar_wat` tinytext DEFAULT NULL,
  `verandering_denken` tinyint(1) NOT NULL,
  `moeite_spreken` tinyint(1) NOT NULL,
  `taal_thuis` varchar(50) DEFAULT NULL,
  `verandering_concentratievermogen` tinyint(1) NOT NULL,
  `moeilijker_beslissen` tinyint(1) NOT NULL,
  `verandering_geheugen` tinyint(1) NOT NULL,
  `verandering_orientatie` tinyint(1) NOT NULL,
  `invloed_medicatie` tinyint(1) NOT NULL,
  `invloed_medicatie_welke` tinytext DEFAULT NULL,
  `gebruikt_middelen` tinyint(1) NOT NULL,
  `gebruikt_middelen_softdrugs` varchar(50) DEFAULT NULL,
  `gebruikt_middelen_harddrugs` varchar(50) DEFAULT NULL,
  `gebruikt_middelen_alcohol` varchar(50) DEFAULT NULL,
  `gebruikt_middelen_anders` varchar(50) DEFAULT NULL,
  `pijnklachten` tinyint(1) NOT NULL,
  `pijnklachten_waar_wanneer_soort` tinytext DEFAULT NULL,
  `pijnklachten_tegengaan_pijn` tinytext DEFAULT NULL,
  `pijnklachten_preventie` tinytext DEFAULT NULL,
  `observatie` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon07zelfbeleving`
--

CREATE TABLE `patroon07zelfbeleving` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `zelfbeschrijving` tinytext DEFAULT NULL,
  `opkomen_voor_uzelf` tinyint(1) NOT NULL,
  `wel_niet_opkomen_blijktuit` tinytext DEFAULT NULL,
  `verandering_stemming` tinyint(1) NOT NULL,
  `verandering_stemming_welke` tinytext DEFAULT NULL,
  `gevoel_op_dit_moment` varchar(11) NOT NULL,
  `gevoel_op_dit_moment_anders` tinytext DEFAULT NULL,
  `verandering_concentratie` tinyint(1) NOT NULL,
  `verandering_denkpatroon` tinyint(1) NOT NULL,
  `verandering_uiterlijk` tinyint(1) NOT NULL,
  `sensaties` tinyint(1) NOT NULL,
  `sensaties_welk_gevoel` tinytext DEFAULT NULL,
  `gevoel_momenteel` varchar(3) NOT NULL,
  `lichamelijke_energie` varchar(3) NOT NULL,
  `zelfverzorging` tinytext DEFAULT NULL,
  `observatie` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon08rollenrelatie`
--

CREATE TABLE `patroon08rollenrelatie` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `getrouwd_samenwonend` tinyint(1) NOT NULL,
  `kinderen` tinyint(1) NOT NULL,
  `tevreden_thuissituatie` tinyint(1) NOT NULL,
  `steun_vrienden_familie` tinyint(1) NOT NULL,
  `inkomstenbron` tinytext DEFAULT NULL,
  `verandering_fin_sit_vroeger` tinyint(1) NOT NULL,
  `verandering_fin_sit_vroeger_welke` tinytext DEFAULT NULL,
  `verandering_fin_sit_toekomst` tinyint(1) NOT NULL,
  `verandering_fin_sit_toekomst_welke` tinytext DEFAULT NULL,
  `opleiding` varchar(50) NOT NULL,
  `verandering_sociale_contacten` tinyint(1) NOT NULL,
  `verandering_sociale_contacten_welke` tinytext DEFAULT NULL,
  `groot_gezin` tinyint(1) NOT NULL,
  `plaats_in_gezin` varchar(15) NOT NULL,
  `onderlinge_contacten_gezin` varchar(50) DEFAULT NULL,
  `agressie_gezin` tinyint(1) NOT NULL,
  `verenigingslid` tinyint(1) NOT NULL,
  `vereniging_welke` tinytext DEFAULT NULL,
  `contact_met_derden` varchar(50) DEFAULT NULL,
  `verlies_geleden` tinyint(1) NOT NULL,
  `verlies_geleden_welke` tinytext DEFAULT NULL,
  `observatie` varchar(17) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon09seksualiteitvoorplanting`
--

CREATE TABLE `patroon09seksualiteitvoorplanting` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `verandering_seksuele_beleving` tinyint(1) NOT NULL,
  `verandering_seksuele_beleving_door` tinytext DEFAULT NULL,
  `verandering_seksueel_gedrag` tinyint(1) NOT NULL,
  `wisselende_contacten` tinyint(1) NOT NULL,
  `veilig_vrijen` tinyint(1) NOT NULL,
  `anticonceptiemiddel` tinyint(1) NOT NULL,
  `anticonceptiemiddel_welke` tinytext DEFAULT NULL,
  `anticonceptiemiddel_problemen` tinyint(1) NOT NULL,
  `seksuele_gerichtheid` varchar(3) NOT NULL,
  `seksuele_gerichtheid_problemen` tinyint(1) NOT NULL,
  `soa` tinyint(1) NOT NULL,
  `soa_welke` tinytext DEFAULT NULL,
  `observatie` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon10stressverwerking`
--

CREATE TABLE `patroon10stressverwerking` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `reactie_spanningen` varchar(13) NOT NULL,
  `spanningsvolle_situaties_voorkomen` tinyint(1) NOT NULL,
  `spanningsvolle_situaties_voorkomen_hoe` tinytext DEFAULT NULL,
  `spanningsvolle_situaties_oplossen` tinyint(1) NOT NULL,
  `spanningsvolle_situaties_oplossen_hoe` tinytext DEFAULT NULL,
  `omstandigheden_in_war_raken` tinyint(1) NOT NULL,
  `omstandigheden_in_war_raken_welke` tinytext DEFAULT NULL,
  `angsig_paniek` tinyint(1) NOT NULL,
  `angsig_paniek_actie` tinytext DEFAULT NULL,
  `angsig_paniek_lukt_voorkomen` tinyint(1) NOT NULL,
  `suicidaal` tinyint(1) NOT NULL,
  `suicidaal_momenteel` tinyint(1) NOT NULL,
  `agressiief` tinyint(1) NOT NULL,
  `anderen_iets_aan_willen_doen` tinyint(1) NOT NULL,
  `maatregelen_veiligheid` tinyint(1) NOT NULL,
  `maatregelen_veiligheid_door` tinytext DEFAULT NULL,
  `moeite_uiten_gevoelens` tinyint(1) NOT NULL,
  `bespreken_gevoelens_met` tinytext DEFAULT NULL,
  `observatie` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroon11waardelevensovertuiging`
--

CREATE TABLE `patroon11waardelevensovertuiging` (
  `id` int(11) NOT NULL,
  `vragenlijstid` int(11) NOT NULL,
  `gelovig` tinyint(1) NOT NULL,
  `geloof_welk` varchar(6) NOT NULL,
  `geloof_anders` varchar(25) DEFAULT NULL,
  `behoefte_religieuze_activiteit` tinyint(1) NOT NULL,
  `gebruiken_tav_geloofsovertuiging` tinyint(1) NOT NULL,
  `gebruiken_tav_geloofsovertuiging_welke` tinytext DEFAULT NULL,
  `gebruiken_tav_geloofsovertuiging_wanneer` tinytext DEFAULT NULL,
  `overeenkomst_waarden_normen` tinyint(1) NOT NULL,
  `etnische_achtergrond` tinytext DEFAULT NULL,
  `gebruiken_mbt_etnische_achtergrond` tinyint(1) NOT NULL,
  `gebruiken_mbt_etnische_achtergrond_welke` tinytext DEFAULT NULL,
  `gebruiken_mbt_etnische_achtergrond_wanneer` tinytext DEFAULT NULL,
  `observatie` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `patroontype`
--

CREATE TABLE `patroontype` (
  `id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `patroontype`
--

INSERT INTO `patroontype` (`id`, `type`) VALUES
(1, 'Gezondheidsbeleving en -instanthouding'),
(2, 'Voedings- en Stofwisselingspatroon'),
(3, 'UitscheidingsPatroon'),
(4, 'Activiteitenpatroon'),
(5, 'Slaap- en rustpatroon'),
(6, 'Congintie- en waarnemingspatroon'),
(7, 'Zelfbgelevingspatroon'),
(8, 'Rollen- en relatiepatroon'),
(9, 'Seksualiteits- en voortplantingspatroon'),
(10, 'Stressverwerkingspatroon (probleemhantering)'),
(11, 'Waarde- en levensovertuiginspatroon');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `rapport`
--

CREATE TABLE `rapport` (
  `id` int(11) NOT NULL,
  `verzorgerregelid` int(11) NOT NULL,
  `datumtijd` datetime NOT NULL,
  `inhoud` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `relatie`
--

CREATE TABLE `relatie` (
  `id` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `naam` varchar(40) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefoonnummer` varchar(12) DEFAULT NULL,
  `relatietype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `samenstelling`
--

CREATE TABLE `samenstelling` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `uiterlijk` varchar(30) NOT NULL,
  `beschrijving` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `samenstelling`
--

INSERT INTO `samenstelling` (`id`, `type`, `uiterlijk`, `beschrijving`) VALUES
(1, 1, 'Harde keutels', 'Aanwijzing verstopping'),
(2, 2, 'Klonterig, worstvormig', 'Aanwijzing verstopping'),
(3, 3, 'Worstvormig met barstjes', 'Ideale ontlasting'),
(4, 4, 'Gladde sigaar', 'Ideale ontlasting'),
(5, 5, 'Zachte stukjes, gladde rand', 'Neiging tot diarree'),
(6, 6, 'Zachte stukjes, ruwe rand', 'Neiging tot diarree'),
(7, 7, 'Waterig, zonder vaste stof', 'Neiging tot diarree');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verzorgerregel`
--

CREATE TABLE `verzorgerregel` (
  `id` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `medewerkerid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `vragenlijst`
--

CREATE TABLE `vragenlijst` (
  `id` int(11) NOT NULL,
  `verzorgerregelid` int(11) NOT NULL,
  `afnamedatumtijd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `zorgplan`
--

CREATE TABLE `zorgplan` (
  `id` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `opsteldatumtijd` datetime NOT NULL,
  `patroontypeid` int(11) NOT NULL,
  `P` text DEFAULT NULL,
  `E` text DEFAULT NULL,
  `S` text DEFAULT NULL,
  `doelen` text DEFAULT NULL,
  `interventies` text DEFAULT NULL,
  `evaluatiedoelen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clientverhaal`
--
ALTER TABLE `clientverhaal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_medischoverzicht_id` (`medischoverzichtid`);

--
-- Indexen voor tabel `medewerker`
--
ALTER TABLE `medewerker`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `medischoverzicht`
--
ALTER TABLE `medischoverzicht`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_client_id` (`clientid`);

--
-- Indexen voor tabel `meting`
--
ALTER TABLE `meting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_verzorgerregel_id` (`verzorgerregelid`);

--
-- Indexen voor tabel `metingontlasting`
--
ALTER TABLE `metingontlasting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_meting_id` (`metingid`),
  ADD KEY `FK_samenstelling_id` (`samenstellingid`) USING BTREE;

--
-- Indexen voor tabel `metingurine`
--
ALTER TABLE `metingurine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_meeting_id` (`metingid`);

--
-- Indexen voor tabel `patroon01gezondheidsbeleving`
--
ALTER TABLE `patroon01gezondheidsbeleving`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon02voedingstofwisseling`
--
ALTER TABLE `patroon02voedingstofwisseling`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon03uitscheiding`
--
ALTER TABLE `patroon03uitscheiding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon04activiteiten`
--
ALTER TABLE `patroon04activiteiten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon05slaaprust`
--
ALTER TABLE `patroon05slaaprust`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon06cognitiewaarneming`
--
ALTER TABLE `patroon06cognitiewaarneming`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon07zelfbeleving`
--
ALTER TABLE `patroon07zelfbeleving`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon08rollenrelatie`
--
ALTER TABLE `patroon08rollenrelatie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon09seksualiteitvoorplanting`
--
ALTER TABLE `patroon09seksualiteitvoorplanting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon10stressverwerking`
--
ALTER TABLE `patroon10stressverwerking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroon11waardelevensovertuiging`
--
ALTER TABLE `patroon11waardelevensovertuiging`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vragenlijst_id` (`vragenlijstid`);

--
-- Indexen voor tabel `patroontype`
--
ALTER TABLE `patroontype`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `rapport`
--
ALTER TABLE `rapport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_verzorgerregel_id` (`verzorgerregelid`);

--
-- Indexen voor tabel `relatie`
--
ALTER TABLE `relatie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_client_id` (`clientid`);

--
-- Indexen voor tabel `samenstelling`
--
ALTER TABLE `samenstelling`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `verzorgerregel`
--
ALTER TABLE `verzorgerregel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientid` (`clientid`,`medewerkerid`),
  ADD KEY `FK_client_id` (`clientid`),
  ADD KEY `FK_medewerker_id` (`medewerkerid`) USING BTREE;

--
-- Indexen voor tabel `vragenlijst`
--
ALTER TABLE `vragenlijst`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_verzorgerregel_id` (`verzorgerregelid`);

--
-- Indexen voor tabel `zorgplan`
--
ALTER TABLE `zorgplan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_patroontype_id` (`patroontypeid`),
  ADD KEY `FK_client_id` (`clientid`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `clientverhaal`
--
ALTER TABLE `clientverhaal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `medewerker`
--
ALTER TABLE `medewerker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `medischoverzicht`
--
ALTER TABLE `medischoverzicht`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `meting`
--
ALTER TABLE `meting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `metingontlasting`
--
ALTER TABLE `metingontlasting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `metingurine`
--
ALTER TABLE `metingurine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon01gezondheidsbeleving`
--
ALTER TABLE `patroon01gezondheidsbeleving`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon02voedingstofwisseling`
--
ALTER TABLE `patroon02voedingstofwisseling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon03uitscheiding`
--
ALTER TABLE `patroon03uitscheiding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon04activiteiten`
--
ALTER TABLE `patroon04activiteiten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon05slaaprust`
--
ALTER TABLE `patroon05slaaprust`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon06cognitiewaarneming`
--
ALTER TABLE `patroon06cognitiewaarneming`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon07zelfbeleving`
--
ALTER TABLE `patroon07zelfbeleving`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon08rollenrelatie`
--
ALTER TABLE `patroon08rollenrelatie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon09seksualiteitvoorplanting`
--
ALTER TABLE `patroon09seksualiteitvoorplanting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon10stressverwerking`
--
ALTER TABLE `patroon10stressverwerking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroon11waardelevensovertuiging`
--
ALTER TABLE `patroon11waardelevensovertuiging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `patroontype`
--
ALTER TABLE `patroontype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `rapport`
--
ALTER TABLE `rapport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `relatie`
--
ALTER TABLE `relatie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `samenstelling`
--
ALTER TABLE `samenstelling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `verzorgerregel`
--
ALTER TABLE `verzorgerregel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `vragenlijst`
--
ALTER TABLE `vragenlijst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `zorgplan`
--
ALTER TABLE `zorgplan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `clientverhaal`
--
ALTER TABLE `clientverhaal`
  ADD CONSTRAINT `clientverhaal_ibfk_1` FOREIGN KEY (`medischoverzichtid`) REFERENCES `medischoverzicht` (`id`);

--
-- Beperkingen voor tabel `medischoverzicht`
--
ALTER TABLE `medischoverzicht`
  ADD CONSTRAINT `medischoverzicht_ibfk_1` FOREIGN KEY (`clientid`) REFERENCES `client` (`id`);

--
-- Beperkingen voor tabel `meting`
--
ALTER TABLE `meting`
  ADD CONSTRAINT `meting_ibfk_1` FOREIGN KEY (`verzorgerregelid`) REFERENCES `verzorgerregel` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `metingontlasting`
--
ALTER TABLE `metingontlasting`
  ADD CONSTRAINT `metingontlasting_ibfk_1` FOREIGN KEY (`metingid`) REFERENCES `meting` (`id`),
  ADD CONSTRAINT `metingontlasting_ibfk_2` FOREIGN KEY (`samenstellingid`) REFERENCES `samenstelling` (`id`);

--
-- Beperkingen voor tabel `metingurine`
--
ALTER TABLE `metingurine`
  ADD CONSTRAINT `metingurine_ibfk_1` FOREIGN KEY (`metingid`) REFERENCES `meting` (`id`);

--
-- Beperkingen voor tabel `patroon01gezondheidsbeleving`
--
ALTER TABLE `patroon01gezondheidsbeleving`
  ADD CONSTRAINT `patroon01gezondheidsbeleving_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon02voedingstofwisseling`
--
ALTER TABLE `patroon02voedingstofwisseling`
  ADD CONSTRAINT `patroon02voedingstofwisseling_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon03uitscheiding`
--
ALTER TABLE `patroon03uitscheiding`
  ADD CONSTRAINT `patroon03uitscheiding_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon04activiteiten`
--
ALTER TABLE `patroon04activiteiten`
  ADD CONSTRAINT `patroon04activiteiten_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon05slaaprust`
--
ALTER TABLE `patroon05slaaprust`
  ADD CONSTRAINT `patroon05slaaprust_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon06cognitiewaarneming`
--
ALTER TABLE `patroon06cognitiewaarneming`
  ADD CONSTRAINT `patroon06cognitiewaarneming_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon07zelfbeleving`
--
ALTER TABLE `patroon07zelfbeleving`
  ADD CONSTRAINT `patroon07zelfbeleving_ibfk_1` FOREIGN KEY (`id`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon08rollenrelatie`
--
ALTER TABLE `patroon08rollenrelatie`
  ADD CONSTRAINT `patroon08rollenrelatie_ibfk_1` FOREIGN KEY (`id`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon09seksualiteitvoorplanting`
--
ALTER TABLE `patroon09seksualiteitvoorplanting`
  ADD CONSTRAINT `patroon09seksualiteitvoorplanting_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon10stressverwerking`
--
ALTER TABLE `patroon10stressverwerking`
  ADD CONSTRAINT `patroon10stressverwerking_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `patroon11waardelevensovertuiging`
--
ALTER TABLE `patroon11waardelevensovertuiging`
  ADD CONSTRAINT `patroon11waardelevensovertuiging_ibfk_1` FOREIGN KEY (`vragenlijstid`) REFERENCES `vragenlijst` (`id`);

--
-- Beperkingen voor tabel `rapport`
--
ALTER TABLE `rapport`
  ADD CONSTRAINT `rapport_ibfk_1` FOREIGN KEY (`verzorgerregelid`) REFERENCES `verzorgerregel` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `relatie`
--
ALTER TABLE `relatie`
  ADD CONSTRAINT `relatie_ibfk_1` FOREIGN KEY (`clientid`) REFERENCES `client` (`id`);

--
-- Beperkingen voor tabel `verzorgerregel`
--
ALTER TABLE `verzorgerregel`
  ADD CONSTRAINT `verzorgerregel_ibfk_1` FOREIGN KEY (`clientid`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `verzorgerregel_ibfk_5` FOREIGN KEY (`medewerkerid`) REFERENCES `medewerker` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `vragenlijst`
--
ALTER TABLE `vragenlijst`
  ADD CONSTRAINT `vragenlijst_ibfk_1` FOREIGN KEY (`verzorgerregelid`) REFERENCES `verzorgerregel` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `zorgplan`
--
ALTER TABLE `zorgplan`
  ADD CONSTRAINT `zorgplan_ibfk_1` FOREIGN KEY (`clientid`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `zorgplan_ibfk_2` FOREIGN KEY (`patroontypeid`) REFERENCES `patroontype` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
