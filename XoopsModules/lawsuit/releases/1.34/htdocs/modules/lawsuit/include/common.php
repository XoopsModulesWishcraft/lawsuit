<?php
// $Id: common.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $
include('functions.php');

$module_handler = xoops_gethandler('module');
$xoopsModule =& $module_handler->getByDirname('lawsuit');

if( !defined("LAWSUIT_CONSTANTS_DEFINED") ){
	define("LAWSUIT_URL", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/');
	define("LAWSUIT_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/');
	define("LAWSUIT_UPLOAD_PATH", $xoopsModuleConfig['uploaddir'].'/');

	define("LAWSUIT_CONSTANTS_DEFINED", true);
}

$lawsuit_form_mgr =& xoops_getmodulehandler('forms', 'lawsuit');
$lawsuit_category_mgr =& xoops_getmodulehandler('category', 'lawsuit');
$lawsuit_response_mgr =& xoops_getmodulehandler('response', 'lawsuit');
$lawsuit_pages_mgr =& xoops_getmodulehandler('pages', 'lawsuit');

if( false != LAWSUIT_UPLOAD_PATH ){
	if( !is_dir(LAWSUIT_UPLOAD_PATH) ){
		$oldumask = umask(0);
		mkdir(LAWSUIT_UPLOAD_PATH, 0777);
		umask($oldumask);
	}
	if( is_dir(LAWSUIT_UPLOAD_PATH) && !is_writable(LAWSUIT_UPLOAD_PATH) ){
		chmod(LAWSUIT_UPLOAD_PATH, 0777);
	}
}

?>