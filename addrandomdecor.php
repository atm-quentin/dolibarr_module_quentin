<?php 
	require 'config.php';
	
	dol_include_once('/quentin/class/film.class.php');
	
	$PDOdb=new TPDOdb;

	
	llxHeader();
	dol_fiche_head();
	/*$sql="SELECT rowid 
		FROM ".MAIN_DB_PREFIX."film
	";

$monfichier=fopen('films.txt','r');


while($nom = fgets($monfichier)){
	$sql= "INSERT INTO  `".MAIN_DB_PREFIX."film` (
	`rowid` ,
	`date_cre` ,
	`date_maj` ,
	`title` ,
	`description` ,
	`fk_c_categoryfilm` ,
	`code_c_categoryfilm`
	)
	VALUES (
	'8',  '1000-01-01 00:00:00',  '1000-01-01 00:00:00',  $nom, NULL ,  '0', NULL
	);";
	
	$PDOdb->Execute($sql);
}
fclose($monfichier);*/

	
	$TLegume = file('fruitslegumes.txt');
	$TCouleur = file('couleurs.txt');
	$TAction = file('action.txt');
	$TAnimal = file('animal.txt');
	$TDecor = file('decor.txt');
	
	
	$TIDFilm =array_keys( Tfilm::getAll($PDOdb) );
	//var_dump($filelegume, $filecouleur);exit;
	
	for($i=0; $i<200; $i++) {
		
	
		shuffle($TCouleur);
	
		shuffle($TDecor);
		shuffle($TIDFilm);
		
		 
		$decor = new TDecor();
		$decor->label=trim($TDecor[0]).' '.trim($TCouleur[0]); 
		$decor->fk_film=$TIDFilm[0];
		$decor->surface=rand(0,100);
		$decor->save($PDOdb);
	}

		
	dol_fiche_end();
	llxFooter();
?>