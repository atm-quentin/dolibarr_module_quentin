<?php

class TCCategoryFilm extends TObjetStd {

	function __construct() {
		parent::set_table(MAIN_DB_PREFIX.'c_categoryfilm');
		parent::add_champs('code', array('index'=>true) );
		parent::add_champs('label');
		parent::add_champs('active',array('type'=>'integer', 'index'=>true));
		
		parent::start();
		parent::_init_vars();
	}

	static function getAll(&$PDOdb) {
	$sql = "SELECT code, label FROM ".MAIN_DB_PREFIX."c_categoryfilm WHERE active = 1 ORDER BY label";
		/*$PDOdb->Execute($sql);
		$Tab=array();
		while($obj = $PDOdb->Get_line()) {
			
			$Tab[$obj->code] = $obj->label;
			
		}
		
		return $Tab;
	*/
	
		return TRequeteCore::_get_id_by_sql($PDOdb, $sql, 'label','code');
	}

} 

class TFilm extends TObjetStd {

	function __construct() {
		parent::set_table(MAIN_DB_PREFIX.'film');
		parent::add_champs('title', array('index'=>true) );
		parent::add_champs('description',array('type'=>'text'));
		parent::add_champs('code_c_categoryfilm',array('type'=>'string', 'index'=>true));
		
		parent::start();
		parent::_init_vars();
		
		$this->setChild('TDecor', 'fk_film');
	}
		static function getAll(&$PDOdb) {
	$sql = "SELECT rowid, title FROM ".MAIN_DB_PREFIX."film ORDER BY title";
		/*$PDOdb->Execute($sql);
		$Tab=array();
		while($obj = $PDOdb->Get_line()) {
			
			$Tab[$obj->code] = $obj->label;
			
		}
		
		return $Tab;
	*/
	
		return TRequeteCore::_get_id_by_sql($PDOdb, $sql, 'title','rowid');
	}

} 


class TDecor extends TObjetStd {

	function __construct() {
		parent::set_table(MAIN_DB_PREFIX.'decor');
		parent::add_champs('label', array('index'=>true) );
		parent::add_champs('description',array('type'=>'text'));
		parent::add_champs('fk_film',array('type'=>'integer', 'index'=>true));
		parent::add_champs('surface',array('type'=>'integers'));
		parent::start();
		parent::_init_vars();
		}
		
	
	

} 
