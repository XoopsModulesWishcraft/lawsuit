<?php
// $Id: forms.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $



if( !defined('LAWSUIT_ROOT_PATH') ){ exit(); }
class LawsuitForms extends XoopsObject {
	
	function __construct(&$db) {
		return $this->LawsuitForms();
	}
	
	function LawsuitForms(){
		$this->XoopsObject();
	//	key, data_type, value, req, max, opt
		$this->initVar("form_id", XOBJ_DTYPE_INT);
		$this->initVar("form_send_method", XOBJ_DTYPE_TXTBOX, 'e', true, 1);
		$this->initVar("form_send_to_group", XOBJ_DTYPE_TXTBOX, 1, false, 3);
		$this->initVar("form_order", XOBJ_DTYPE_INT, 1, false, 3);
		$this->initVar("form_delimiter", XOBJ_DTYPE_TXTBOX, 's', true, 1);
		$this->initVar("form_title", XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar("form_submit_text", XOBJ_DTYPE_TXTBOX, _SUBMIT, true, 50);
		$this->initVar("form_desc", XOBJ_DTYPE_TXTAREA);
		$this->initVar("form_intro", XOBJ_DTYPE_TXTAREA);
		$this->initVar("form_whereto", XOBJ_DTYPE_TXTBOX);
	}
}

class LawsuitFormsHandler extends XoopsPersistableObjectHandler {
	var $db;
	var $perm_name = 'lawsuit_form_access';
	var $obj_class = 'LawsuitForms';

	function __construct(&$db) {
		return $this->LawsuitFormsHandler(&$db);
	}
	
	function LawsuitFormsHandler(&$db){
		$this->db =& $db;
		$this->perm_handler =& xoops_gethandler('groupperm');
		parent::__construct($db, 'lawsuit_forms', 'LawsuitForms', "form_id", "form_title");	
	}

	function deleteFormPermissions($form_id){
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('gperm_itemid', $form_id)); 
		$criteria->add(new Criteria('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid')));
		$criteria->add(new Criteria('gperm_name', $this->perm_name)); 
		if( $old_perms =& $this->perm_handler->getObjects($criteria) ){
			foreach( $old_perms as $p ){
				$this->perm_handler->delete($p);
			}
		}
		return true;
	}
	
	function insertFormPermissions($form_id, $group_ids){
		
		foreach( $group_ids as $id ){
			$perm =& $this->perm_handler->create();
			$perm->setVar('gperm_name', $this->perm_name);
			$perm->setVar('gperm_itemid', $form_id);
			$perm->setVar('gperm_groupid', $id);
			$perm->setVar('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid'));
			$this->perm_handler->insert($perm);
		}
		return true;
	}
	
	function &getPermittedForms(){
		
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('form_order', 1, '>='), 'OR');
		$criteria->setSort('form_order');
		$criteria->setOrder('ASC');
		if( $forms =& $this->getObjects($criteria, 'home_list') ){
			$ret = array();
			foreach( $forms as $f ){
				if( false != $this->perm_handler->checkRight($this->perm_name, $f->getVar('form_id'), $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
					$ret[] = $f;
					unset($f);
				}
			}
			return $ret;
		}
		return false;
	}
	
	function getSingleFormPermission($form_id){
		
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		if( false != $this->perm_handler->checkRight($this->perm_name, $form_id, $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
			return true;
		}
		return false;
	}
	
}
?>