<?php
// $Id: forms.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $

class LawsuitValidation extends XoopsObject {
	function LawsuitValidation(){
		$this->XoopsObject();
		$this->initVar("rule_id", XOBJ_DTYPE_INT);
		$this->initVar("weight", XOBJ_DTYPE_INT);
		$this->initVar("type", XOBJ_DTYPE_OTHER);
		$this->initVar("action", XOBJ_DTYPE_UNICODE_TXTBOX, false, false, 65535);		
	}
}

class LawsuitValidationHandler extends XoopsObjectHandler {
	var $db;
	var $db_table;
	var $perm_name = 'lawsuit_validation';
	var $obj_class = 'LawsuitValidation';

	function LawsuitValidationHandler(&$db){
		$this->db =& $db;
		$this->db_table = $this->db->prefix('lawsuit_validation');
		$this->perm_handler =& xoops_gethandler('groupperm');
	}
	function &getInstance(&$db){
		static $instance;
		if( !isset($instance) ){
			$instance = new LawsuitValidationHandler($db);
		}
		return $instance;
	}
	function &create(){
		return new $this->obj_class();
	}

	function &get($id, $fields='*'){
		$id = intval($id);
		if( $id > 0 ){
			$sql = 'SELECT '.$fields.' FROM '.$this->db_table.' WHERE rule_id='.$id;
			if( !$result = $this->db->query($sql) ){
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if( $numrows == 1 ){
				$validation = new $this->obj_class();
				$validation->assignVars($this->db->fetchArray($result));
				return $validation;
			}
			return false;
		}
		return false;
	}

	function insert(&$validation, $force = false){
        if( strtolower(get_class($validation)) != strtolower($this->obj_class)){
            return false;
        }
        if( !$validation->isDirty() ){
            return true;
        }
        if( !$validation->cleanVars() ){
            return false;
        }
		foreach( $validation->cleanVars as $k=>$v ){
			${$k} = $v;
		}
		if( empty($rule_id) ){
			$rule_id = $this->db->genId($this->db_table."_rule_id_seq");
			$sql = sprintf("INSERT INTO %s (
				`rule_id`, `weight`, `type`, `action`) VALUES (
				%u, %u, %s, %s
				)",
				$this->db_table,
				$rule_id,
				$weight,
				$this->db->quoteString($type),
				$this->db->quoteString($action)
			);
		}else{
			$sql = sprintf("UPDATE %s SET `weight` = %u, `type` = %s, `action` = %s WHERE rule_id = %u",
				$this->db_table,
				$weight,
				$this->db->quoteString($type),
				$this->db->quoteString($action),
				$rule_id
			);
		}
        if( false != $force ){
            $result = $this->db->queryF($sql);
        }else{
            $result = $this->db->query($sql);
        }
		if( !$result ){
			$validation->setErrors("Could not store data in the database.<br />".$this->db->error().' ('.$this->db->errno().')<br />'.$sql);
			return false;
		}
		if( empty($rule_id) ){
			$rule_id = $this->db->getInsertId();
		}
		$validation->setVar('rule_id', $rule_id);
		return $rule_id;
	}
	
	function &getObjects($criteria = null, $fields='*', $id_as_key = false){
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT '.$fields.' FROM '.$this->db_table;
		if( isset($criteria) && is_subclass_of($criteria, 'criteriaelement') ){
			$sql .= ' '.$criteria->renderWhere();
			if( $criteria->getSort() != '' ){
				$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if( !$result ){
			return false;
		}
		while( $myrow = $this->db->fetchArray($result) ){
			$validations = new $this->obj_class();
			$validations->assignVars($myrow);
			if( !$id_as_key ){
				$ret[] =& $validations;
			}else{
				$ret[$myrow['rule_id']] =& $validations;
			}
			unset($validations);
		}
		return count($ret) > 0 ? $ret : false;
	}
	
    function getCount($criteria = null){
		$sql = 'SELECT COUNT(*) FROM '.$this->db_table;
		if( isset($criteria) && is_subclass_of($criteria, 'criteriaelement') ){
			$sql .= ' '.$criteria->renderWhere();
		}
		$result = $this->db->query($sql);
		if( !$result ){
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}
    
    function deleteAll($criteria = null){
		$sql = 'DELETE FROM '.$this->db_table;
		if( isset($criteria) && is_subclass_of($criteria, 'criteriaelement') ){
			$sql .= ' '.$criteria->renderWhere();
		}
		if( !$result = $this->db->query($sql) ){
			return false;
		}
		return true;
	}
	
	function deleteValidationPermissions($rule_id){
		global $xoopsModule;
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('gperm_itemid', $rule_id)); 
		$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid')));
		$criteria->add(new Criteria('gperm_name', $this->perm_name)); 
		if( $old_perms =& $this->perm_handler->getObjects($criteria) ){
			foreach( $old_perms as $p ){
				$this->perm_handler->delete($p);
			}
		}
		return true;
	}
	
	function insertValidationPermissions($rule_id, $group_ids){
		global $xoopsModule;
		foreach( $group_ids as $id ){
			$perm =& $this->perm_handler->create();
			$perm->setVar('gperm_name', $this->perm_name);
			$perm->setVar('gperm_itemid', $rule_id);
			$perm->setVar('gperm_groupid', $id);
			$perm->setVar('gperm_modid', $xoopsModule->getVar('mid'));
			$this->perm_handler->insert($perm);
		}
		return true;
	}
	
	function &getPermittedValidation(){
		global $xoopsUser, $xoopsModule;
		$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : 3;
		if( $validations =& $this->getObjects($criteria) ){
			$ret = array();
			foreach( $validations as $f ){
				if( false != $this->perm_handler->checkRight($this->perm_name, $f->getVar('rule_id'), $groups, $xoopsModule->getVar('mid')) ){
					$ret[] = $f;
					unset($f);
				}
			}
			return $ret;
		}
		return false;
	}
	
	function getSingleValidationPermission($rule_id){
		global $xoopsUser, $xoopsModule;
		$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : 3;
		if( false != $this->perm_handler->checkRight($this->perm_name, $rule_id, $groups, $xoopsModule->getVar('mid')) ){
			return true;
		}
		return false;
	}
	
}
?>