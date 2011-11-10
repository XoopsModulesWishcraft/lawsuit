<?php
// $Id: admin_header.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $


include '../../../include/cp_header.php';

$GLOBALS['myts'] = MyTextSanitizer::getInstance();

$module_handler = xoops_gethandler('module');
$config_handler = xoops_gethandler('config');
$GLOBALS['lawsuitModule'] = $module_handler->getByDirname('lawsuit');
$GLOBALS['lawsuitModuleConfig'] = $config_handler->getConfigList($GLOBALS['lawsuitModule']->getVar('mid')); 
	
xoops_load('pagenav');	
xoops_load('xoopslists');
xoops_load('xoopsformloader');

include_once $GLOBALS['xoops']->path('class'.DS.'xoopsmailer.php');
include_once $GLOBALS['xoops']->path('class'.DS.'xoopstree.php');

if ( file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))){
        include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
        //return true;
    }else{
        echo lawsuit_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");
        //return false;
    }
$GLOBALS['lawsuitImageIcon'] = XOOPS_URL .'/'. $GLOBALS['lawsuitModule']->getInfo('icons16');
$GLOBALS['lawsuitImageAdmin'] = XOOPS_URL .'/'. $GLOBALS['lawsuitModule']->getInfo('icons32');

if ($GLOBALS['xoopsUser']) {
    $moduleperm_handler =& xoops_gethandler('groupperm');
    if (!$moduleperm_handler->checkRight('module_admin', $GLOBALS['lawsuitModule']->getVar( 'mid' ), $GLOBALS['xoopsUser']->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . "/user.php", 1, _NOPERM);
    exit();
}

if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	$GLOBALS['xoopsTpl'] = new XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('pathImageIcon', $GLOBALS['lawsuitImageIcon']);
$GLOBALS['xoopsTpl']->assign('pathImageAdmin', $GLOBALS['lawsuitImageAdmin']);

include dirname(dirname(__FILE__)).'/include/common.php';
include dirname(dirname(__FILE__)).'/include/forms.php';
define('LAWSUIT_ADMIN_URL', LAWSUIT_URL.'admin/index.php');

function adminHtmlHeader(){
	xoops_cp_header();
}
error_reporting(E_ALL);
?>