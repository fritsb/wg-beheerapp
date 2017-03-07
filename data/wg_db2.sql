## ---------------------------------------------------------------------------------
## Bestandsnaam: wg_db.sql
## Datum: 06 - 11 - 2005
## 
## Beschrijving: 
## Het SQL-script om de database op te zetten
## 
## 
## -------------------------------------------------------------------------------//

## ---------------------------------------------------------------------------------
## Database `beheerapplicatie`
## -------------------------------------------------------------------------------//

DROP DATABASE IF EXISTS `beheerapplicatie`;
CREATE DATABASE IF NOT EXISTS `beheerapplicatie`;
USE beheerapplicatie;


## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `ban`
## -------------------------------------------------------------------------------//

CREATE TABLE `ban` (
  `id` int(255) NOT NULL auto_increment,
  `toevoegdatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `begindatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `einddatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `reden` text NOT NULL,
  `status` int(100) NOT NULL default '0',
  `ipadres` varchar(255) NOT NULL default '',
  `gebruikersid` int(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `bedrijf`
## -------------------------------------------------------------------------------//

CREATE TABLE `bedrijf` (
  `id` int(255) NOT NULL auto_increment,
  `bedrijfsnaam` varchar(100) NOT NULL default '',
  `kvk` varchar(255) NOT NULL,
  `straat` varchar(100) NOT NULL default '',
  `huisnr` varchar(100) NOT NULL default '',
  `postcode` varchar(6) NOT NULL default '',
  `woonplaats` varchar(100) NOT NULL default '',
  `telefoon` varchar(15) NOT NULL default '',
  `fax` varchar(15) NOT NULL default '',
  `emailadres` varchar(100) NOT NULL default '',
  `website` int(255) NOT NULL default '0',
  `toevoegdatum` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `bedrijfsnode`
## -------------------------------------------------------------------------------//

CREATE TABLE `bedrijfsnode` (
  `id` int(255) NOT NULL auto_increment,
  `bedrijfsid` int(255) NOT NULL default '0',
  `nodeid` int(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `gebruiker`
## -------------------------------------------------------------------------------//

CREATE TABLE `gebruiker` (
  `id` int(255) NOT NULL auto_increment,
  `gebruikersnaam` varchar(15) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `userlevel` int(255) NOT NULL,
  `status` int(255) NOT NULL,
  `ipadres` varchar(255) NOT NULL,
  `aanmelddatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `notificaties` varchar(20) NOT NULL,
  `persoonid` int(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `module`
## -------------------------------------------------------------------------------//

CREATE TABLE `module` (
  `id` int(255) NOT NULL auto_increment,
  `modulenaam` varchar(100) NOT NULL default '',
  `functiesbestand` varchar(255) NOT NULL,
  `actiebestand` varchar(255) NOT NULL,
  `menunaam` varchar(100) NOT NULL,
  `userlevel` int(255) NOT NULL,
  `toevoegdatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `actief` varchar(5) NOT NULL default 'nee',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `node`
## -------------------------------------------------------------------------------//

CREATE TABLE `node` (
  `id` int(255) NOT NULL auto_increment,
  `nwid` varchar(255) NOT NULL default '',
  `naam` varchar(255) NOT NULL default '',
  `ipadres` varchar(255) NOT NULL default '',
  `macadres` varchar(255) NOT NULL default '',
  `toevoegdatum` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `persoon`
## -------------------------------------------------------------------------------//

CREATE TABLE `persoon` (
  `id` int(255) NOT NULL auto_increment,
  `voornaam` varchar(100) NOT NULL default '',
  `tussenvoegsel` varchar(20) NOT NULL default '',
  `achternaam` varchar(100) NOT NULL default '',
  `straat` varchar(100) NOT NULL default '',
  `huisnr` varchar(100) NOT NULL default '',
  `postcode` varchar(6) NOT NULL default '',
  `woonplaats` varchar(100) NOT NULL default '',
  `telthuis` varchar(15) NOT NULL default '',
  `telwerk` varchar(15) NOT NULL default '',
  `telmobiel` varchar(15) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `toevoegdatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `bedrijfsid` int(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `persoonnode`
## -------------------------------------------------------------------------------//

CREATE TABLE `persoonnode` (
  `id` int(255) NOT NULL auto_increment,
  `persoonsid` int(255) NOT NULL default '0',
  `nodeid` int(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

## ---------------------------------------------------------------------------------
## Tabel structuur voor tabel `status`
## -------------------------------------------------------------------------------//

CREATE TABLE `status` (
  `id` int(255) NOT NULL auto_increment,
  `soort` varchar(100) NOT NULL default '',
  `begindatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `einddatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `extrainfo` text NOT NULL,
  `status` varchar(100) NOT NULL default '',
  `ipadres` varchar(255) NOT NULL default '',
  `uniekestring` varchar(255) NOT NULL default '',
  `toevoegdatum` datetime NOT NULL default '0000-00-00 00:00:00',
  `persoonid` int(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;



## ---------------------------------------------------------------------------------
## 
## -------------------------------------------------------------------------------//

