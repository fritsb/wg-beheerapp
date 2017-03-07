<?php
/* Bestandsnaam: config.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * In dit bestand staan de configvariabelen
 *
 */


$_arrConfig['db_host'] = "";
$_arrConfig['db_user'] = "";
$_arrConfig['db_pass'] = "";
$_arrConfig['db_table'] = "";

// De algemene instellingen 
$_arrConfig['img_dir'] = "images/";    // Directory waar de images in staan
$_arrConfig['css_dir'] = "includes/";  // Directory waar het CSS-bestand in staat
$_arrConfig['css_file'] = "stylesheet_v1.css"; // Bestandsnaam van het CSS-bestand
$_arrConfig['header_img'] = "header_img.png";  // Bestandsnaam van het headerplaatje
$_arrConfig['website_title'] = "Wireless Grootebroek"; // Naam van de website
$_arrConfig['website_title_afk'] = "WG"; // Afkorting van de naam van de website
$_arrConfig['work_dir'] = "wg-beheerapp/"; // De dir van de applicatie in de WWW-dir
$_arrConfig['www_dir'] = $_SERVER['DOCUMENT_ROOT']; // De WWW-dir
$_arrConfig['website_mail'] = "";
$_arrConfig['website_url'] = "";

// De instellingen mbt MRTG
$_arrConfig['mrtg_cfg_dir'] = "/etc/mrtg/"; // Locatie van mrtg-config-dir
$_arrConfig['mrtg_cfg_template'] = "/etc/mrtg/mrtg_template.cfg"; // Locatie van mrtg-template
$_arrConfig['mrtg_cfg'] = "/etc/mrtg/mrtg.cfg"; // Locatie van mrtg-config bestand
$_arrConfig['mrtg_html_dir'] = "/var/www/mrtg/"; // Locatie van mrtg-dir
$_arrConfig['mrtg_bin'] = "/usr/bin/mrtg"; // Locatie van mrtg-bin

// Overige instellingen
$_arrConfig['login_pogingen'] = 5; // Aantal toegestaande loginpogingen
$_arrConfig['max_inlog_uur'] = 2;  // Maximaal aantal uur dat men is ingelogd



?>
