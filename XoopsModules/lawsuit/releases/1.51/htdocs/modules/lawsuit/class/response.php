<?php
// $Id: forms.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $



if( !defined('LAWSUIT_ROOT_PATH') ){ exit(); }
class LawsuitResponse extends XoopsObject {
	
	function __construct(&$db) {
		return $this->LawsuitResponse();
	}
	
	function LawsuitResponse(){
		$this->XoopsObject();
		$this->initVar("rid", XOBJ_DTYPE_INT);
		$this->initVar("cid", XOBJ_DTYPE_INT);
		$this->initVar("pid", XOBJ_DTYPE_INT);
		$this->initVar("form_id", XOBJ_DTYPE_INT);		
		$this->initVar("fingerprint", XOBJ_DTYPE_TXTBOX, false, false, 32);
		$this->initVar("response", XOBJ_DTYPE_ARRAY);
		$this->initVar("time_response", XOBJ_DTYPE_INT, false, false);
	}
}

class LawsuitResponseHandler extends XoopsPersistableObjectHandler {
	var $db;
	var $db_table;
	var $perm_name = 'lawsuit_response_access';
	var $obj_class = 'LawsuitResponse';

	function __construct(&$db) {
		return $this->LawsuitResponseHandler(&$db);
	}
	
	function LawsuitResponseHandler(&$db){
		$this->db =& $db;
		$this->perm_handler =& xoops_gethandler('groupperm');		
		parent::__construct($db, 'lawsuit_response', 'LawsuitResponse', "rid", "form_id");
	}

	function deleteResponsePermissions($rid){
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('gperm_itemid', $rid)); 
		$criteria->add(new Criteria('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid')));
		$criteria->add(new Criteria('gperm_name', $this->perm_name)); 
		if( $old_perms =& $this->perm_handler->getObjects($criteria) ){
			foreach( $old_perms as $p ){
				$this->perm_handler->delete($p);
			}
		}
		return true;
	}
	
	function insertResponsePermissions($rid, $group_ids){
		
		foreach( $group_ids as $id ){
			$perm =& $this->perm_handler->create();
			$perm->setVar('gperm_name', $this->perm_name);
			$perm->setVar('gperm_itemid', $rid);
			$perm->setVar('gperm_groupid', $id);
			$perm->setVar('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid'));
			$this->perm_handler->insert($perm);
		}
		return true;
	}
	
	function &getPermittedResponse(){
		
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		if( $responses =& $this->getObjects($criteria) ){
			$ret = array();
			foreach( $responses as $f ){
				if( false != $this->perm_handler->checkRight($this->perm_name, $f->getVar('rid'), $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
					$ret[] = $f;
					unset($f);
				}
			}
			return $ret;
		}
		return false;
	}
	
	function getSingleResponsePermission($rid){
		
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		if( false != $this->perm_handler->checkRight($this->perm_name, $rid, $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
			return true;
		}
		return false;
	}
	
}
?>