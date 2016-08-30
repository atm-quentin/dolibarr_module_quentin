<?php

	require 'config.php';
	
	dol_include_once('/quentin/class/film.class.php');
	
	$action = GETPOST('action');
	
	$PDOdb=new TPDOdb;

	$film = new TFilm;
	
	llxHeader();
	dol_fiche_head();
	
	switch ($action) {
		case 'save':
			$film->load($PDOdb, GETPOST('id'));
		
			$film->set_values($_POST);
			$film->save($PDOdb);
	
			_card($film,'view');		
			break;
		
		case 'view':
			$film->load($PDOdb, GETPOST('id'));
	
			_card($film,'view');		
			break;
		
		case 'edit':
			$film->load($PDOdb, GETPOST('id'));
	
			_card($film,'edit');		
			break;
		
		case 'add':
			_card($film,'edit');
			break;
		default:
			_list();
			
			break;
	}
	
	dol_fiche_end();
	llxFooter();
		var_dump($_POST);
		
	
	
	
function _list() {
	global $conf,$langs,$user;
	
	$PDOdb=new TPDOdb;
	
	$sql="SELECT rowid, title, description, code_c_categoryfilm
		FROM ".MAIN_DB_PREFIX."film
	";
	
	$formCore = new TFormCore('auto','formFilm','post');
	$l = new TListviewTBS('listFilm');
	echo $l->render($PDOdb, $sql,array(
		'title'=>array(
			'rowid'=>$langs->trans('Id')
			,'title'=>$langs->trans('Title')
			,'description'=>$langs->trans('Description')
			,'code_c_categoryfilm'=>$langs->trans('Category')
		)
		,'translate'=>array(
			'code_c_categoryfilm'=>TCCategoryFilm::getAll($PDOdb)
		)
		,'link'=>array(
			'title'=>'<a href="?action=view&id=@rowid@">@title@</a>'
		)
	
	));
	
	$formCore->end();
}


	
function _card(&$film, $mode) {

	global $conf, $langs, $user;
	
	
	
	
	$formCore = new TFormCore('auto','formFilm','post');

	$formCore->Set_typeaff($mode);

	echo $formCore->hidden('action', 'save');
	echo $formCore->hidden('id', $film->getId());
	echo $formCore->texte('','title', $film->title, 80,255);
	echo '<hr />';
	
	$PDOdb=new TPDOdb;
	
	echo $formCore->combo('','code_c_categoryfilm', TCCategoryFilm::getAll($PDOdb), $film->code_c_categoryfilm);
	echo '<hr />';
	

	echo $formCore->zonetexte('', 'description', $film->description, 80,5);
	echo '<hr />';
	
	if($mode == 'edit') {
		
		echo $formCore->btsubmit($langs->trans('Save'), 'bt_save');
			
	}
	else{
		echo $formCore->bt($langs->trans('Edit'), 'bt_edit', ' onclick="document.location.href = \'?action=edit&id='.$film->getId().'\'" ');
		
		$sql="SELECT label,surface FROM ".MAIN_DB_PREFIX."decor";
	
		$l = new TListviewTBS('listFilmObject'.$film->getId());
	
		echo $l->render($PDOdb, $sql,array(
			'no-select'=>true
			,'type'=>'chart'
			,'chartType'=>'PieChart'
			,'pieHole'=>0.6
		));
	}
	$formCore->end();
	

	
	

}
