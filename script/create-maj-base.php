<?php
/*
 * Script créant et vérifiant que les champs requis s'ajoutent bien
 */

if(!defined('INC_FROM_DOLIBARR')) {
	define('INC_FROM_CRON_SCRIPT', true);

	require('../config.php');

}



dol_include_once('/quentin/class/film.class.php');

$PDOdb=new TPDOdb;

$o=new TCCategoryFilm($db);
$o->init_db_by_vars($PDOdb);

$o=new TFilm($db);
$o->init_db_by_vars($PDOdb);

$o=new TDecor($db);
$o->init_db_by_vars($PDOdb);