<?php
// $Id: forms.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $

class LawsuitValidation extends XoopsObject {
	function __construct(&$db) {
		return $this->LawsuitValidation();
	}
	
	function LawsuitValidation(){
		$this->XoopsObject();
		$this->initVar("rule_id", XOBJ_DTYPE_INT);
		$this->initVar("weight", XOBJ_DTYPE_INT);
		$this->initVar("type", XOBJ_DTYPE_OTHER);
		$this->initVar("action", XOBJ_DTYPE_OTHER, false, false, 65535);		
	}
}

class LawsuitValidationHandler extends XoopsPersistableObjectHandler {

	var $db;
	var $perm_name = 'lawsuit_validation';
	var $obj_class = 'LawsuitValidation';

	function __construct(&$db) {
		return $this->LawsuitValidationHandler(&$db);
	}
	
	function LawsuitValidationHandler(&$db){
		$this->db =& $db;
		$this->perm_handler =& xoops_gethandler('groupperm');
		parent::__construct($db, 'lawsuit_validation', 'LawsuitValidation', "rule_id", "type");
	}

	function deleteFormPermissions($rule_id){
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('gperm_itemid', $rule_id)); 
		$criteria->add(new Criteria('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid')));
		$criteria->add(new Criteria('gperm_name', $this->perm_name)); 
		if( $old_perms =& $this->perm_handler->getObjects($criteria) ){
			foreach( $old_perms as $p ){
				$this->perm_handler->delete($p);
			}
		}
		return true;
	}
	
	function insertFormPermissions($rule_id, $group_ids){
		
		foreach( $group_ids as $id ){
			$perm =& $this->perm_handler->create();
			$perm->setVar('gperm_name', $this->perm_name);
			$perm->setVar('gperm_itemid', $rule_id);
			$perm->setVar('gperm_groupid', $id);
			$perm->setVar('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid'));
			$this->perm_handler->insert($perm);
		}
		return true;
	}
	
	function &getPermittedForm(){
		
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		if( $validations =& $this->getObjects($criteria) ){
			$ret = array();
			foreach( $validations as $f ){
				if( false != $this->perm_handler->checkRight($this->perm_name, $f->getVar('rule_id'), $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
					$ret[] = $f;
					unset($f);
				}
			}
			return $ret;
		}
		return false;
	}
	
	function getSingleValidationPermission($rule_id){
		
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		if( false != $this->perm_handler->checkRight($this->perm_name, $rule_id, $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
			return true;
		}
		return false;
	}
	
}
?>