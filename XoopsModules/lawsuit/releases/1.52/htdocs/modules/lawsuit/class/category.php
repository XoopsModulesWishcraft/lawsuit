<?php
// $Id: forms.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $


class LawsuitCategory extends XoopsObject {
	
	function __construct(&$db) {
		return $this->LawsuitCategory();
	}
	
	function LawsuitCategory(){
		$this->XoopsObject();
		$this->initVar("cid", XOBJ_DTYPE_INT);
		$this->initVar("title", XOBJ_DTYPE_TXTBOX, false, true, 255);
		$this->initVar("domain", XOBJ_DTYPE_TXTBOX, false, true, 255);
		$this->initVar("domains", XOBJ_DTYPE_ARRAY, false, true);
	}
	
	function getURL() {
		if ($GLOBALS['lawsuitModuleConfig']['htaccess']) {
			return XOOPS_URL.'/'.$GLOBALS['lawsuitModuleConfig']['baseurl'].'/'.xoops_sef($this->getVar('title')).'/'.$this->getVar('cid').$GLOBALS['lawsuitModuleConfig']['endofurl'];
		} else {
			return XOOPS_URL.'/modules/lawsuit/index.php?cid='.$this->getVar('cid');
		}
	}
	
}

class LawsuitCategoryHandler extends XoopsPersistableObjectHandler {
	var $db;
	var $perm_name = 'lawsuit_category_access';
	var $obj_class = 'LawsuitCategory';

	function __construct(&$db) {
		return $this->LawsuitCategoryHandler(&$db);
	}
	
	function LawsuitCategoryHandler(&$db){
		$this->db =& $db;
		$this->perm_handler =& xoops_gethandler('groupperm');
		parent::__construct($db, 'lawsuit_category', 'LawsuitCategory', "cid", "title");		
	}
	
	function deleteCategoryPermissions($cid){
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('gperm_itemid', $cid)); 
		$criteria->add(new Criteria('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid')));
		$criteria->add(new Criteria('gperm_name', $this->perm_name)); 
		if( $old_perms =& $this->perm_handler->getObjects($criteria) ){
			foreach( $old_perms as $p ){
				$this->perm_handler->delete($p);
			}
		}
		return true;
	}
	
	function insertCategoryPermissions($cid, $group_ids){
		foreach( $group_ids as $id ){
			$perm =& $this->perm_handler->create();
			$perm->setVar('gperm_name', $this->perm_name);
			$perm->setVar('gperm_itemid', $cid);
			$perm->setVar('gperm_groupid', $id);
			$perm->setVar('gperm_modid', $GLOBALS['lawsuitModule']->getVar('mid'));
			$this->perm_handler->insert($perm);
		}
		return true;
	}
	
	function &getPermittedCategory(){
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		if( $categorys =& $this->getObjects(NULL) ){
			$ret = array();
			foreach( $categorys as $f ){
				if( false != $this->perm_handler->checkRight($this->perm_name, $f->getVar('cid'), $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
					$ret[] = $f;
					unset($f);
				}
			}
			return $ret;
		}
		return false;
	}
	
	function getSingleCategoryPermission($cid){
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : 3;
		if( false != $this->perm_handler->checkRight($this->perm_name, $cid, $groups, $GLOBALS['lawsuitModule']->getVar('mid')) ){
			return true;
		}
		return false;
	}
	
}
?>