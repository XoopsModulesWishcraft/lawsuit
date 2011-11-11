<?php
// $Id: forms.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $



if( !defined('LAWSUIT_ROOT_PATH') ){ exit(); }
class LawsuitPages extends XoopsObject {
	
	function __construct(&$db) {
		return $this->LawsuitPages();
	}
	
	function LawsuitPages(){
		$this->XoopsObject();
		$this->initVar("pid", XOBJ_DTYPE_INT);
		$this->initVar("cid", XOBJ_DTYPE_INT);		
		$this->initVar("form_id", XOBJ_DTYPE_INT);				
		$this->initVar("default", XOBJ_DTYPE_INT);						
		$this->initVar("weight", XOBJ_DTYPE_INT);						
		$this->initVar("html", XOBJ_DTYPE_OTHER);
		$this->initVar("title", XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar("description", XOBJ_DTYPE_TXTBOX, false, false, 255);
	}
	
	function getURL() {
		static $category;
		if (!is_object($category[$this->getVar('cid')])) {
			$category_handler =& xoops_getmodulehandler('category', 'lawsuit');
			$category[$this->getVar('cid')] = $category_handler->get($this->getVar('cid'));
		}
		error_reporting(E_ALL);
		if ($GLOBALS['lawsuitModuleConfig']['htaccess']) {
			return XOOPS_URL.'/'.$GLOBALS['lawsuitModuleConfig']['baseurl'].'/'.xoops_sef($category[$this->getVar('cid')]->getVar('title')).'/'.xoops_sef($this->getVar('title')).'/'.$this->getVar('pid').$GLOBALS['lawsuitModuleConfig']['endofurl'];
		} else {
			return XOOPS_URL.'/modules/lawsuit/index.php?id='.$this->getVar('pid');
		}
	}
}

class LawsuitPagesHandler extends XoopsPersistableObjectHandler {
	var $db;
	var $perm_name = 'lawsuit_pages_access';
	var $obj_class = 'LawsuitPages';

	function __construct(&$db) {
		return $this->LawsuitPagesHandler(&$db);
	}
	
	function LawsuitPagesHandler(&$db){
		$this->db =& $db;
		$this->perm_handler =& xoops_gethandler('groupperm');
		parent::__construct($db, 'lawsuit_pages', 'LawsuitPages', "pid", "form_id");		
	}
	
	function deletePagesPermissions($pid){
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('gperm_itemid', $pid)); 
		$criteria->add(new Criteria('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid')));
		$criteria->add(new Criteria('gperm_name', $this->perm_name)); 
		if( $old_perms =& $this->perm_handler->getObjects($criteria) ){
			foreach( $old_perms as $p ){
				$this->perm_handler->delete($p);
			}
		}
		return true;
	}
	
	function insertPagesPermissions($pid, $group_ids){
		
		foreach( $group_ids as $id ){
			$perm =& $this->perm_handler->create();
			$perm->setVar('gperm_name', $this->perm_name);
			$perm->setVar('gperm_itemid', $pid);
			$perm->setVar('gperm_groupid', $id);
			$perm->setVar('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid'));
			$this->perm_handler->insert($perm);
		}
		return true;
	}
	
	function &getPermittedPages(){
		
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('weight', 1, '>='), 'OR');
		$criteria->setSort('weight');
		$criteria->setOrder('ASC');
		if( $pages =& $this->getObjects($criteria) ){
			$ret = array();
			foreach( $pages as $f ){
				if( false != $this->perm_handler->checkRight($this->perm_name, $f->getVar('pid'), $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
					$ret[] = $f;
					unset($f);
				}
			}
			return $ret;
		}
		return false;
	}
	
	function getSinglePagePermission($pid){
		
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		if( false != $this->perm_handler->checkRight($this->perm_name, $pid, $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
			return true;
		}
		return false;
	}
	
}
?>