<?php 
	require 'config.php';
	
	dol_include_once('/quentin/class/film.class.php');
	
	$PDOdb=new TPDOdb;

	
	llxHeader();
	dol_fiche_head();


	
	$TLegume = file('fruitslegumes.txt');
	$TCouleur = file('couleurs.txt');
	$TAction = file('action.txt');
	$TAnimal = file('animal.txt');
	//var_dump($filelegume, $filecouleur);exit;
	
	$TFilm = array();
	
	for($i=0; $i<200; $i++) {
		
		shuffle($TLegume);
		shuffle($TCouleur);
		shuffle($TAction);
		shuffle($TAnimal);
		
		
		 
		$film = new TFilm();
		$film->title=trim($TLegume[0]).' '.trim($TCouleur[0]).' '.trim($TAction[0]).' '.strtolower(trim($TAnimal[0])); 
		$film->save($PDOdb);
	}

		
	dol_fiche_end();
	llxFooter();
?>