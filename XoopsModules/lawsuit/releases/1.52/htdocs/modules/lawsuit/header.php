<?php
// $Id: header.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $


include_once(dirname(dirname(dirname(__FILE__)))."/mainfile.php");
include_once(dirname(__FILE__)."/include/common.php");

$config_handler = xoops_gethandler('config');
$module_handler = xoops_gethandler('module');
$GLOBALS['lawsuitModule'] = $module_handler->getByDirname('lawsuit');
$GLOBALS['lawsuitModuleConfig'] = $config_handler->getConfigList($GLOBALS['lawsuitModule']->getVar('mid'));

if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	$GLOBALS['xoopsTpl'] = new XoopsTpl();
}
?>