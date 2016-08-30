<?php

	require 'config.php';
	
	dol_include_once('/quentin/class/film.class.php');
	
	$action = GETPOST('action');
	
	$PDOdb=new TPDOdb;
	
	$decor = new TDecor;
	
	llxHeader();
	dol_fiche_head();
	
	switch ($action) {
		case 'save':
			$decor->load($PDOdb, GETPOST('id'));
			$decor->set_values($_POST);
			$decor->save($PDOdb);
	
			_card($decor,'view');		
			break;
		
		case 'view':
			$decor->load($PDOdb, GETPOST('id'));
	
			_card($decor,'view');		
			break;
		
		case 'edit':
			$decor->load($PDOdb, GETPOST('id'));
	
			_card($decor,'edit');		
			break;
		
		case 'add':
			_card($decor,'edit');
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
	
	$sql="SELECT d.rowid as fk_decor, d.label, d.description, d.surface, d.fk_film, f.title
		FROM ".MAIN_DB_PREFIX."decor AS d 
		LEFT JOIN ".MAIN_DB_PREFIX."film AS f ON (d.fk_film = f.rowid)
	";
	$formCore = new TFormCore('auto','formDecor','post');
	$l = new TListviewTBS('listDecor');
	echo $l->render($PDOdb, $sql,array(
		'title'=>array(
			'fk_decor'=>$langs->trans('Id')
			,'label'=>$langs->trans('Decor')
			,'description'=>$langs->trans('Description')
			,'title'=>$langs->trans('Film')
			,'surface'=>$langs->trans('Surface')
		)
		,'hide' => array(
			'fk_film'
		)
		,'link'=>array(
			'label'=>'<a href="?action=view&id=@fk_decor@">@label@</a>',
			'title'=>'<a href="../quentin/film.php?action=view&id=@fk_film@&surface=@surface@">@title@</a>'
		)
		
			
		
	
	));
	
	$formCore->end();
}


	
function _card(&$decor, $mode) {

	global $conf, $langs, $user;

	$formCore = new TFormCore('auto','formDecor','post');

	$formCore->Set_typeaff($mode);

	echo $formCore->hidden('action', 'save');
	echo $formCore->hidden('id', $decor->getId());
	echo "<strong>Nom du décor : </strong>";
	echo $formCore->texte('','label', $decor->label, 80,255);
	echo '<hr />';
	echo "<strong>Surface (en m2) : </strong>";
	echo $formCore->texte('','surface', $decor->surface, 80,255);
	echo '<hr />';
	$PDOdb=new TPDOdb;
	echo "<strong>Nom du film : </strong>";
	echo $formCore->combo('','fk_film', TFilm::getAll($PDOdb), $decor->fk_film);
	echo '<hr />';
	echo "<strong>Description : </strong>";
	echo $formCore->zonetexte('', 'description', $decor->description, 80,5);
	echo '<hr />';
	
	if($mode == 'edit') {
		
		echo $formCore->btsubmit($langs->trans('Save'), 'bt_save');
			
	}
	else {
		echo $formCore->bt($langs->trans('Edit'), 'bt_edit', ' onclick="document.location.href = \'?action=edit&id='.$decor->getId().'\'" ');
	
	}
	
	
	$formCore->end();
	
}
