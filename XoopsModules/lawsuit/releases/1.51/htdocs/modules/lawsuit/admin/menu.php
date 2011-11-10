<?php
// $Id: menu.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $
$module_handler = xoops_gethandler('module');
$GLOBALS['lawsuitModule'] = $module_handler->getByDirname('lawsuit');
global $adminmenu;
$adminmenu = array();
$adminmenu[-1]['title'] = _MI_LAWSUIT_ADMENU10;
$adminmenu[-1]['link'] = "admin/dashboard.php";
$adminmenu[-1]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.dashboard.png';
$adminmenu[0]['title'] = _MI_LAWSUIT_ADMENU1;
$adminmenu[0]['link'] = "admin/index.php?op=pages";
$adminmenu[0]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.pages.png';
$adminmenu[1]['title'] = _MI_LAWSUIT_ADMENU2;
$adminmenu[1]['link'] = "admin/index.php?op=category";
$adminmenu[1]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.category.png';
$adminmenu[2]['title'] = _MI_LAWSUIT_ADMENU3;
$adminmenu[2]['link'] = "admin/index.php?op=list";
$adminmenu[2]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.list.png';
$adminmenu[3]['title'] = _MI_LAWSUIT_ADMENU4;
$adminmenu[3]['link'] = "admin/index.php?op=edit";
$adminmenu[3]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.edit.png';
$adminmenu[4]['title'] = _MI_LAWSUIT_ADMENU5;
$adminmenu[4]['link'] = "admin/editelement.php";
$adminmenu[4]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.elements.png';
$adminmenu[5]['title'] = _MI_LAWSUIT_ADMENU7;
$adminmenu[5]['link'] = "admin/permissions.php";
$adminmenu[5]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.permissions.png';
$adminmenu[6]['title'] = _MI_LAWSUIT_ADMENU8;
$adminmenu[6]['link'] = "admin/validation.php";
$adminmenu[6]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.validation.png';
$adminmenu[7]['title'] = _MI_LAWSUIT_ADMENU6;
$adminmenu[7]['link'] = "admin/reports.php";
$adminmenu[7]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/lawsuit.reports.png';
$adminmenu[8]['title'] = _MI_LAWSUIT_ADMENU9;
$adminmenu[8]['link'] = "admin/about.php";
$adminmenu[8]['icon'] = '../../'.$GLOBALS['lawsuitModule']->getInfo('icons32').'/about.png';
?>
