<?php
// $Id: common.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $
include_once('functions.php');
include_once('forms.php');
include_once('categories.forms.php');
include_once('pages.forms.php');

$module_handler = xoops_gethandler('module');
$GLOBALS['lawsuitModule'] =& $module_handler->getByDirname('lawsuit');

if( !defined("LAWSUIT_CONSTANTS_DEFINED") ){
	define("LAWSUIT_URL", XOOPS_URL.'/modules/'.$GLOBALS['lawsuitModule']->getVar('dirname').'/');
	define("LAWSUIT_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.$GLOBALS['lawsuitModule']->getVar('dirname').'/');
	define("LAWSUIT_UPLOAD_PATH", $GLOBALS['lawsuitModuleConfig']['uploaddir'].'/');

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