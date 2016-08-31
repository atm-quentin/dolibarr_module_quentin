<?php
	
	require 'config.php';
	require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
	require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
	dol_include_once('/quentin/class/film.class.php');
	dol_include_once('/core/lib/files.lib.php');
	
	$action = GETPOST('action');
	
	$PDOdb=new TPDOdb;

	$film = new TFilm;
	
	llxHeader();
	dol_fiche_head();
	
	switch ($action) {
		case 'add':
			_card($film, 'edit');
			break;
		case 'save':
			$film->load($PDOdb, GETPOST('id'));
		
			$film->set_values($_POST);
			$film->save($PDOdb);
			
			if ($film->getId() > 0)
			{
				$image = $_POST['bt_file'];
				$imagename = $_FILES['bt_file']['name'];
				$imageurl = $_FILES['bt_file']['tmp_name'];
			
				$folder = DOL_DATA_ROOT.'/quentin/film/'.$film->getId();
				//dol_mkdir($folder);
				//$destination = $folder.'/'.$imagename;
				dol_add_file_process($folder, 1, 1, 'bt_file');
				//dol_move_uploaded_file($imageurl, $destination, 1);
			}			
			
			_card($film, 'view');
			break;
		
		case 'view':
			$film->load($PDOdb, GETPOST('id'));
	
			_card($film,'view');		
			break;
		
		case 'edit':
			$film->load($PDOdb, GETPOST('id'));
	
			_card($film,'edit');
			break;
			
		case 'delete':
			$film->load($PDOdb, GETPOST('id'));
			$langs->load("companies");	// Need for string DeleteFilse+ConfirmDeleteFiles
			$ret = $form->form_confirm(
					$_SERVER["PHP_SELF"] . '?id=' . $film->getId() . '&urlfile=' . urlencode(GETPOST("urlfile")) . '&linkid=' . GETPOST('linkid', 'int') . (empty($param)?'':$param),
					$langs->trans('DeleteFile'),
					$langs->trans('ConfirmDeleteFile'),
					'confirm_deletefile',
					'',
					0,
					1
			);
			if ($ret == 'html') print '<br>';
			
			
	
			_card($film,'view');
			break;
		case 'confirm_deletefile':
			$film->load($PDOdb, GETPOST('id'));
			$confirm=GETPOST('confirm');
			
			if($confirm=="yes"){
				$folder = DOL_DATA_ROOT.'/quentin/film/'.$film->getId();
				dol_delete_file(DOL_DATA_ROOT.'/quentin'.GETPOST('urlfile'), 0, 0, 0, $film);
				
			}
			_card($film,'view');
				break;
		
	
		default:
			_list();
			
			break;
	}
	
	dol_fiche_end();
	llxFooter();
		//var_dump($_POST);
		
	
	
	
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
	
	
	$formCore = new TFormCore('auto','formFilm','post', true);

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
		
		echo $formCore->fichier( '','bt_file', '',100000000);
		echo "<br/>";
		echo $formCore->btsubmit($langs->trans('Save'), 'bt_save');
		
		
	}
	else{
		echo $formCore->bt($langs->trans('Edit'), 'bt_edit', ' onclick="document.location.href = \'?action=edit&id='.$film->getId().'\'" ');
		
		/*
		 * 1 : trouver une fonction qui liste les fichiers d'un dossier
		 * 2 : trouver la fonction qui affiche mes images
		 * 
		 * 3 : les afficher (avec un icone "poubelle" à côté pour suppression)
		 */
		$upload_dir= DOL_DATA_ROOT.'/quentin/film/'.$film->getId();
		$filearray=dol_dir_list($upload_dir,"files",0,'','(\.meta|_preview\.png)$',$sortfield,(strtolower($sortorder)=='desc'?SORT_DESC:SORT_ASC),1);
		//var_dump($filearray);
		$formfile=new FormFile($db);
		$formfile->list_of_documents(
		    $filearray,
		    $film,
		    'quentin',
		    '&id='.$film->getId(),
		    0,
		    '/film/'.$film->getId().'/',		// relative path with no file. For example "moduledir/0/1"
		    true
		);
		//exit;
		/*echo '<table width="100%">';
		for($i=0;$i<count($filearray);$i++){
			echo '<tr>';
				echo '<td>';
				echo '<img border="0" height="50px" src="'.DOL_URL_ROOT.'/viewimage.php?modulepart=quentin&file='.urlencode('film/'.$film->getId().'/'.$filearray[$i]['name']).'" title="">';
				echo '</td>';
				//echo '<td><a href="?action=delete&url='.$upload_dir."/".$filearray[$i]['name'].'">'.img_picto('poubelle', 'delete2@quentin').'</a></td>';
			echo '</tr>';
		}
		echo '</table>';*/
		
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
